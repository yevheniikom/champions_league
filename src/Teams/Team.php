<?php
declare(strict_types=1);

namespace Src\Teams;

use InvalidArgumentException;

/**
 * This class contains the main characteristics of the team
 */
class Team
{

    /**  @var non-empty-string $name */
    private string $name;
    private Strengths $strengths;

    /**
     * @param non-empty-string $name
     */
    public function __construct(string $name, Strengths $strengths)
    {
        if (strlen($name) <= 1) {
            throw new InvalidArgumentException("Team name can't be empty or less than 2 characters");
        } elseif (strlen($name) > 55) {
            throw new InvalidArgumentException("Team name can't be longer than 55 characters");
        }

        $this->name = $name;
        $this->strengths = $strengths;
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getStrengths(): Strengths
    {
        return $this->strengths;
    }
}