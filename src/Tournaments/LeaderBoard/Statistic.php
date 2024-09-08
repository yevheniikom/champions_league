<?php
declare(strict_types=1);

namespace Src\Tournaments\LeaderBoard;

/**
 * This DTO class contains all the information about the team's game statistics in the tournament
 */
class Statistic
{
    public function __construct(
        public int $points,
        public int $playedGames = 0,
        public int $wonGames = 0,
        public int $drawnGames = 0,
        public int $lostGames = 0,
        public int $goalsFor = 0,
        public int $goalsAgainst = 0,
        public int $goalsDifference = 0,
    ) {}

    public function toArray(): array
    {
        return (array)$this;
    }
}