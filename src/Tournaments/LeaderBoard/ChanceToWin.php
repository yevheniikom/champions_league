<?php
declare(strict_types=1);

namespace Src\Tournaments\LeaderBoard;

/**
 * The class is responsible for calculating the table with the chances of winning the tournament
 */
readonly class ChanceToWin
{

    private Table $finaleTable;

    public function __construct(Table $finalTable)
    {
        $this->finaleTable = $finalTable;
    }

    /**
     * @param non-negative-int $actualRoundNum
     * @param non-negative-int $maxRoundNum
     * @return list<array{non-empty-string, float|int}>
     */
    public function getEstimatedTable(int $actualRoundNum, int $maxRoundNum): array
    {
        $leaderShip = $this->finaleTable->getResults();

        $totalPoints = 0;
        foreach ($leaderShip as $ind => $player) {
            $totalPoints += $this->calcLocalPoints($player[1], $ind);
        }

        $balance = ($maxRoundNum !== 0) ? $actualRoundNum / $maxRoundNum : 0;
        $baseChance = 1 / count($leaderShip) * (1 - $balance);

        $result = [];
        foreach ($leaderShip as $ind => $player) {
            $chance = $baseChance;
            if ($totalPoints > 0) {
                $chance += $this->calcLocalPoints($player[1], $ind) / $totalPoints * $balance;
            }
            $result[] = [$player[0], $chance];
        }

        return $result;
    }

    /**
     * @param non-negative-int $ind
     */
    private function calcLocalPoints(Statistic $stat, int $ind): float|int
    {
        return $stat->points / ($ind + 2);
    }

}