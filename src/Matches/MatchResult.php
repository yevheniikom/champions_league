<?php
declare(strict_types=1);

namespace Src\Matches;

/**
 * This is a DTO class that contains information about which teams played and with what result
 */
readonly class MatchResult
{
    /**
     * @param list<non-negative-int> $goals
     */
    public function __construct(
        public MatchPair $matchPair,
        public array     $goals,
    ) {}

    public function toArray(): array
    {
        return [
            'matchPair' => $this->matchPair->toArray(),
            'goals' => $this->goals,
        ];
    }
}