<?php

declare(strict_types=1);

namespace App\Render;

use App\Board;

class HtmlTableRenderer
{
    private const ALIVE_SYMBOL = '&#9679;';
    private const DEAD_SYMBOL = '&#9675;';

    public function render(Board $board, int $generationNumber): void
    {
        echo "<h2>Generation {$generationNumber}</h2>" . PHP_EOL;
        echo '<table>' . PHP_EOL;

        $rows = $board->getRows();
        $cols = $board->getCols();

        for ($row = 0; $row < $rows; $row++) {
            echo '<tr>';

            for ($col = 0; $col < $cols; $col++) {
                echo $this->renderCell($board->isAlive($row, $col), $row, $col);
            }

            echo '</tr>' . PHP_EOL;
        }

        echo '</table>' . PHP_EOL;
    }

    private function renderCell(bool $isAlive, int $row, int $col): string
    {
        $cssClass = $isAlive ? 'alive' : 'dead';
        $symbol = $isAlive ? self::ALIVE_SYMBOL : self::DEAD_SYMBOL;
        $rowLabel = $row + 1;
        $colLabel = $col + 1;

        return "<td class=\"{$cssClass}\">"
            . "<span class=\"coord\">R{$rowLabel} C{$colLabel}</span>"
            . "<span class=\"state\">{$symbol}</span>"
            . '</td>';
    }
}
