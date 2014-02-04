<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Lemon\Lemon;

$lemon = new Lemon();
$lemon->getOdds(Array("Pinnacle"), "two");
?>
<html>
<head>
<title>LemonProject</title>
</head>
<body>
<h1>Lemon project</h1>
</body>
</html>
