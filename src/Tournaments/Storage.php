<?php
declare(strict_types=1);

namespace Src\Tournaments;

use Throwable;

/**
 * This class is responsible for saving/restoring information about the tournament
 */
class Storage
{
    /** @var non-empty-string */
    private string $filePath;

    /** @param non-empty-string $filePath */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function save(Tournament $premierLeague): void
    {
        $serialized = serialize($premierLeague);
        file_put_contents($this->filePath, $serialized);
    }

    public function load(): ?Tournament
    {
        try {
            if (file_exists($this->filePath)) {
                $serialized = file_get_contents($this->filePath);
                /** @psalm-suppress MixedAssignment */
                $premierLeague = unserialize($serialized);
                if ($premierLeague instanceof Tournament) {
                    return $premierLeague;
                }
            }
        } catch (Throwable) {
            // do nothing
        }

        return null;
    }
}