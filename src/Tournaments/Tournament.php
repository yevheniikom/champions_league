<?php
declare(strict_types=1);

namespace Src\Tournaments;

use InvalidArgumentException;
use Src\Matches\MatchEngine\MatchEngineInterface;
use Src\Tournaments\LeaderBoard\ChanceToWin;
use Src\Tournaments\LeaderBoard\Table as FinalTable;
use Src\Tournaments\MatchPairGenerator\MatchPairGeneratorInterface;

/**
 * This class provides the basic functionality for simulating a tournament
 */
class Tournament
{

    private MatchEngineInterface $matchEngine;
    private MatchPairGeneratorInterface $matchPairGenerator;
    private FinalTable $finalTable;
    /** @var list<RoundResult> */
    private array $rounds;
    /**@var positive-int */
    private readonly int $maxRoundCount;

    /**
     * @param positive-int $playsCount
     * @param list<RoundResult> $rounds
     */
    public function __construct(MatchEngineInterface $matchEngine, MatchPairGeneratorInterface $matchPairGenerator, TeamList $teamList, int $playsCount = 2, array $rounds = [])
    {
        $this->matchEngine = $matchEngine;
        $this->matchPairGenerator = $matchPairGenerator;
        $this->finalTable = new FinalTable($teamList);
        $n = $teamList->count();
        if ($n % 2 !== 0) {
            throw new InvalidArgumentException('This tournament requires an even number of players!');
        }
        $this->maxRoundCount = (int)round($n * ($n - 1) / 4 * $playsCount);

        $this->rounds = $rounds;
    }

    /**
     * Starts the simulation of the next round of the tournament. If the simulation is over, nothing happens.
     */
    public function nextRound(): ?RoundResult
    {
        $roundCounter = $this->getNextRoundNumber();
        if ($roundCounter > $this->maxRoundCount) {
            return null;
        }
        $matchPairs = $this->matchPairGenerator->getRoundMatchPairs($roundCounter);
        $plays = [];
        foreach ($matchPairs as $matchPair) {
            $game = $this->matchEngine->match($matchPair);
            $this->finalTable->addMatch($game);
            $plays[] = $game;
        }

        return $this->rounds[] = new RoundResult($roundCounter, $plays);
    }

    /**
     * Runs the tournament simulation until the end
     */
    public function playAll(): void
    {
        while ($this->getNextRoundNumber() <= $this->maxRoundCount) {
            $this->nextRound();
        }
    }

    /**
     * Returns the tournament table with the results of the game at the moment
     */
    public function getFinalTable(): FinalTable
    {
        return clone $this->finalTable;
    }

    /**
     * @return list<array{non-empty-string, float|int}>
     */
    public function getChanceToWinTable(): array
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return (new ChanceToWin($this->finalTable))
            ->getEstimatedTable(count($this->rounds), $this->maxRoundCount);
    }

    /**
     * Returns information about all played rounds
     *
     * @return RoundResult[]
     */
    public function getAllRounds(): array
    {
        return $this->rounds;
    }
    /**
     * Returns information about the last round played
     */
    public function getLastRound(): ?RoundResult
    {

        return end($this->rounds) ?: null;
    }

    /**
     * @return bool - true if all rounds of the tournament have been completed
     */
    public function isFinished(): bool
    {
        return $this->getNextRoundNumber() > $this->maxRoundCount;
    }

    /**
     * @return positive-int
     */
    private function getNextRoundNumber(): int
    {
        return count($this->rounds) + 1;
    }
}