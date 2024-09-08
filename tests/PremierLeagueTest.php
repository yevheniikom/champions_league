<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Src\PremierLeagueSimulation;
use Src\Tournaments\RoundResult;
use Src\Tournaments\Tournament;

#[CoversClass(Tournament::class)]
final class PremierLeagueTest extends TestCase
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private Tournament $league;

    public function setUp(): void
    {
        $this->league = PremierLeagueSimulation::createTournament4Team();
    }

    public function testPremierLeagueIsWorking(): void
    {
        $league = $this->league;

        $this->assertNull($league->getLastRound());

        $result = $league->nextRound();
        $this->assertInstanceOf(RoundResult::class, $result);

        $league->playAll();
        $this->assertInstanceOf(RoundResult::class, $league->getLastRound());
    }

    public function testPremierLeagueNextWeek(): void
    {
        $league = $this->league;
        $prevPlays = null;

        for ($i = 1; $i <= 6; $i++) {
            $result = $league->nextRound();
            $this->assertInstanceOf(RoundResult::class, $result);
            $this->assertEquals($result, $league->getLastRound());
            $this->assertEquals($i, $result->roundNum);
            $this->assertCount(2, $result->plays);
            $this->assertNotEquals($result->plays[0]->toArray(), $result->plays[1]->toArray());
            if ($prevPlays) {
                $this->assertNotEquals($prevPlays, $result->plays);
            }
            $prevPlays = $result->plays;
        }
    }

    public function testTryToPlayOneMoreThanAllowedWeek(): void
    {
        for ($i = 0; $i < 7; $i++) {
            $last = $this->league->nextRound();
        }
        $this->assertNull($last);
    }

    public function testIsCorrectlyPlayToTheEnd(): void
    {
        $league = $this->league;
        $this->assertEquals(null, $league->getLastRound());

        $league->playAll();
        $lastRound = $league->getLastRound();
        $this->assertInstanceOf(RoundResult::class, $lastRound);
        $this->assertEquals(6, $lastRound->roundNum);
    }
}