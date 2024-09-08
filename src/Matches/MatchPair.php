<?php
declare(strict_types=1);

namespace Src\Matches;

use Src\Teams\Team;
use Src\Tournaments\TeamList;

/**
 * This DTO class contains information about 2 teams that are to play together or have already played
 */
readonly class MatchPair
{
    public Team $team1;
    public int $tNum1;
    public Team $team2;
    public int $tNum2;

    public function __construct(
        Team $team1,
        int  $tNum1,
        Team $team2,
        int  $tNum2
    )
    {
        if ($tNum1 === $tNum2) {
            throw new \InvalidArgumentException('The two teams can\'t have the same number');
        } elseif ($team1 === $team2) {
            throw new \InvalidArgumentException('It should be different teams');
        }

        if ($tNum1 < $tNum2) {
            $firstTeam = [$team1, $tNum1];
            $secondTeam = [$team2, $tNum2];
        } else {
            $firstTeam = [$team2, $tNum2];
            $secondTeam = [$team1, $tNum1];
        }

        list($this->team1, $this->tNum1) = $firstTeam;
        list($this->team2, $this->tNum2) = $secondTeam;
    }

    /**
     * @param non-negative-int $idx1
     * @param non-negative-int $idx2
     */
    static public function createFromTeamList(TeamList $teamList, int $idx1, int $idx2): MatchPair {
        return new self($teamList[$idx1], $idx1 + 1, $teamList[$idx2], $idx2 + 1);
    }

    public function toArray(): array
    {
        return [
            [$this->team1->getName() => $this->tNum1],
            [$this->team2->getName() => $this->tNum2]
        ];
    }
}