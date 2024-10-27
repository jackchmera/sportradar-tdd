<?php

namespace Jacekchmera\SportradarLibrary;

/**
 * Defines a basic structure for a team.
 */
interface TeamInterface
{
    /**
     * Gets the name of the team.
     *
     * @return string The name of the team.
     */
    public function getName(): string;
}
