<?php
declare(strict_types=1);

namespace Tests\Tournaments\MatchPairGenerator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Src\Matches\MatchPair;
use Src\Teams\Strengths;
use Src\Teams\Team;
use Src\Tournaments\MatchPairGenerator\SimpleMatchPairGeneratorTeam4;
use Src\Tournaments\TeamList;

#[CoversClass(SimpleMatchPairGeneratorTeam4::class)]
final class SimpleMatchPairGeneratorTeam4Test extends TestCase
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private TeamList $teamList;

    public function setUp(): void
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
        $this->teamList = new TeamList($teamList);
    }

    public function testMatchPairGeneratorCanDoSeveralCycle(): void
    {
        $generator = new SimpleMatchPairGeneratorTeam4($this->teamList);

        for ($i = 0; $i < 12; $i++) {
            $arr = $generator->getRoundMatchPairs($i + 1);
            /** @psalm-suppress RedundantCondition */
            $this->assertIsArray($arr);
        }
    }

    public function testMatchPairGeneratorCreateUniquePairs(): void
    {
        $generator = new SimpleMatchPairGeneratorTeam4($this->teamList);

        $cyclePairs = [];
        for ($i = 0; $i < 6; $i++) {
            if ($i === 3) {
                $cyclePairs = []; // refresh for new cycle
            }
            $result = $generator->getRoundMatchPairs($i + 1);
            $this->assertNotEquals($result[0], $result[1]);

            $ids = [];
            foreach ($result as $pair) {
                $ids[] = $pair->tNum1;
                $ids[] = $pair->tNum2;
                $cyclePairs[] = $this->concatenatePairNums($pair);
            }
            // check unique in one round
            $this->assertCount(4, array_filter(array_unique($ids)), print_r($result, true));
        }
        // check for unique pair for whole cycle
        $this->assertCount(count($cyclePairs), array_filter(array_unique($cyclePairs)), print_r($cyclePairs, true));
    }


    public function testMatchPairGeneratorCreateUniformDistribution(): void
    {
        $generator = new SimpleMatchPairGeneratorTeam4($this->teamList);

        $allPairs = [];
        for ($i = 0; $i < 12; $i++) {
            $roundNum = $i + 1;
            $result = $generator->getRoundMatchPairs($roundNum);

            foreach ($result as $pair) {
                $idx = $this->concatenatePairNums($pair);
                if (!isset($allPairs[$idx])) {
                    $allPairs[$idx] = 0;
                }
                $allPairs[$idx] += 1;
            }

            if ($roundNum % 3 === 0) {
                $this->assertCount(1, array_unique(array_values($allPairs)), print_r($allPairs, true));
            }
        }
    }

    private function concatenatePairNums(MatchPair $matchPair): string
    {
        return $matchPair->tNum1 . $matchPair->tNum2;
    }

}
