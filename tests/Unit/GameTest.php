<?php

namespace Unit;

use DateTimeImmutable;
use InvalidArgumentException;
use Jacekchmera\SportradarLibrary\Exceptions\DuplicateTeamNameException;
use Jacekchmera\SportradarLibrary\Game;
use Jacekchmera\SportradarLibrary\Team;
use LogicException;
use PHPUnit\Framework\TestCase;

class GameTest  extends TestCase
{
    private Team $homeTeam;
    private Team $awayTeam;

    public function setUp(): void
    {
        $this->homeTeam = new Team('Team A');
        $this->awayTeam = new Team('Team B');
    }

    public function testCreateGameWithDuplicatedTeam(): void
    {
        $this->expectException(DuplicateTeamNameException::class);
        new Game($this->homeTeam, $this->homeTeam);
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testGetTotalScoreReturnsSumOfHomeAndAwayScores(): void
    {
        $game = new Game($this->homeTeam, $this->awayTeam, 3, 2);
        $this->assertEquals(5, $game->getTotalScore());
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testFinishGameSetsInProgressToFalse(): void
    {
        $game = new Game($this->homeTeam, $this->awayTeam);
        $game->startGame();
        $game->finishGame();

        $this->assertFalse($game->isGameInProgress());
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testFinishNotStartedGame(): void
    {
        $this->expectException(LogicException::class);
        $game = new Game($this->homeTeam, $this->awayTeam);
        $game->finishGame();

        $this->assertFalse($game->isGameInProgress());
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testStartGameSetsInProgressToTrue(): void
    {
        $game = new Game($this->homeTeam, $this->awayTeam);
        $game->startGame();

        $this->assertTrue($game->isGameInProgress());
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testGetStartTimeReturnsCorrectStartTime(): void
    {
        $startTime = new DateTimeImmutable('2023-10-01 10:00:00');
        $game = new Game($this->homeTeam, $this->awayTeam, 0, 0, true, $startTime);

        $this->assertEquals($startTime, $game->getStartTime());
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testCreateGameWithNegativeScore(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $game = new Game($this->homeTeam, $this->awayTeam, -1);
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testDefaultStartTimeIsNow(): void
    {
        $game = new Game($this->homeTeam, $this->awayTeam);
        $this->assertEqualsWithDelta((new DateTimeImmutable('now'))->getTimestamp(), $game->getStartTime()->getTimestamp(), 1);
    }

    /**
     * @throws DuplicateTeamNameException
     */
    public function testSetScoreUpdatesCorrectly(): void
    {
        $game = new Game($this->homeTeam, $this->awayTeam);
        $game->setHomeTeamScore(4);
        $game->setAwayTeamScore(3);

        $this->assertEquals(4, $game->getHomeTeamScore());
        $this->assertEquals(3, $game->getAwayTeamScore());
        $this->assertEquals(7, $game->getTotalScore());
    }
}
