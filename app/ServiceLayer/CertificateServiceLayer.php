<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\CertificateInterface as CertificateRepository;
use App\Repository\EventRepository;
use App\ServiceLayer\Base\ServiceResource;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Laravel\Facades\Image;

class CertificateServiceLayer extends ServiceResource
{
    public function __construct(
        private readonly CertificateRepository $certificateRepository,
        private readonly EventRepository $eventRepository
    )
    {
        $this->repository = $this->certificateRepository;
    }

    public function show(string|array $data): ?object
    {
        return $this->repository->getFirstData($data);
    }

    public function print($data)
    {
        $event = $this->eventRepository->getModel()->where('uuid', $data['event_uuid'])
                            ->with('participants', fn($participant) => $participant->where('uuid', $data['user_uuid']))
                            ->first();

        $response = match($event['organization']) {
            'Fiocruz' => $this->generateFiocruzCertificate($event),
            'TSMS' => $this->generateTSMSCertificate($event)
        };

        return $response;
    }

    public function generateFiocruzCertificate($event)
    {
            $image = Image::read(resource_path('images/certificado-fiocruz.jpg'));
            $imageWidth = $image->width() / 2;
            $imageHeight = $image->height() / 2;

            $maxLength = 65;
            $fontSize = 35;
            $fontHeight = 25;
            $imageHeight = 420;
            $participant = mb_strtoupper($event->participants[0]->name, 'utf8');
            $eventName = mb_strtoupper($event->name, 'utf8');
            $text = "Declaramos que {$participant} participou da webaula intitulada {$eventName}, no dia {$event->start_at_formatted} com carga horária de {$event->workload_formatted}.";
            $lines = explode("\n", wordwrap($text, $maxLength));

            foreach($lines as $index => $line) {
                $image->text($line, $imageWidth, $imageHeight, function($font) use($fontSize) {
                    $font->file(resource_path('fonts/Poppins-Regular.ttf'));
                    $font->size($fontSize);
                    $font->color('#222222');
                    $font->align('center');
                    $font->valign('middle');
                });

                $imageHeight += $fontHeight * 2;
            }

            $image->text('Campo Grande, MS ', $imageWidth + 50, 740, function($font) use($fontSize) {
                $font->file(resource_path('fonts/Poppins-Regular.ttf'));
                $font->size($fontSize);
                $font->color('#222222');
                $font->align('right');
                $font->valign('middle');
            });

            $image->text(now()->format('d/m/Y'),  $imageWidth + 50, 740, function($font) use($fontSize) {
                $font->file(resource_path('fonts/Poppins-SemiBold.ttf'));
                $font->size($fontSize);
                $font->color('#222222');
                $font->align('left');
                $font->valign('middle');
            });

            return response()->image($image, quality: 65);
    }

    public function generateTSMSCertificate($event)
    {
        $logoTSMS = 'data:image/png;base64,' . base64_encode(file_get_contents(resource_path('/images/logo-telessaude.png')));
        $logoSES = 'data:image/png;base64,' . base64_encode(file_get_contents(resource_path('/images/logo-ses.png')));
        $directorSignature = 'data:image/png;base64,' . base64_encode(file_get_contents(resource_path('/images/ass-andre.png')));

        // REGRA DE ASSINATURA POR DATA
        if (Carbon::parse($event->ev_data_inicio_evento)->lt(Carbon::parse('2024-11-27'))) {
            $coordinatorSignature = 'data:image/png;base64,' . base64_encode(file_get_contents(resource_path('/images/ass-marcia.png')));
        }else {
            $coordinatorSignature = 'data:image/png;base64,' . base64_encode(file_get_contents(resource_path('/images/ass-rosangela.png')));
        }

        $pdf = Pdf::loadView('certificate', [
            'event' => $event,
            'directorSignature' => $directorSignature,
            'coordinatorSignature' => $coordinatorSignature,
            'logoTSMS' => $logoTSMS,
            'logoSES' => $logoSES
        ]);

        return $pdf->stream("{$event->name}.pdf");
    }
}