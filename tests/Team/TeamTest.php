<?php
declare(strict_types=1);

namespace Tests\Team;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Src\Teams\Strengths;
use Src\Teams\Team;

#[CoversClass(Team::class)]
final class TeamTest extends TestCase
{
    public static function initData(): array
    {
        $strengths = new Strengths();
        return [
            ['', $strengths, false],
            ['t', $strengths, false],
            ['TU', $strengths, true],
            ['Chelsea', $strengths, true],
            ['LongTeamNameButNotTooMuch', $strengths, true],
            [
                'llllllllllllllllllllooooooooooooooooooooooooooooonnnnnnnnnnnnnnnnnggggggggggggggggggggggg',
                $strengths, false
            ],
        ];
    }

    #[DataProvider('initData')]
    public function testCreate(string $name, Strengths $strengths, bool $isCorrect): void
    {
        if (!$isCorrect) {
            $this->expectException(InvalidArgumentException::class);
        }
        /** @psalm-suppress ArgumentTypeCoercion */
        $team = new Team($name, $strengths);

        if ($isCorrect) {
            $this->assertEquals($name, $team->getName());
            $this->assertEquals($strengths, $team->getStrengths());
        }
    }
}
