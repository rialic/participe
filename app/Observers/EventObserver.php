<?php

namespace App\Observers;

use App\Jobs\WebClassJob;
use App\Models\Event;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Log;
use Mailjet\LaravelMailjet\Facades\Mailjet;
use Mailjet\Resources;

class EventObserver
{
    private $mj;

    public function __construct(
        private readonly UserRepository $userRepository,
    ){
        $this->mj = Mailjet::getClient();
    }

    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        // WebClassJob::dispatch($event);

        match($event->type_notification) {
            'all' => $this->notifyWebclassForAll($event),
            'cities' => $this->notifyWebclassForCities($event),
            'group' => $this->notifyWebclassForGroup($event),
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
            $response = $this->extractErrorMessages($response->getData());

            Log::error("Erro ao enviar email de notificação de nova webaula", $response);
            $this->notifyAdminForSendEmails($event);
        }

    }

    private function notifyWebclassForCities($event)
    {
        $userList = $this->userRepository->getModel()
                                ->select('tb_users.name', 'tb_users.email')
                                ->join('tb_establishment_users', 'tb_establishment_users.user_id', '=', 'tb_users.id')
                                ->join('tb_establishments', 'tb_establishments.id', '=', 'tb_establishment_users.establishment_id')
                                ->join('tb_cities', 'tb_cities.id', '=', 'tb_establishments.city_id')
                                ->where('tb_establishment_users.primary_bond', true)
                                ->whereIn('tb_cities.uuid', json_decode($event->cities_to_notify))
                                ->get()
                                ->toArray();
        $body = $this->presetWebClassCreatedEmailNotification($event, $userList);
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);

        if(!$response->success()) {
            $response = $this->extractErrorMessages($response->getData());

            Log::error("Erro ao enviar email de notificação de nova webaula", $response);
            $this->notifyAdminForSendEmails($event);
        }
    }

    private function notifyWebclassForGroup($event)
    {
        $userList = json_decode($event->select_group_emails);
        $userList = collect($userList)->map(fn($email) => ['Name' => substr($email, 0, strpos($email, '@')), 'Email' => $email])->all();
        $body = $this->presetWebClassCreatedEmailNotification($event, $userList);
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);

        if(!$response->success()) {
            $response = $this->extractErrorMessages($response->getData());

            Log::error("Erro ao enviar email de notificação de nova webaula", $response);
            $this->notifyAdminForSendEmails($event);
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
                    'To' => collect($adminEmails)->map(fn($email) => ['Name' => 'admin', 'Email' => $email])->all(),
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
        $vars = json_decode(json_encode(['link' => config('app.url') . '/webaulas', 'theme' => $event->name, 'start_at' => $event->start_at_datetime_formatted]), true);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Name' => config('app.mail_from_name'),
                        'Email' => config('app.mail_from_address')
                    ],
                    'To' => $userList,
                    'TemplateID' => ($event->organization === 'Fiocruz') ? 6922462 : 6906817,
                    'TemplateLanguage' => true,
                    'Subject' => config('app.name') . ' - Aviso de nova agenda de webaula',
                    'Variables' => $vars
                ],
            ]
        ];

        return $body;
    }

    private function extractErrorMessages(array $response): array
    {
        $errorMessages = [];

        if (!isset($response['Messages'])) {
            return $errorMessages;
        }

        foreach ($response['Messages'] as $_ => $message) {
            if (isset($message['Errors']) && is_array($message['Errors'])) {
                $innerErrors = [];

                foreach ($message['Errors'] as $error) {
                    if (isset($error['ErrorMessage'])) {
                        $innerErrors[] =  "ErrorIdentifier : {$error['ErrorIdentifier']} - {$error['ErrorMessage']}";
                    }
                }

                if (count($innerErrors) === 1) {
                    $errorMessages[] = $innerErrors[0];
                    continue;
                }

                $errorMessages[] = $innerErrors;
            }
        }

        return $errorMessages;
    }
}
