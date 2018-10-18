<?php

namespace Tests\Unit\Setlist\Infrastructure\Persistence\InMemory;

use PHPUnit\Framework\TestCase;
use Setlist\Infrastructure\Repository\Application\InMemory\SongRepository;

class ApplicationSongRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function repositoryCanGetAllTitles()
    {
        $applicationRepository = new SongRepository();
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
