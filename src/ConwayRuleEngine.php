<?php

declare(strict_types=1);

namespace App;

class ConwayRuleEngine
{
    public function nextGeneration(Board $current): Board
    {
        $next = $current->createEmptyCopy();

        $rows = $current->getRows();
        $cols = $current->getCols();

        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {
                $liveNeighbors = $this->countLiveNeighbors($current, $row, $col);
                $isAlive = $current->isAlive($row, $col);

                if ($this->willBeAlive($isAlive, $liveNeighbors)) {
                    $next->setAlive($row, $col);
                }
            }
        }

        return $next;
    }

    private function countLiveNeighbors(Board $board, int $row, int $col): int
    {
        $count = 0;

        for ($dRow = -1; $dRow <= 1; $dRow++) {
            for ($dCol = -1; $dCol <= 1; $dCol++) {
                if ($dRow === 0 && $dCol === 0) {
                    continue;
                }

                if ($board->isAlive($row + $dRow, $col + $dCol)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    private function willBeAlive(bool $isCurrentlyAlive, int $liveNeighbors): bool
    {
        if ($isCurrentlyAlive) {
            return $liveNeighbors === 2 || $liveNeighbors === 3;
        }

        return $liveNeighbors === 3;
    }
}
