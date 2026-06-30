<?php

declare(strict_types=1);

// --- Configuration -----------------------------------------------------
const ROWS = 25;
const COLS = 25;
const GENERATIONS = 10;


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

interface PatternSeederInterface
{
    public function seed(Board $board, int $originRow, int $originCol): void;
}

class GliderSeeder implements PatternSeederInterface
{
    public function seed(Board $board, int $originRow, int $originCol): void
    {
        $relativeLiveCells = [
            [0, 1],[1, 2],[2, 0], [2, 1], [2, 2],
        ];

        foreach ($relativeLiveCells as [$dRow, $dCol]) {
            $board->setAlive($originRow + $dRow, $originCol + $dCol);
        }
    }
}


class ConwayRuleEngine
{
    public function nextGeneration(Board $current): Board
    {
        $next = new Board($current->getRows(), $current->getCols());

        for ($row = 0; $row < $current->getRows(); $row++) {
            for ($col = 0; $col < $current->getCols(); $col++) {
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
                    continue; // a cell is not its own neighbor
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
            // Rule 1 (underpopulation) and Rule 3 (overcrowding) kill
            // the cell; Rule 2 (survival) keeps it alive on 2 or 3.
            return $liveNeighbors === 2 || $liveNeighbors === 3;
        }

        // Rule 4 (reproduction): a dead cell with exactly 3 live
        // neighbors is born.
        return $liveNeighbors === 3;
    }
}


class HtmlTableRenderer
{
    private const ALIVE_SYMBOL = '&#9679;'; // ●
    private const DEAD_SYMBOL = '&#9675;';  //○

    public function render(Board $board, int $generationNumber): void
    {
        echo "<h2>Generation {$generationNumber}</h2>" . PHP_EOL;
        echo '<table>' . PHP_EOL;
        for ($row = 0; $row < $board->getRows(); $row++) {
            echo '<tr>';
            for ($col = 0; $col < $board->getCols(); $col++) {
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

        return "<td class='{$cssClass}'>"
            . "<span class='coord'>R{$rowLabel} C{$colLabel}</span>"
            . "<span class='state'>{$symbol}</span>"
            . '</td>';
    }
}

class GameOfLifeSimulation
{
    public function __construct(
        private ConwayRuleEngine $ruleEngine,
        private HtmlTableRenderer $renderer
    ) {
    }

    public function run(Board $initialBoard, int $generationCount): void
    {
        $board = $initialBoard;

        for ($generation = 0; $generation <= $generationCount; $generation++) {
            $this->renderer->render($board, $generation);

            if ($generation === $generationCount) {
                break;
            }

            $board = $this->ruleEngine->nextGeneration($board);
        }
    }
}



$board = new Board(ROWS, COLS);

$originRow = intdiv(ROWS, 2);
$originCol = intdiv(COLS, 2);
(new GliderSeeder())->seed($board, $originRow, $originCol);

$simulation = new GameOfLifeSimulation(
    new ConwayRuleEngine(),
    new HtmlTableRenderer()
);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Game of Life</title>
<style>
  body { font-family: Arial; background: #f4f4f4; margin: 20px; }
  table { border-collapse: collapse; margin: 30px 0; }
  td {
    width: 34px;
    height: 34px;
    border: 1px solid #aaa;
    text-align: center;
    vertical-align: middle;
  }
  .coord { font-size: 7px; display: block; line-height: 8px; }
  .state { font-size: 14px; font-weight: bold; }
  .alive { background: #8f8; }
  .dead { background: #fff; }
</style>
</head>
<body>

<h1>25x25 Game of Life</h1>

<?php $simulation->run($board, GENERATIONS); ?>

</body>
</html>