<?php

namespace Tests\Unit\Setlist\Infrastructure\Persistence\InMemory;

use Setlist\Infrastructure\Persistence\InMemory\ApplicationSetlistRepository;
use PHPUnit\Framework\TestCase;

class ApplicationSetlistRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function repositoryCanGetAllTitles()
    {
        $applicationRepository = new ApplicationSetlistRepository();
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
