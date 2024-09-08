<?php
declare(strict_types=1);

namespace Tests\TraitsHelpers;

use Src\Teams\Strengths;
use Src\Teams\Team;

trait CreateArrayTeamListFromNamesList {
    /**
     * @param list<non-empty-string> $teamNames
     * @return list<Team>
     */
    private function createTeamListFromNamesList(array $teamNames): array
    {
        $teamList = [];
        $zeroStrengths = new Strengths();
        foreach ($teamNames as $name) {
            $teamList[] = new Team($name, $zeroStrengths);
        }

        return $teamList;
    }
}