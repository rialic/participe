<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Certificado de Participação</title>
  <style>
    html,
    body {
      font-family: Arial, Helvetica, sans-serif;
    }

    main {
      margin: 40px;
      max-width: 650px;
      margin: 0 auto;
    }

    .logo-tele {
      float: left;
    }

    .logo-ses {
      float: right;
    }

    header {
      text-align: center;
    }

    header>h3 {
      font-size: 22px;
      margin: 5;
    }

    header>h1 {
      font-size: 30px;
      margin-top: 80px;
    }

    .text-body {
      font-size: 22px;
      margin-top: 80px;
      text-align: justify;
      line-height: 1.7;
    }

    .event-date {
      margin: 80px 0px 100px 0px;
      text-align: right;
    }

    div {
      margin: 0 auto;
      text-align: center;
      display: block;
      width: 500px;
    }

    div>p {
      margin: 5;
    }

    .signature {
      font-size: 18px;
    }

    .director {
      float: left;
    }

    .coordinator {
      float: right;
    }

    img {
      display: block;
    }

    table {
      width: 100%;
      margin: 0 auto;
    }

    td {
      text-align: center;
      margin: 0;
    }
  </style>
</head>

<body>
  <main>
    <header>
      <div>
        <img class="logo-tele" src="{{ $logoTSMS }}" width="70px">
        <img class="logo-ses" src="{{ $logoSES }}" width="200px"/>
      </div>

      <h3 style="margin-top: 100px">Secretaria de Estado de Saúde</h3>

      <h3>Superintendência de Saúde Digital</h3>

      <h1>Declaração</h1>
    </header>
    <section class="text-body">
      <p>
        Declaramos que <strong>{{ mb_strtoupper($event->participants[0]->name, 'utf8') }}</strong> participou da Web Aula <strong>{{ strtoupper($event->name) }}</strong> realizada no dia <strong>{{ $event->start_at_formatted }}</strong>, com carga horária de {{ $event->workload_formatted }}.
      </p>

      <p class="event-date">Campo Grande, <strong>{{ now()->format('d/m/Y') }}</strong></p>
    </section>
    <section class="signature">
      <table>
        <tbody>
          <tr>
            <td>
              <img src="{{ $coordinatorSignature }}" alt="esplogo">
            </td>
          </tr>

          <tr>
            <td>
            <!-- Signature Rule -->
            @if (\Carbon\Carbon::parse($event->end_at)->lt(\Carbon\Carbon::parse('2024-11-27')))
              <strong>Marcia Bogena Cereser Tomasi</strong>
            @else
              <strong>Rosângela Rodrigues Dobbro</strong>
            @endif
            </td>
          </tr>

          <tr>
            {{-- <td>
              Diretor-Geral de Gestão do Trabalho e Educação na Saúde
            </td> --}}

            <td>
            @if (\Carbon\Carbon::parse($event->start_at)->lt(\Carbon\Carbon::parse('2024-11-27')))
              <span>Superintendente de Saúde Digital</span>
            @else
              <span>Coordenadora de Telessaúde</span>
            @endif
            </td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>
</body>
</html>