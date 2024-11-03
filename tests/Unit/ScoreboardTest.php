<?php

namespace Unit;

use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;
use Jacekchmera\SportradarLibrary\Exceptions\DuplicateTeamNameException;
use Jacekchmera\SportradarLibrary\Exceptions\GameAlreadyExistsException;
use Jacekchmera\SportradarLibrary\Exceptions\GameNotFoundException;
use PHPUnit\Framework\TestCase;
use Jacekchmera\SportradarLibrary\Scoreboard;
use Jacekchmera\SportradarLibrary\Team;
use Jacekchmera\SportradarLibrary\Game;

class ScoreboardTest extends TestCase
{
    private Scoreboard $scoreboard;
    private Team $homeTeam;
    private Team $awayTeam;
    private Team $homeTeamSecond;
    private Team $awayTeamSecond;

    public function setUp(): void
    {
        error_reporting(E_ALL | E_DEPRECATED);
        $this->scoreboard = new Scoreboard();
        $this->homeTeam = new Team('Team A');
        $this->awayTeam = new Team('Team B');

        $this->homeTeamSecond = new Team('Team C');
        $this->awayTeamSecond = new Team('Team D');
    }

    /**
     * @throws DuplicateTeamNameException
     * @throws GameAlreadyExistsException
     */
    public function testStartGame(): void
    {
        $this->scoreboard->startGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam);
        $this->assertCount(1, $this->scoreboard->getGames());

        $gameKey = $this->homeTeam->getName() . '-' . $this->awayTeam->getName();
        $this->assertInstanceOf(Game::class, $this->scoreboard->getGames()[$gameKey]);
        $this->assertEquals('Team A', $this->scoreboard->getGames()[$gameKey]->getHomeTeam()->getName());
        $this->assertEquals('Team B', $this->scoreboard->getGames()[$gameKey]->getAwayTeam()->getName());
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testStartSameGameAgain()
    {
        $this->expectException(GameAlreadyExistsException::class);
        $this->scoreboard->startGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam);
        $this->scoreboard->startGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam);
    }

    /**
     * @throws DuplicateTeamNameException
     * @throws GameAlreadyExistsException
     */
    public function testStartGameWithSameTeamNames(): void
    {
        $this->expectException(DuplicateTeamNameException::class);
        $this->scoreboard->startGame(homeTeam: $this->homeTeam, awayTeam: $this->homeTeam);
    }

    /**
     * @throws DuplicateTeamNameException
     * @throws GameAlreadyExistsException
     */
    public function testStartGameWithNegativeScore(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->scoreboard->startGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam, homeScore:-1);
    }

    /**
     * @throws DuplicateTeamNameException
     * @throws GameNotFoundException
     * @throws GameAlreadyExistsException
     */
    public function testUpdateScore(): void
    {
        $this->scoreboard->startGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam);
        $this->scoreboard->updateScore(
            homeTeam: $this->homeTeam,
            awayTeam: $this->awayTeam,
            homeScore: 0,
            awayScore: 1,
        );

        $gameKey = $this->homeTeam->getName() . '-' . $this->awayTeam->getName();
        $this->assertEquals(0, $this->scoreboard->getGames()[$gameKey]->getHomeTeamScore());
        $this->assertEquals(1, $this->scoreboard->getGames()[$gameKey]->getAwayTeamScore());
    }

    /**
     * @throws GameNotFoundException
     * @throws DuplicateTeamNameException
     * @throws GameAlreadyExistsException
     */
    public function testFinishGame():void
    {
        $this->scoreboard->startGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam);
        $this->assertCount(1, $this->scoreboard->getGames());

        $this->scoreboard->finishGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam);
        $this->assertCount(0, $this->scoreboard->getGames());

        $this->expectException(GameNotFoundException::class);
        $this->scoreboard->finishGame(homeTeam: $this->homeTeamSecond, awayTeam: $this->awayTeamSecond);
    }

    /**
     * @throws GameNotFoundException
     * @throws DuplicateTeamNameException
     * @throws GameAlreadyExistsException
     */
    public function testGetSummary(): void
    {
        $startTime = new DateTimeImmutable('now');

        $game1 = $this->createAndStartGame('Mexico', 'Canada', $startTime->add(new DateInterval('PT1S')), 0, 5);
        $game2 = $this->createAndStartGame('Spain', 'Brazil', $startTime->add(new DateInterval('PT2S')), 10, 2);
        $game3 = $this->createAndStartGame('Germany', 'France', $startTime->add(new DateInterval('PT3S')), 2, 2);
        $game4 = $this->createAndStartGame('Uruguay', 'Italy', $startTime->add(new DateInterval('PT4S')), 6, 6);
        $game5 = $this->createAndStartGame('Argentina', 'Australia', $startTime->add(new DateInterval('PT4S')), 3, 1);

        // The game order should be sorted by the total score in descending order.
        // The matches with the same total score will be returned ordered by the most recently started match
        // in the scoreboard.
        $this->assertEquals(
            [
                $game4, // Uruguay vs Italy
                $game2, // Spain vs Brazil
                $game1, // Mexico vs Canad
                $game5, // Argentina vs Australia
                $game3, // Germany vs France
            ],
            $this->scoreboard->getSummary()
        );
    }

    /**
     * @throws DuplicateTeamNameException
     * @throws GameNotFoundException
     * @throws GameAlreadyExistsException
     */
    private function createAndStartGame(
        string $homeTeamName,
        string $awayTeamName,
        DateTimeImmutable $startTime,
        int $homeScore,
        int $awayScore
    ): Game {
        $homeTeam = new Team($homeTeamName);
        $awayTeam = new Team($awayTeamName);

        $game = $this->scoreboard->startGame(homeTeam: $homeTeam, awayTeam: $awayTeam, startTime: $startTime);
        
        $this->scoreboard->updateScore(
            homeTeam: $homeTeam,
            awayTeam: $awayTeam,
            homeScore: $homeScore,
            awayScore: $awayScore
        );

        return $game;
    }

    /**
     * @throws GameNotFoundException
     * @throws DuplicateTeamNameException
     * @throws GameAlreadyExistsException
     */
    public function testGetGames():void
    {
        $this->scoreboard->startGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam);
        $this->assertCount(1, $this->scoreboard->getGames());

        $this->scoreboard->startGame(homeTeam: $this->homeTeamSecond, awayTeam: $this->awayTeamSecond);
        $this->assertCount(2, $this->scoreboard->getGames());

        $this->scoreboard->finishGame(homeTeam: $this->homeTeam, awayTeam: $this->awayTeam);
        $this->assertCount(1, $this->scoreboard->getGames());
    }
}
