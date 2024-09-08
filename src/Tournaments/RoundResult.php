<?php
declare(strict_types=1);

namespace Src\Tournaments;

use Src\Matches\MatchResult;

/**
 * This DTO class contains information about the results of the games of all teams in the round
 */
readonly class RoundResult
{

    /**
     * @param positive-int $roundNum
     * @param non-empty-list<MatchResult> $plays
     */
    public function __construct(
        public int   $roundNum,
        public array $plays
    )
    {
    }
}