<?php

namespace Tests\Tournaments\LeaderBoard;

use Src\Matches\MatchPair;
use Src\Matches\MatchResult;
use Src\Tournaments\LeaderBoard\Table;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Src\Tournaments\TeamList;
use Tests\TraitsHelpers\CreateArrayTeamListFromNamesList;

#[CoversClass(Table::class)]
class TableTest extends TestCase
{
    use CreateArrayTeamListFromNamesList;

    public function testTableTeamOrderIsCorrect(): void
    {
        /** @var non-empty-list<non-empty-string> $teamNames */
        $teamNames = [
            'Chelsea',         // 0
            'Manchester City', // 1
            'Arsenal',         // 2
            'Liverpool'        // 3
        ];
        /** @psalm-suppress InvalidArgument */
        $teamList = new TeamList($this->createTeamListFromNamesList($teamNames));

        $leaderBoard = new Table($teamList);
        $leaderBoard->addMatch($this->createMatchResult($teamList, 0, 1, 1,0));
        $leaderBoard->addMatch($this->createMatchResult($teamList, 2, 3, 4,1));
        $board = $leaderBoard->getResults();

        $this->assertCount(count($teamNames), $board);
        // check teams order
        $this->assertEquals(
            [$teamNames[2], $teamNames[0], $teamNames[1], $teamNames[3]],
            array_map(static fn($val) => $val[0], $board)
        );
    }

    /**
     * @param non-negative-int $idx1
     * @param non-negative-int $idx2
     * @param non-negative-int $goal1
     * @param non-negative-int $goal2
     */
    private function createMatchResult(TeamList $teamList, int $idx1, int $idx2, int $goal1, int $goal2): MatchResult
    {
        return new MatchResult(MatchPair::createFromTeamList($teamList, $idx1, $idx2), [$goal1, $goal2]);
    }
}
