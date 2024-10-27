<?php

namespace Jacekchmera\SportradarLibrary;

use DateTimeImmutable;
use Jacekchmera\SportradarLibrary\Exceptions\DuplicateTeamNameException;
use Jacekchmera\SportradarLibrary\Exceptions\GameAlreadyExistsException;
use Jacekchmera\SportradarLibrary\Exceptions\GameNotFoundException;

class Scoreboard implements ScoreboardInterface
{
    public function __construct(private array $games = [])
    {
    }

    /**
     * @throws GameAlreadyExistsException
     * @throws DuplicateTeamNameException
     */
    public function startGame(
        TeamInterface     $homeTeam,
        TeamInterface     $awayTeam,
        int               $homeScore = 0,
        int               $awayScore = 0,
        DateTimeImmutable $startTime = new DateTimeImmutable('now')
    ): Game
    {
        if (null !== $this->findGameByTeams($homeTeam, $awayTeam)) {
            throw new GameAlreadyExistsException('Game already exists');
        }

        $game = new Game(
            homeTeam: $homeTeam,
            awayTeam: $awayTeam,
            homeScore: $homeScore,
            awayScore: $awayScore,
            startTime: $startTime,
        );
        $game->startGame();

        // For the performance reasons, we are using the array key to store the game.
        $this->games[$homeTeam->getName() . '-' . $awayTeam->getName()] = $game;

        return $game;
    }

    /**
     * @throws GameNotFoundException
     */
    public function updateScore(TeamInterface $homeTeam, TeamInterface $awayTeam, int $homeScore, int $awayScore): void
    {
        $game = $this->findGameByTeams($homeTeam, $awayTeam);

        if (null === $game) {
            throw new GameNotFoundException('Game not found');
        }

        $game->setHomeTeamScore($homeScore);
        $game->setAwayTeamScore($awayScore);
    }

    private function findGameByTeams(TeamInterface $homeTeam, TeamInterface $awayTeam): ?Game
    {
        // For the performance reasons, we are using the array key to find the game.
        return $this->games[$homeTeam->getName() . '-' . $awayTeam->getName()] ?? null;
    }

    /**
     * @throws GameNotFoundException
     */
    public function finishGame(TeamInterface $homeTeam, TeamInterface $awayTeam): void
    {
        $game = $this->findGameByTeams($homeTeam, $awayTeam);

        if (null === $game) {
            throw new GameNotFoundException('Game not found');
        }

        if ($game->isGameInProgress()) {
            $game->finishGame();
            $this->deleteGame($game);
        }
    }

    private function deleteGame(Game $game): void
    {
        $this->games = array_filter($this->games, static function (Game $scoreboardGames) use ($game) {
            return $scoreboardGames !== $game;
        });
    }

    public function getSummary(): array
    {
        $matches = $this->games;
        usort($matches, static function(GameScoreManagementInterface $a, GameScoreManagementInterface $b) {
            // Compare total_score (descending).
            if ($a->getTotalScore() === $b->getTotalScore()) {
                // if total_score is the same, sort by start_time (descending).
                return $a->getStartTime()->getTimestamp() < $b->getStartTime()->getTimestamp() ? 1 : -1;
            }
            return $b->getTotalScore() - $a->getTotalScore();
        });

        return $matches;
    }

    /**
     * Helper for unit testing.
     *
     * @param bool $includeFinished Should finished matches be included.
     *
     * @return Game[] Matches (object of typu Game).
     */
    public function getGames(bool $includeFinished = false): array
    {
        return $includeFinished ? $this->games : array_filter($this->games, fn(Game $game) => $game->isGameInProgress());
    }
}
