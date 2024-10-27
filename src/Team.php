<?php

namespace Jacekchmera\SportradarLibrary;

use InvalidArgumentException;

/**
 * Represents a sports team with a specific name.
 */
class Team implements TeamInterface
{
    /**
     * Creates a new team.
     *
     * @param string $teamName The name of the team. Must be non-empty and trimmed.
     *
     * @throws InvalidArgumentException If the team name is empty.
     */
    public function __construct(private readonly string $teamName)
    {
        if (empty(trim($teamName))) {
            throw new InvalidArgumentException('The team name cannot be empty.');
        }
    }

    public function getName(): string
    {
        return $this->teamName;
    }
}
