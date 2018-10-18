<?php

namespace Tests\Unit\Setlist\Infrastructure\Persistence\InMemory;

use PHPUnit\Framework\TestCase;
use Setlist\Infrastructure\Repository\Application\InMemory\SetlistRepository;

class ApplicationSetlistRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function repositoryCanGetAllTitles()
    {
        $applicationRepository = new SetlistRepository();
        $names = [
            'Name 1',
            'Name 2',
            'Name 3',
        ];
        $applicationRepository->names = $names;

        $this->assertEquals(
            $applicationRepository->getAllNames(),
            $names
        );
    }
}
