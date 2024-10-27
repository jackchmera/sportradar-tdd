<?php

namespace Jacekchmera\SportradarLibrary;

use DateTimeImmutable;

/**
 * Interface defining the core data of a game.
 */
interface GameInterface
{
    /**
     * Gets the score for the home team.
     *
     * @return int The score for the home team, expected to be a non-negative integer.
     */
    public function getHomeTeamScore(): int;

    /**
     * Gets the score for the away team.
     *
     * @return int The score for the away team, expected to be a non-negative integer.
     */
    public function getAwayTeamScore(): int;

    /**
     * Gets the home team participating in the game.
     *
     * @return TeamInterface An instance representing the home team.
     */
    public function getHomeTeam(): TeamInterface;

    /**
     * Gets the away team participating in the game.
     *
     * @return TeamInterface An instance representing the away team.
     */
    public function getAwayTeam(): TeamInterface;

    /**
     * Gets the start time of the game.
     *
     * @return DateTimeImmutable The date and time when the game is started. By default, the game is started immediately.
     */
    public function getStartTime(): DateTimeImmutable;
}
