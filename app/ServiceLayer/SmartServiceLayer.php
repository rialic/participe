<?php

namespace App\ServiceLayer;

use App\Exceptions\ApiException;
use App\Models\Establishment;
use App\Models\Event;
use App\Models\User;
use App\Repository\Interfaces\SmartInterface as SmartRepository;
use App\ServiceLayer\Base\ServiceResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Builder;

class SmartServiceLayer extends ServiceResource
{
    private readonly string $token;
    private readonly string $coreCode;
    private readonly string $apiSmartEstablishment;
    private readonly string $apiSmartHealthProfessional;
    private readonly string $apiSmartTeleEducation;
    private $apiResponse;

    public function __construct(
        private readonly Establishment $establishment,
        private readonly Event $event,
        private readonly User $user,
        private readonly SmartRepository $smartRepository,
    ) {
        $this->repository = $smartRepository;
        $this->apiSmartEstablishment = config('app.api_smart_establishment');
        $this->apiSmartHealthProfessional = config('app.api_smart_professional');
        $this->apiSmartTeleEducation = config('app.api_smart_teleeducation');
        $this->token = config('app.token');
        $this->coreCode = config('app.core_code');
    }

    public function sendEstablishments(array $data): JsonResource|JsonResponse
    {
        $establishmentList = $this->buildEstablishmentsQuery($data)->get();
        $payload = $this->preparePayload($data, 'estabelecimentos', $establishmentList->toArray());

        return $this->sendToSmartApi($this->apiSmartEstablishment, $payload);
    }

    public function sendProfessionals(array $data): JsonResource|JsonResponse
    {
        $professionalsList = $this->buildProfessionalsQuery($data)->get();
        $formattedProfessionals = $this->formatProfessionalsData($professionalsList);
        $payload = $this->preparePayload($data, 'profissionais', $formattedProfessionals);

        return $this->sendToSmartApi($this->apiSmartHealthProfessional, $payload);
    }

    public function sendWebs(array $data): JsonResource|JsonResponse
    {
        $websList = $this->buildWebsQuery()->get();
        $websList = $this->formatWebsData($websList);
        $payload = $this->preparePayload($data, 'atividades_teleeducacao', $websList);

        return $this->sendToSmartApi($this->apiSmartTeleEducation, $payload);
    }

    private function buildEstablishmentsQuery($data): Builder
    {
        $query = $this->establishment
            ->selectRaw('cnes, tdiagn, teduca, tconsul')
            ->join('tb_cities', 'tb_cities.id', '=', 'tb_establishments.city_id')
            ->join('tb_states', 'tb_states.id', '=', 'tb_cities.state_id')
            ->where('acronym', 'MS')
            ->where('legal_nature', 'ADMINISTRAÇÃO PÚBLICA')
            ->where('sus', 'SIM')
            ->where('cnes', '!=', '9999999');

        return $this->applyTagFilters($query, $data, 'cnes');
    }

    private function buildProfessionalsQuery($data): Builder
    {
        $eventsQuery = $this->buildEventsParticipantsQuery($data);
        $usersQuery = $this->buildUsersQuery($data);

        return $eventsQuery->union($usersQuery);
    }

    private function buildWebsQuery(): Builder
    {
        return $this->event
            ->whereNull('deleted_at')
            ->whereRaw("start_at between date_format(date_sub(curdate(), interval 1 month), '%Y-%m-01') and last_day(date_sub(curdate(), interval 1 month))")
            ->whereHas('participants')
            ->with([
                'participants.cbos' => fn($query) => $query->where('primary_bond', true),
                'participants.establishments' => fn($query) => $query->where('primary_bond', true),
                'descs'
            ]);
    }

    private function formatWebsData($websList): array
    {
        $typeEventList = ['Curso' => 1, 'Webaulas/palestras' => 2];
        return $websList->map(fn($event) => [
            'id' => "MS{$event->id}",
            'tipo' => $typeEventList[$event->type_event],
            'origemf' => '',
            'dtdispo' => $event->start_at_datetime_formatted,
            'cargah' => $event->workload,
            'decs' => $event->descs->map(fn($descs) => $descs->bireme_code)->toArray(),
            'participacoes_teleeducacao' => $event->participants->map(fn($participant) => [
                'id' => "MS{$event->id}",
                'satisf' => $participant->pivot->rating_event,
                'dtparti' => $participant->pivot->created_at->format('d/m/Y H:i:s'),
                'cpf' => str_replace(['.', '-'], ['', ''], $participant->cpf),
                'cbo' => $participant->cbos->first()->code,
                'cnes' => $participant->establishments->first()->cnes,
                'ine' => ''
            ])
        ])
            ->all();
    }

    private function buildEventsParticipantsQuery($data): Builder
    {
        $query = $this->event
            ->selectRaw('tb_users.name as nome, cpf, sex, "01" as tprof, "" as cns, cnes, code as cbo, "" as ine')
            ->join('tb_event_participants', 'tb_event_participants.event_id', '=', 'tb_events.id')
            ->join('tb_users', 'tb_event_participants.user_id', '=', 'tb_users.id')
            ->join('tb_establishment_users', 'tb_establishment_users.user_id', 'tb_users.id')
            ->join('tb_cbos', 'tb_establishment_users.cbo_id', 'tb_cbos.id')
            ->join('tb_establishments', 'tb_establishment_users.establishment_id', 'tb_establishments.id')
            ->whereNull('tb_events.deleted_at')
            ->where('primary_bond', true)
            ->whereRaw($this->gePreviousMonthDateRange());

        return $this->applyTagFilters($query, $data, 'cpf');
    }

    private function buildUsersQuery($data): Builder
    {
        $query = $this->user
            ->selectRaw('tb_users.name as nome, cpf, sex, "01" as tprof, "" as cns, cnes, code as cbo, "" as ine')
            ->join('tb_establishment_users', 'tb_establishment_users.user_id', 'tb_users.id')
            ->join('tb_cbos', 'tb_establishment_users.cbo_id', 'tb_cbos.id')
            ->join('tb_establishments', 'tb_establishment_users.establishment_id', 'tb_establishments.id')
            ->where('primary_bond', true)
            ->whereRaw("tb_users.created_at between date_format(date_sub(curdate(), interval 1 month), '%Y-%m-01') and last_day(date_sub(curdate(), interval 1 month))");

        return $this->applyIncludeTagFilter($query, $data, 'cpf');
    }

    private function applyTagFilters(Builder $query, $data, string $field): Builder
    {
        return $query->where(function ($subQuery) use ($data, $field) {
            if (!empty($data['include_tag_list'])) {
                $subQuery->orWhereIn($field, $data['include_tag_list']);
            }

            if (!empty($data['exclude_tag_list'])) {
                $subQuery->orWhereNotIn($field, $data['exclude_tag_list']);
            }
        });
    }

    private function applyIncludeTagFilter(Builder $query, $data, string $field): Builder
    {
        return $query->where(function ($subQuery) use ($data, $field) {
            if (!empty($data['include_tag_list'])) {
                $subQuery->orWhereIn($field, $data['include_tag_list']);
            }
        });
    }

    private function gePreviousMonthDateRange(): string
    {
        return "start_at between date_format(date_sub(curdate(), interval 1 month), '%Y-%m-01') and last_day(date_sub(curdate(), interval 1 month))";
    }

    private function formatProfessionalsData($professionalsList): array
    {
        return $professionalsList->map(function ($professional) {
            return [
                'nome' => $professional->nome,
                'cpf' => $this->formatCpf($professional->cpf),
                'sexo' => $this->formatSex($professional->sex),
                'tprof' => $professional->tprof,
                'cns' => $professional->cns,
                'cnes' => $professional->cnes,
                'cbo' => $professional->cbo,
                'ine' => $professional->ine
            ];
        })->toArray();
    }

    private function formatCpf(string $cpf): string
    {
        return str_replace(['.', '-'], '', $cpf);
    }

    private function formatSex(string $sex): string
    {
        return substr($sex, 0, 1);
    }

    private function preparePayload($data, string $listName, array $list): array
    {
        return [
            'tipo_envio' => $data['dispatch_type'],
            'mes_referencia' => $data['custom_date'] ? str_replace('/', '', $data['custom_date']) : now()->subMonths(1)->format('mY'),
            'codigo_nucleo' => $this->coreCode,
            $listName => $list
        ];
    }

    private function sendToSmartApi(string $api, array $payload): JsonResource|JsonResponse
    {
        try {
            $this->apiResponse = $this->makeHttpRequest($api, $payload);

            return $this->handleApiResponse();
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    private function makeHttpRequest(string $api, array $payload)
    {
        return Http::withHeaders([
            "Authorization" => "Token {$this->token}"
        ])->withMiddleware(function ($handler) {
            return function ($request, $options) use ($handler) {
                // Função para inspecionar a requisição antes de enviá-la
                // Log::debug('Request body:', ['body' => (string) $request->getBody()]);
                return $handler($request, $options);
            };
        })->post($api, $payload);
    }

    private function handleApiResponse(): JsonResponse
    {
        $status = $this->apiResponse->status();
        $message = $this->extractMessageFromResponse();

        if ($this->apiResponse->successful()) {
            return response()->json([
                'status' => $status,
                'message' => $message
            ], $status);
        }

        return response()->json([
            'status' => $status,
            'errors' => ['message' => 'Erro ao enviar os dados: ' . $message]
        ], $status);
    }

    private function extractMessageFromResponse(): string
    {
        $responseData = $this->apiResponse->json();
        return isset($responseData['message']) ? implode(' ', $responseData['message']) : 'Resposta sem mensagem';
    }

    private function handleException(\Exception $e): JsonResponse
    {
        $message = $this->apiResponse ? $this->extractMessageFromResponse() : $e->getMessage();
        return ApiException::handleException($e, $message);
    }
}
