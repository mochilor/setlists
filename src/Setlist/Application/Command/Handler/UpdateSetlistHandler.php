<?php

namespace Setlist\Application\Command\Handler;

use DateTime;
use Setlist\Application\Command\Handler\Helper\SetlistHandlerHelper;
use Setlist\Application\Command\UpdateSetlist;
use Setlist\Application\Exception\InvalidSetlistException;
use Setlist\Application\Exception\SetlistDoesNotExistException;
use Setlist\Application\Exception\SetlistNameNotUniqueException;
use Setlist\Application\Persistence\Setlist\ApplicationSetlistRepository;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class UpdateSetlistHandler
{
    private $setlistRepository;
    private $applicationSetlistRepository;
    private $songRepository;
    private $setlistFactory;
    private $songFactory;
    private $setlistHandlerHelper;

    public function __construct(
        SetlistRepository $setlistRepository,
        ApplicationSetlistRepository $applicationSetlistRepository,
        SongRepository $songRepository,
        SetlistFactory $setlistFactory,
        SongFactory $songFactory,
        SetlistHandlerHelper $setlistHandlerHelper
    ) {
        $this->setlistRepository = $setlistRepository;
        $this->setlistFactory = $setlistFactory;
        $this->songFactory = $songFactory;
        $this->setlistHandlerHelper = $setlistHandlerHelper;
        $this->songRepository = $songRepository;
        $this->applicationSetlistRepository = $applicationSetlistRepository;
    }

    public function __invoke(UpdateSetlist $command)
    {
        $uuid = Uuid::create($command->uuid());
        $setlist = $this->setlistRepository->get($uuid);

        $this->guard($command, $setlist);

        $acts = $this->setlistHandlerHelper->getActsForSetlist($command->acts());
        $actCollection = ActCollection::create(...$acts);
        $dateTime = DateTime::createFromFormat(Setlist::DATE_TIME_FORMAT, $command->date());

        $setlist->changeName($command->name());
        $setlist->changeActCollection($actCollection);
        $setlist->changeDate($dateTime);

        $this->setlistRepository->save($setlist);
    }

    private function guard(UpdateSetlist $command, $setlist)
    {
        if (!$setlist instanceof Setlist) {
            throw new SetlistDoesNotExistException('Setlist not found');
        }

        $otherNames = $this->applicationSetlistRepository->getOtherNames($command->uuid());
        if (in_array($command->name(), $otherNames)) {
            throw new SetlistNameNotUniqueException('Setlist name already exists');
        }

        $songs = [];
        foreach ($command->acts() as $act) {
            foreach ($act as $songUuid) {
                if (in_array($songUuid, $songs)) {
                    throw new InvalidSetlistException('Non unique song provided');
                }

                $songs[] = $songUuid;
            }
        }
    }
}
