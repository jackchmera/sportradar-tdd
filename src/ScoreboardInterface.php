<?php

interface ScoreboardInterface
{
    public function startMatch(): void;
    public function updateScore(): void;
    public function finishMatch(): void;
    public function getScore(): int;
}
