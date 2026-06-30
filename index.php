<?php

declare(strict_types=1);

use App\Board;
use App\ConwayRuleEngine;
use App\GameOfLifeSimulation;
use App\Render\HtmlTableRenderer;
use App\Seeders\GliderSeeder;

require __DIR__ . '/vendor/autoload.php';

/** @var array{rows: int, cols: int, generations: int} $config */
$config = require __DIR__ . '/config/config.php';

$board = new Board($config['rows'], $config['cols']);

(new GliderSeeder())->seed(
    $board,
    intdiv($config['rows'], 2),
    intdiv($config['cols'], 2)
);

$simulation = new GameOfLifeSimulation(
    new ConwayRuleEngine(),
    new HtmlTableRenderer()
);

$boardSize = "{$config['rows']}x{$config['cols']}";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($boardSize, ENT_QUOTES, 'UTF-8') ?> Game of Life</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1><?= htmlspecialchars($boardSize, ENT_QUOTES, 'UTF-8') ?> Game of Life</h1>
    <?php $simulation->run($board, $config['generations']); ?>
</body>
</html>
