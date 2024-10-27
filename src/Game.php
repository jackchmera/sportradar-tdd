<?php

namespace Jacekchmera\SportradarLibrary;

use DateTimeImmutable;
use InvalidArgumentException;
use Jacekchmera\SportradarLibrary\Exceptions\DuplicateTeamNameException;
use LogicException;

/**
 * Class Game.
 *
 * Represents a game between two teams.
 */
class Game implements GameInterface, GameScoreManagementInterface, GameStateInterface
{
    /**
     * Game constructor.
     *
     * @param TeamInterface $homeTeam Home team.
     * @param TeamInterface $awayTeam Away team.
     * @param int $homeScore Home team score (default 0).
     * @param int $awayScore Away team score (default 0).
     * @param bool $inProgress Is the game in progress (default true).
     * @param DateTimeImmutable|null $startTime Game start time (default now).
     *
     * @throws DuplicateTeamNameException If both teams have the same name.
     */
    public function __construct(
        private readonly TeamInterface $homeTeam,
        private readonly TeamInterface $awayTeam,
        private int                    $homeScore = 0,
        private int                    $awayScore = 0,
        private bool                   $inProgress = false,
        private ?DateTimeImmutable     $startTime = null,
    )
    {
        if ($homeTeam->getName() === $awayTeam->getName()) {
            throw new DuplicateTeamNameException('The home and away teams must be different.');
        }

        $this->validateScore($homeScore);
        $this->validateScore($awayScore);

        $this->startTime = $startTime ?? new DateTimeImmutable('now');
    }

    public function setHomeTeamScore(int $homeScore): void
    {
        $this->validateScore($homeScore);
        $this->homeScore = $homeScore;
    }

    public function getHomeTeamScore(): int
    {
        return $this->homeScore;
    }

    public function setAwayTeamScore(int $awayScore): void
    {
        $this->validateScore($awayScore);

        $this->awayScore = $awayScore;
    }

    public function getAwayTeamScore(): int
    {
        return $this->awayScore;
    }

    public function getHomeTeam(): TeamInterface
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): TeamInterface
    {
        return $this->awayTeam;
    }

    public function isGameInProgress(): bool
    {
        return $this->inProgress;
    }

    public function startGame(): void
    {
        if ($this->inProgress) {
            throw new LogicException('Game is already in progress.');
        }

        $this->inProgress = true;
    }

    public function finishGame(): void
    {
        if (!$this->inProgress) {
            throw new LogicException('Game is not in progress.');
        }

        $this->inProgress = false;
    }

    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getTotalScore(): int
    {
        return $this->getHomeTeamScore() + $this->getAwayTeamScore();
    }

    /* @throws InvalidArgumentException */
    private function validateScore(int $score): void
    {
        if ($score < 0) {
            throw new InvalidArgumentException('The score must be a non-negative integer.');
        }
    }
}
