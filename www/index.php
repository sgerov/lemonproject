<?php
require_once __DIR__ . '/../vendor/autoload.php';

$lemon = new Lemon\Expert\Expert();
?>
<html>
<head>
<title>LemonProject</title>
</head>
<body>
<h1>Lemon bets</h1>
<hr>
<?
$arbs = $lemon->findArbitratge();
foreach ($arbs as $houseArbs) {
	foreach ($houseArbs as $houseArb) {
		echo 'Ganancia: ' . $houseArb["odd"] . "<br>Apuesta por:<br>";
		foreach ($houseArb["info"] as $house => $betOn) {
			echo "'$betOn' en <strong>'$house'</strong><br>";
		}
		echo '<br><hr><br>';
	}
}
?>
</body>
</html>
