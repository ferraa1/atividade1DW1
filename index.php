<!DOCTYPE html>
<?php
	$title = "ATIVIDADE - FUNÇÃO";
	$valores = isset($_POST['valores']) ? $_POST['valores'] : 1;
	$inicio = isset($_POST['inicio']) ? $_POST['inicio'] : 0;
	$fim = isset($_POST['fim']) ? $_POST['fim'] : 0;
	$escrever = isset($_POST['escrever']) ? $_POST['escrever'] : "json/valores.json";
	$ler_valores = isset($_POST['ler_valores']) ? $_POST['ler_valores'] : "json/valores.json";
	$ler_graficos = isset($_POST['ler_graficos']) ? $_POST['ler_graficos'] : "json/valores.json";
	#valores ultima atividade + mediana
	#google chart line chart html
?>
<html>
<head>
	<meta charset="UTF-8">
	<title><?=$title?></title>
</head>
<body>
	<h1><?=$title?></h1>
	<div><!--style="display: grid; grid-template-columns: 1fr 1fr 1fr;"-->
		<form method="post">
			<fieldset>
				<legend>ENTRADA</legend>
				<label for="escrever">ARQUIVO: </label>
				<input type="text" name="escrever" id="escrever" <?php echo "value=\"$escrever\"";?>>
				<br>
				<label for="valores">VALORES: </label>
				<input type="number" min="1" name="valores" id="valores" <?php echo "value=\"$valores\"";?>>
				<br>
				<label for="inicio">INÍCIO: </label>
				<input type="number" name="inicio" id="inicio" <?php echo "value=\"$inicio\"";?>>
				<br>
				<label for="fim">FIM: </label>
				<input type="number" name="fim" id="fim" <?php echo "value=\"$fim\"";?>>
				<br>
				<br>
				<input type="submit">
				<?php
					if (isset($_POST['escrever']) && isset($_POST['valores']) && isset($_POST['inicio']) && isset($_POST['fim'])) {
						$vetor = array();
						for ($i = 0; $i < $valores; $i++) {
							array_push($vetor, rand($inicio, $fim));
						}
						$fp = fopen($escrever, "w");
						fwrite($fp, json_encode($vetor));
						fclose($fp);
						echo "<p style=\"color: green;\">ARQUIVO ARMAZENADO!</p>";
					}
				?>
			</fieldset>
		</form>
		<form method="post">
			<fieldset>
				<legend>SAÍDA - VALORES</legend>
				<label for="ler_valores">ARQUIVO: </label>
				<input type="text" name="ler_valores" id="ler_valores" <?php echo "value=\"$ler_valores\"";?>>
				<br>
				<br>
				<input type="submit">
				<?php
					if (isset($_POST['ler_valores'])) {
						$vetor = array();
						foreach (json_decode(file_get_contents($ler_valores)) as $i) {
							array_push($vetor, $i);
						}
						if (count($vetor) > 0) {
							sort($vetor);
							$elementos_maior = $vetor[0];
							$elementos_menor = $vetor[0];
							$elementos_pares = array();
							$elementos_impares = array();
							$elementos_soma = 0;
							$elementos_acima_media = array();
							$elementos_abaixo_media = array();
							$elementos_primos = array();
							foreach ($vetor as $i) {
								if ($i > $elementos_maior) {
									$elementos_maior = $i;
								}
								if ($i < $elementos_menor) {
									$elementos_menor = $i;
								}
								if ($i % 2 == 0 && $i != 0) {
									array_push($elementos_pares, $i);
								} elseif ($i != 0) {
									array_push($elementos_impares, $i);
								}
								$elementos_soma += $i;
								if ($i > 1) {
									$divisiveis = array();
									for ($j = 1; $j <= $i; $j++) {
										if ($i % $j == 0) {
											array_push($divisiveis, $j);
										}
									}
									if ($divisiveis == [1, $i]) {
										array_push($elementos_primos, $i);
									}
								}
							}
							$elementos_media = $elementos_soma / count($vetor);
							foreach ($vetor as $i) {
								if ($i > $elementos_media) {
									array_push($elementos_acima_media, $i);
								} elseif ($i < $elementos_media) {
									array_push($elementos_abaixo_media, $i);
								}
							}
							if (count($vetor) % 2 == 0) {
								$i = intdiv(count($vetor), 2);
								$j = $i - 1;
								$elementos_mediana = ($vetor[$i] + $vetor[$j]) / 2;
							} else {
								$i = intdiv(count($vetor), 2);
								$elementos_mediana = $vetor[$i];
							}
							echo "<p>";
							echo "<span style=\"color: green;\">VALORES GERADOS!</span><br><br>";
							echo "Maior Elemento: ".$elementos_maior."<br>";
							echo "Menor Elemento: ".$elementos_menor."<br>";
							echo "Elementos Pares: ";
							foreach ($elementos_pares as $i) {
								echo $i." ";
							}
							echo "<br>";
							echo "Elementos Impares: ";
							foreach ($elementos_impares as $i) {
								echo $i." ";
							}
							echo "<br>";
							echo "Soma dos Elementos: ".$elementos_soma."<br>";
							echo "Media dos Elementos: ".$elementos_media."<br>";
							echo "Elementos Acima da Media: ";
							foreach ($elementos_acima_media as $i) {
								echo $i." ";
							}
							echo "<br>";
							echo "Elementos Abaixo da Media: ";
							foreach ($elementos_abaixo_media as $i) {
								echo $i." ";
							}
							echo "<br>";
							echo "Elementos Primos: ";
							foreach ($elementos_primos as $i) {
								echo $i." ";
							}
							echo "<br>";
							echo "Mediana: ".$elementos_mediana;
							echo "</p>";
						} else {
							echo "<p style=\"color: red;\">VETOR VAZIO</p>";
						}
					}
				?>
			</fieldset>
		</form>
		<form method="post">
			<fieldset>
				<legend>SAÍDA - GRÁFICO</legend>
				<label for="ler_graficos">ARQUIVO: </label>
				<input type="text" name="ler_graficos" id="ler_graficos" <?php echo "value=\"$ler_graficos\"";?>>
				<br>
				<br>
				<input type="submit">
				<?php
					if (isset($_POST['ler_graficos'])) {
						$vetor = array();
						foreach (json_decode(file_get_contents($ler_valores)) as $i) {
							array_push($vetor, $i);
						}
						if (count($vetor) > 0) {
							sort($vetor);
							$freq = array_count_values($vetor);
				?>
						<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
						<script type="text/javascript">
							google.charts.load('current', {'packages':['line']});
							google.charts.setOnLoadCallback(drawChart);

							function drawChart() {
								var data = new google.visualization.DataTable();
								data.addColumn('number', 'Valores');
								data.addColumn('number', 'Frequência');

								data.addRows([
									<?php
										foreach ($freq as $value => $count) {
											echo "[$value , $count],";
										}
									?>
								]);

								var options = {
									chart: {
									title: 'Gráfico',
									subtitle: 'frequência de valores'
									},
									width: 900,
									height: 500
								};

								var chart = new google.charts.Line(document.getElementById('linechart_material'));

								chart.draw(data, google.charts.Line.convertOptions(options));
							}
						</script>
				<?php
						echo "<p style=\"color: green;\">GRÁFICO GERADO!</p>";
						if (isset($_POST['ler_graficos'])) {
							echo "<div id=\"linechart_material\" style=\"width: 900px; height: 500px;\"></div>";
						}
						} else {
							echo "<p style=\"color: red;\">VETOR VAZIO</p>";
						}
					}
				?>
			</fieldset>
		</form>
	</div>
	<br>
	<a href="index.php">reset</a>
	<br>
	<br>
</body>
</html>