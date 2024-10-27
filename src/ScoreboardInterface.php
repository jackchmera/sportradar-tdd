<?php

namespace Jacekchmera\SportradarLibrary;

use DateTimeImmutable;
use Jacekchmera\SportradarLibrary\Exceptions\GameNotFoundException;

/**
 * Defines methods for managing and tracking games on a scoreboard, including starting games,
 * updating scores, finishing games, and retrieving game summaries.
 */
interface ScoreboardInterface
{
    /**
     * Starts a new game and adds it to the scoreboard.
     *
     * @param TeamInterface $homeTeam The home team for the game.
     * @param TeamInterface $awayTeam The away team for the game.
     * @param int $homeScore Initial score for the home team, expected to be a non-negative integer.
     * @param int $awayScore Initial score for the away team, expected to be a non-negative integer.
     * @param DateTimeImmutable $startTime The starting time of the game.
     *
     * @return Game The game instance added to the scoreboard.
     */
    public function startGame(
        TeamInterface $homeTeam,
        TeamInterface $awayTeam,
        int $homeScore,
        int $awayScore,
        DateTimeImmutable $startTime,
    ): Game;

    /**
     * Updates the score of an ongoing game on the scoreboard.
     *
     * @param TeamInterface $homeTeam The home team for the game.
     * @param TeamInterface $awayTeam The away team for the game.
     * @param int $homeScore The new score for the home team, expected to be a non-negative integer.
     * @param int $awayScore The new score for the away team, expected to be a non-negative integer.
     *
     * @throws GameNotFoundException If the game cannot be found.
     */
    public function updateScore(TeamInterface $homeTeam, TeamInterface $awayTeam, int $homeScore, int $awayScore): void;

    /**
     * Marks a game as finished on the scoreboard.
     *
     * @param TeamInterface $homeTeam The home team for the game.
     * @param TeamInterface $awayTeam The away team for the game.
     *
     * @throws GameNotFoundException If the game cannot be found.
     */
    public function finishGame(TeamInterface $homeTeam, TeamInterface $awayTeam): void;

    /**
     * Retrieves all games from the scoreboard.
     *
     * @param bool $includeFinished Whether to include finished games in the result.
     *
     * @return Game[] An array of games currently on the scoreboard.
     */
    public function getGames(bool $includeFinished = false): array;

    /**
     * Retrieves a summary of all games on the scoreboard.
     *
     * @return Game[] A summary of the games, including scores and statuses.
     */
    public function getSummary(): array;
}
