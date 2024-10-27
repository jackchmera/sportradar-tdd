<?php

namespace Jacekchmera\SportradarLibrary;

/**
 * Interface defining methods for managing game score.
 */
interface GameScoreManagementInterface
{
    /**
     * Sets the score for the home team.
     *
     * @param int $homeScore The score for the home team. Expected to be a non-negative integer.
     */
    public function setHomeTeamScore(int $homeScore): void;

    /**
     * Sets the score for the away team.
     *
     * @param int $awayScore The score for the away team. Expected to be a non-negative integer.
     */
    public function setAwayTeamScore(int $awayScore): void;

    /**
     * Gets the total score of the game by summing the scores of both teams.
     *
     * @return int The combined score of the home and away teams.
     */
    public function getTotalScore(): int;
}
