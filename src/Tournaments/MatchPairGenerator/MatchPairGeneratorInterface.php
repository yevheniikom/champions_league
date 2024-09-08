<?php
declare(strict_types=1);

namespace Src\Tournaments\MatchPairGenerator;

use Src\Matches\MatchPair;

/**
 *  The interface for classes that determine which teams should play in a particular round of a tournament.
 */
interface MatchPairGeneratorInterface
{
    /**
     * @param non-negative-int $actual
     *
     * @return non-empty-list<MatchPair>
     */
    public function getRoundMatchPairs(int $actual): array;
}