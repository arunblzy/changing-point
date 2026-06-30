<?php

declare(strict_types=1);

namespace App;

use OutOfRangeException;

class Board
{
    private array $cells;

    public function __construct(
        private int $rows,
        private int $cols
    ) {
        $this->cells = array_fill(0, $rows, array_fill(0, $cols, false));
    }

    public function getRows(): int
    {
        return $this->rows;
    }

    public function getCols(): int
    {
        return $this->cols;
    }

    public function createEmptyCopy(): self
    {
        return new self($this->rows, $this->cols);
    }

    public function isInBounds(int $row, int $col): bool
    {
        return $row >= 0 && $row < $this->rows && $col >= 0 && $col < $this->cols;
    }

    public function isAlive(int $row, int $col): bool
    {
        if (!$this->isInBounds($row, $col)) {
            return false;
        }

        return $this->cells[$row][$col];
    }

    public function setAlive(int $row, int $col, bool $alive = true): void
    {
        if (!$this->isInBounds($row, $col)) {
            throw new OutOfRangeException("Cell ({$row}, {$col}) is outside the {$this->rows}x{$this->cols} board.");
        }

        $this->cells[$row][$col] = $alive;
    }
}
