<?php

declare(strict_types=1);

namespace App;

use App\Render\HtmlTableRenderer;

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
