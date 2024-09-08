<?php
declare(strict_types=1);

namespace Src\Tournaments\LeaderBoard;

use Src\Matches\MatchResult;
use Src\Tournaments\TeamList;

/**
 * This class contains the basic functionality for maintaining the standings of team games
 */
class Table
{
    private readonly TeamList $teamList;
    /** @var list<Statistic> */
    private array $statistics;

    public function __construct(TeamList $teamList)
    {
        $this->teamList = $teamList;
        $this->statistics = [];
        for ($i = 0; $i < count($teamList); $i++) {
            $this->statistics[] = new Statistic(0);
        }
    }

    /**
     * @return list<array{non-empty-string, Statistic}>
     */
    public function getResults(): array
    {
        $result = [];
        for ($i = 0; $i < count($this->teamList); $i++) {
            $name = $this->teamList[$i]->getName();
            $result[$i] = [$name, $this->statistics[$i]];
        }
        usort($result,
                static function (array $a, array $b): int {
                    /** @psalm-suppress MixedPropertyFetch */
                    $compare = $b[1]->points <=> $a[1]->points;
                    if ($compare === 0) {
                        /** @psalm-suppress MixedPropertyFetch */
                        $compare = $b[1]->goalsDifference <=> $a[1]->goalsDifference;
                    }

                    return $compare;
                }
        );

        return $result;
    }

    public function addMatch(MatchResult $match): void
    {
        $matchPair = $match->matchPair;
        $goals = $match->goals;
        $this->addTeamGoalsToStatistic($matchPair->tNum1 - 1, $goals[0], $goals[1]);
        $this->addTeamGoalsToStatistic($matchPair->tNum2 - 1, $goals[1], $goals[0]);
    }

    private function addTeamGoalsToStatistic(int $teamInd, int $teamGoal, int $opponentGoal): void
    {
        /** @var int<0,3> $points */
        $stat = $this->statistics[$teamInd];
        $points = 0;
        $goalsDifference = $teamGoal - $opponentGoal;
        if ($goalsDifference > 0) {
            $points = 3;
            $stat->wonGames += 1;
        } elseif ($goalsDifference === 0) {
            $points = 1;
            $stat->drawnGames += 1;
        } else {
            $stat->lostGames += 1;
        }

        $stat->points += $points;
        $stat->playedGames += 1;

        $stat->goalsFor += $teamGoal;
        $stat->goalsAgainst += $opponentGoal;
        $stat->goalsDifference += $goalsDifference;
    }
}