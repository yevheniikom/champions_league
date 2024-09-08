<?php
declare(strict_types=1);

namespace Src\Tournaments\MatchPairGenerator;

use Src\Matches\MatchPair;
use Src\Tournaments\TeamList;

/**
 * This class deals with determining which teams should play in a particular round of the tournament.
 * This implementation is the simplest and supports only 4 teams.
 */
readonly final class SimpleMatchPairGeneratorTeam4 implements MatchPairGeneratorInterface
{

    public function __construct(
        private TeamList $teamList,
    ) {}

    /**
     * Returns a list of team pairs that have to play each other in this round
     *
     * @param non-negative-int $actual
     *
     * @return non-empty-list<MatchPair>
     */
    public function getRoundMatchPairs(int $actual): array
    {
        $availableTeamByKey = [1 => true, 2 => true, 3 => true];

        $actual = $actual % 3 + 1;
        $match1 = $this->createMatchPairByTeamIndex(0, $actual);

        unset($availableTeamByKey[$actual]);
        $availableTeam = array_keys($availableTeamByKey);
        $match2 = $this->createMatchPairByTeamIndex($availableTeam[0], $availableTeam[1]);

        return [$match1, $match2];
    }

    /**
     * @param int<0,3> $index1
     * @param int<0,3> $index2
     */
    private function createMatchPairByTeamIndex(int $index1, int $index2): MatchPair
    {
        return MatchPair::createFromTeamList($this->teamList, $index1, $index2);
    }
}