<?php

namespace Tests\Tournaments;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Src\Tournaments\TeamList;
use Tests\TraitsHelpers\CreateArrayTeamListFromNamesList;

#[CoversClass(TeamList::class)]
final class TeamListTest extends TestCase
{
    use CreateArrayTeamListFromNamesList;

    /**
     * @param list<non-empty-string> $teamNames
     */
    #[DataProvider('correctData')]
    public function testTeamListCreation(array $teamNames): void {

        $teamList = $this->createTeamListFromNamesList($teamNames);

        // if we had no exception than everything is fine
        $this->assertTrue(true);
    }

    /**
     * @param list<non-empty-string> $teamNames
     */
    #[DataProvider('wrongData')]
    public function testTeamListCreationThrowExceptionOnWrongNames(array $teamNames): void {

        $teamList = $this->createTeamListFromNamesList($teamNames);

        $this->expectException(InvalidArgumentException::class);
        /** @psalm-suppress InvalidArgument */
        $teamList = new TeamList($teamList);
    }

    public static function correctData(): array {
        $baseList = ['Chelsea','Manchester City','Arsenal','Liverpool'];
        return [
            [$baseList],
            [[...$baseList,'Newcastle','Olympiad']],
            [[...$baseList,'Newcastle','Olympiad','OtherChelsea','Alchester City']],
        ];
    }

    public static function wrongData(): array {
        return [
            [['Chelsea']], //not enough members
            [['Chelsea', 'Arsenal']], //not enough members
            [['Manchester City', 'Manchester City', 'Manchester City', 'Manchester City']], // same name
            [['Chelsea','Chelsea','Arsenal','Liverpool']], //one duplicate
        ];
    }
}
