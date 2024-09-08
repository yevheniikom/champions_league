<?php
declare(strict_types=1);

namespace Src\Tournaments;

use ArrayObject;
use InvalidArgumentException;
use Src\Teams\Team;

/**
 * This class contains information about all teams participating in the tournament and ensures that this data is correct
 *
 * @template-extends ArrayObject<int,Team>
 */
class TeamList extends ArrayObject
{
    /**
     * @param non-empty-list<Team> $teamList
     */
    public function __construct(array $teamList)
    {
        if (count($teamList) <= 2) {
            throw new InvalidArgumentException("Team List count error");
        } else {
            $teamByName = [];
            foreach ($teamList as $team) {
                if (!($team instanceof Team)) {
                    throw new InvalidArgumentException("Team List has to contain teams");
                }
                $teamByName[$team->getName()] = $team;
            }
            if (count($teamByName) !== count($teamList)) {
                throw new InvalidArgumentException("Team List has to contain Teams with unique name");
            }
        }

        parent::__construct($teamList);
    }
}