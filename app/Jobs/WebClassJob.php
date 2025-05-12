<?php

namespace App\Jobs;

use App\Models\Event;
use App\Repository\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Mailjet\LaravelMailjet\Facades\Mailjet;
use Mailjet\Resources;

class WebClassJob implements ShouldQueue
{
    use Queueable;

    private $mj;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Event $event,
        private readonly UserRepository $userRepository,
        private readonly bool $modelChanged = false,
        private readonly array $updateVars = [],
    ) {
        $this->mj = Mailjet::getClient();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        match($this->event->type_notification) {
            'all' => $this->notifyWebclassForAll($this->event),
            'cities' => $this->notifyWebclassForCities($this->event),
            'group' => $this->notifyWebclassForGroup($this->event),
            'none' => ''
        };
    }

    private function notifyWebclassForAll($event)
    {
        $userList = $this->userRepository->getModel()->select('name', 'email')->get();
        $userList = $userList->map(fn($user) => ['Name' => $user->name,'Email' => $user->email])->all();
        $body = $this->presetWebClassCreatedEmailNotification($event, $userList);
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);

        if(!$response->success()) {
            $this->storeLogErrors($event, $response);
        }

    }

    private function notifyWebclassForCities($event)
    {
        $cityList = (isset($this->updateVars['cities'])) ? $this->updateVars['cities'] : json_decode($event->cities_to_notify);
        $userList = $this->userRepository->getModel()
                                ->select('tb_users.name', 'tb_users.email')
                                ->join('tb_establishment_users', 'tb_establishment_users.user_id', '=', 'tb_users.id')
                                ->join('tb_establishments', 'tb_establishments.id', '=', 'tb_establishment_users.establishment_id')
                                ->join('tb_cities', 'tb_cities.id', '=', 'tb_establishments.city_id')
                                ->where('tb_establishment_users.primary_bond', true)
                                ->whereIn('tb_cities.uuid', $cityList)
                                ->get()
                                ->toArray();

        $body = $this->presetWebClassCreatedEmailNotification($event, $userList);

        if (count($userList)) {
            $response = $this->mj->post(Resources::$Email, ['body' => $body]);

            if(!$response->success()) {
                $this->storeLogErrors($event, $response);
            }
        }
    }

    private function notifyWebclassForGroup($event)
    {
        $userList = (isset($this->updateVars['emails'])) ? $this->updateVars['emails'] : json_decode($event->select_group_emails);
        $userList = collect($userList)->map(fn($email) => ['Name' => substr($email, 0, strpos($email, '@')), 'Email' => $email])->values()->all();
        $body = $this->presetWebClassCreatedEmailNotification($event, $userList);
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);

        if(!$response->success()) {
            $this->storeLogErrors($event, $response);
        }
    }

    private function notifyAdminForSendEmails($event)
    {
        $date = now()->format('d/m/Y');
        $reqId = request()->header('X-Request-ID');
        $adminEmails = config('app.admin_emails');
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'name' => config('app.mail_from_name'),
                        'email' => config('app.mail_from_address')
                    ],
                    'To' => collect($adminEmails)->map(fn($email) => ['Name' => 'admin', 'Email' => $email])->values()->all(),
                    'Subject' => "Erro ao enviar email de nova agenda de webaula",
                    'HTMLPart' => "Olá admin, <br/> <br/> O sistema informa que houve um erro ao enviar o email de notificação de nova webaula para os usuários. <br/> <br/>"
                    . "<strong>Reqid:</strong> {$reqId} <br/> <strong>Tema:</strong> {$event->name} <br/> <strong>Data criação:</strong> {$event->created_at_datetime_formatted} <br/> <br/>"
                    . "Confira os arquivos de log dentro da pasta do sistema do dia {$date}."
                ]
            ]
        ];

        $this->mj->post(Resources::$Email, ['body' => $body]);
    }

    private function presetWebClassCreatedEmailNotification($event, $userList)
    {
        $templateId = $this->presetTemplateId($event);
        $vars = $this->prepareVariables($event);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Name' => config('app.mail_from_name'),
                        'Email' => config('app.mail_from_address')
                    ],
                    'To' => $userList,
                    'TemplateID' => $templateId,
                    'TemplateLanguage' => true,
                    'Subject' => ($this->modelChanged) ? 'Aviso de alteração de webaula' : 'Aviso de nova agenda de webaula',
                    'Variables' => $vars
                ],
            ]
        ];

        if ($event->attachment) {
            $extension = pathinfo(storage_path("app/private/{$event->attachment->path}"), PATHINFO_EXTENSION);

            $body['Messages'][0]['InlinedAttachments'][] = [
                    'ContentType' => 'application/octet-stream',
                    'Filename' => "banner.{$extension}",
                    'ContentID' => 'id1',
                    'Base64Content' => base64_encode(file_get_contents(storage_path("app/private/{$event->attachment->path}")))
            ];
        }

        return $body;
    }

    private function presetTemplateId($event)
    {
        if ($event->organization === 'Fiocruz') {
            return ($this->modelChanged) ? 6968736 : 6922462;
        }

        return ($this->modelChanged) ? 6968746 : 6906817;
    }

    private function prepareVariables($event): array
    {
        $link = config('app.url') . '/webaulas';
        $theme = $event->name;
        $startAt = $event->start_at_datetime_formatted;

        if ($this->modelChanged) {
            $updateVars = [];

            if (count($this->updateVars)) {
                $updateVars['theme'] = $this->updateVars['name_attrs']['original_name'] ?? '';
                $updateVars['updated_theme'] = $this->updateVars['name_attrs']['updated_name'] ?? '';
                $updateVars['start_at'] = $this->updateVars['start_at_attrs']['original_start_at'] ?? '';
                $updateVars['updated_start_at'] = $this->updateVars['start_at_attrs']['updated_start_at'] ?? '';
            }

            return json_decode(json_encode(['link' => $link, ...$updateVars]), true);
        }

        return json_decode(json_encode(['link' => $link, 'theme' => $theme, 'start_at' => $startAt]), true);
    }

    private function storeLogErrors($event, $response)
    {
        Log::error("Erro ao enviar email de notificação de nova webaula", [
            'code' => $response->getReasonPhrase(),
            'error_api' => $response->getStatus(),
            'errors' => $response->getData()
        ]);
        $this->notifyAdminForSendEmails($event);
    }
}
