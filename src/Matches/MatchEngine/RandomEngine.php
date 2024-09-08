<?php
declare(strict_types=1);

namespace Src\Matches\MatchEngine;

use Src\Matches\MatchPair;
use Src\Matches\MatchResult;

/**
 * This is the simplest implementation of the engine, which calculates the outcome of the game randomly
 */
class RandomEngine implements MatchEngineInterface
{

    public function match(MatchPair $matchPair): MatchResult
    {
        $goals = [rand(0, 5), rand(0, 5)];

        return new MatchResult($matchPair, $goals);
    }
}