<?php

declare(strict_types=1);

namespace App\Seeders;

use App\Board;

class GliderSeeder implements PatternSeederInterface
{
    public function seed(Board $board, int $originRow, int $originCol): void
    {
        $relativeLiveCells = [
            [0, 1],
            [1, 2],
            [2, 0],
            [2, 1],
            [2, 2],
        ];

        foreach ($relativeLiveCells as [$dRow, $dCol]) {
            $board->setAlive($originRow + $dRow, $originCol + $dCol);
        }
    }
}
