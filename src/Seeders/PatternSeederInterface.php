<?php

declare(strict_types=1);

namespace App\Seeders;

use App\Board;

interface PatternSeederInterface
{
    public function seed(Board $board, int $originRow, int $originCol): void;
}
