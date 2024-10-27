<?php

namespace Unit;

use InvalidArgumentException;
use Jacekchmera\SportradarLibrary\Team;
use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    public function testGetName(): void
    {
        $team = new Team('Team A');
        $this->assertEquals('Team A', $team->getName());
    }

    public function testEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Team('');
    }
}
