<?php
declare(strict_types=1);

namespace Src\Matches\MatchEngine;

use Src\Matches\MatchPair;
use Src\Matches\MatchResult;

/**
 * This interface is intended for different engines that can calculate the result of matches of 2 teams
 * This component is implemented for the potential expansion of the functionality, where teams will have characteristics
 * and based on them the engine will be able to simulate the outcome of the match
 */
interface MatchEngineInterface
{
    public function match(MatchPair $matchPair): MatchResult;
}