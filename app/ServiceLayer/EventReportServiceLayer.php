<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\EventReportInterface as EventReportRepository;
use App\ServiceLayer\Base\ServiceResource;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventReportServiceLayer extends ServiceResource {
    public function __construct(
        private readonly EventReportRepository $eventReportRepository,
    )
    {
        $this->repository = $eventReportRepository;
    }

    public function pdfPrint($data)
    {
        $eventList = $this->eventReportRepository->getData($data);
        $logoTSMS = 'data:image/png;base64,' . base64_encode(file_get_contents(resource_path('/images/logo-telessaude.png')));
        $logoSES = 'data:image/png;base64,' . base64_encode(file_get_contents(resource_path('/images/logo-ses.png')));
        $pdf = Pdf::loadView('webs-report', ['eventList' => $eventList, 'logoTSMS' => $logoTSMS, 'logoSES' => $logoSES])->setPaper('A4', 'landscape')->setOption('enable_php', true);

        return $pdf->stream('relatório-web-aulas.pdf');
    }

    public function excelPrint($data)
    {
        $headers = [
            'Tema',
            'Início',
            'Fim',
            'Organização',
            'Desc. Bireme',
            'Participante',
            'Data Participação',
            'Ocupação',
            'UF',
            'Cidade',
            'Macro Região',
            'Micro Região',
            'Data avaliação',
            'Avaliação',
            'Avaliação sobre o horário',
            'Sugestão'
        ];
        $eventList = $this->eventReportRepository->getData($data);
        $eventList = $eventList->map(fn($event) => [
            'name' => $event->name,
            'start_at' => $event->start_at_datetime_formatted,
            'end_at' => $event->end_at_datetime_formatted,
            'organization' => $event->organization,
            'descs' => $event->descs,
            'participant' => $event->participant,
            'signed_up_at' => $event->signed_up_at,
            'cbo' => $event->cbo,
            'state' => $event->state,
            'city' => $event->city,
            'macro_zone' => $event->macro_zone,
            'micro_zone' => $event->micro_zone,
            'rated_at' => $event->rated_at ?? 'Não avaliado',
            'rating_event' => $this->processRating($event->rating_event),
            'rating_event_schedule' => $this->processRating($event->rating_event_schedule),
            'hint' => $event->hint ?? 'Não informado'
        ])
        ->toArray();

        array_unshift($eventList, $headers);

        return new StreamedResponse(function() use($eventList) {
            $spreadsheet = new Spreadsheet();
            $writer = new Xlsx($spreadsheet);
            $sheet = $spreadsheet->getActiveSheet();
            $headerStyle = ['font' => ['bold' => true], 'size' => 14];

            $sheet->getStyle('A1:P1')->applyfromArray($headerStyle);
            $sheet->fromArray($eventList, null, 'A1');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename='arquivo.xlsx'",
            'Cache-Control' => 'no-cache, no-store, must-revalidate'
        ]);
    }

    private function processRating($rating)
    {
        return [
            9 => 'Não informado',
            1 => 'Muito insatisfeito',
            2 => 'Insatisfeito',
            3 => 'Indiferente',
            4 => 'Satisfeito',
            5 => 'Muito satisfeito',
        ][$rating];
    }
}