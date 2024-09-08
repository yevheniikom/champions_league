<?php
declare(strict_types=1);

namespace Src;

use Src\Matches\MatchEngine\RandomEngine;
use Src\Teams\Strengths;
use Src\Teams\Team;
use Src\Tournaments\MatchPairGenerator\SimpleMatchPairGeneratorTeam4;
use Src\Tournaments\Storage;
use Src\Tournaments\TeamList;
use Src\Tournaments\Tournament;

/**
 * Class for initializing the premier league, according to the conditions of the test project
 */
final class PremierLeagueSimulation
{

    private function __construct() {}

    /**
     * @param non-empty-string $fileName
     */
    static public function getStorage(string $fileName): Storage
    {
        return new Storage(sys_get_temp_dir() . '/' . $fileName . '_v1.save');
    }

    static function createTournament4Team(): Tournament
    {
        $teamNames = [
            'Chelsea',
            'Manchester City',
            'Arsenal',
            'Liverpool'
        ];
        $teamList = [];
        $zeroStrengths = new Strengths();
        foreach ($teamNames as $name) {
            $teamList[] = new Team($name, $zeroStrengths);
        }
        $teamList = new TeamList($teamList);

        return new Tournament(
            new RandomEngine(),
            new SimpleMatchPairGeneratorTeam4($teamList),
            $teamList,
            2
        );
    }
}