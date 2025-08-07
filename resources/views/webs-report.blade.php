<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Relatório de web aulas</title>
	<style>
		@page {
			margin: .5cm .5cm;
		}

		body {
			font-family: Arial, Helvetica, sans-serif;
		}

		main {
			margin: 40px;
			margin: 0 auto;
		}

		.logo-tele {
			float: left;
		}

		header {
			text-align: center;
		}

		header>h3 {
			font-size: 22px;
			margin: 5;
		}

		header>h2 {
			margin-top: 100px;
		}

		.text-body {
			font-size: 22px;
			margin-top: 30px;
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

		table {
			border-collapse: collapse;
			width: 100%;
			font-family: Arial, sans-serif;
			font-size: 14px;
		}

		th,
		td {
			border: 1px solid #ddd;
			padding: 4px;
			text-align: left;
		}

		th {
			background-color: #f8f9fa;
			font-weight: bold;
		}

		tr:nth-child(even) {
			background-color: #f8f9fa;
		}
	</style>
</head>

<body>
	<main>
		<header>
			<div>
				<img class="logo-tele" src="{{ $logoTSMS }}" width="70px">
				<img class="logo-ses" src="{{ $logoSES }}" width="200px" />
			</div>

			<h2>Relatório de web aulas</h2>
		</header>
		<section class="text-body">
			<table>
				<thead>
					<tr>
						<th>Tema</th>
						<th>Início</th>
						<th>Fim</th>
						<th>Organização</th>
						<th>Desc. Bireme</th>
						<th>Participante</th>
						<th>Data Participação</th>
						<th>UF</th>
						<th>Cidade</th>
						<th>Macro Região</th>
						<th>Micro Região</th>
					</tr>
				</thead>
				<tbody>
					@foreach($eventList as $event)
					<tr>
						<td>{{ $event->name }}</td>
						<td>{{ $event->start_at_datetime_formatted }}</td>
						<td>{{ $event->end_at_datetime_formatted }}</td>
						<td>{{ $event->organization }}</td>
						<td>{{ $event->descs }}</td>
						<td>{{ $event->participant }}</td>
						<td>{{ $event->signed_up_at }}</td>
						<td>{{ $event->state }}</td>
						<td>{{ $event->city }}</td>
						<td>{{ $event->macro_zone }}</td>
						<td>{{ $event->micro_zone }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</section>
	</main>

	<script type="text/php">
		if (isset($pdf)) {
			$font = $fontMetrics->get_font("Arial", "normal");
			$size = 10;
			$date = date('d/m/Y H:i');

			// Posição Y (altura) e X (horizontal)
			$y = $pdf->get_height() - 30;
			$x = $pdf->get_width() / 2 - 100;

			$pdf->page_text($x, $y, "Pág. {PAGE_NUM} | Impresso em " . $date, $font, $size);
		}
	</script>
</body>
</html>