<?php

namespace Jacekchmera\SportradarLibrary;

/**
 * Interface defining the lifecycle of the game.
 */
interface GameStateInterface
{
    /**
     * Starts the game.
     *
     * Implementations should ensure that the game state is updated to reflect that it has started.
     * This method is typically expected to be called only once during the lifecycle of a game.
     */
    public function startGame(): void;

    /**
     * Finishes the game.
     *
     * Implementations should ensure that the game state is updated to reflect that it has ended.
     * This method is typically expected to be called only once during the lifecycle of a game.
     */
    public function finishGame(): void;

    /**
     * Checks if the game is currently in progress.
     *
     * @return bool True if the game is in progress, otherwise false.
     * This method should return true only after `startGame` has been called and
     * before `finishGame` has been called.
     */
    public function isGameInProgress(): bool;
}
