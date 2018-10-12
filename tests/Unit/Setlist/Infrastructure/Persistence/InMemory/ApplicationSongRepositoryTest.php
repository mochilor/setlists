<?php

namespace Tests\Unit\Setlist\Infrastructure\Persistence\InMemory;

use Setlist\Infrastructure\Persistence\InMemory\ApplicationSongRepository;
use PHPUnit\Framework\TestCase;

class ApplicationSongRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function repositoryCanGetAllTitles()
    {
        $applicationRepository = new ApplicationSongRepository();
        $titles = [
            'Title 1',
            'Title 2',
            'Title 3',
        ];
        $applicationRepository->titles = $titles;

        $this->assertEquals(
            $applicationRepository->getAllTitles(),
            $titles
        );
    }
}
