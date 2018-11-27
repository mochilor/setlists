<?php

namespace Setlist\Application\Command\Setlist\Handler;

use DateTime;
use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use Setlist\Application\Command\Setlist\UpdateSetlist;
use Setlist\Application\Exception\InvalidSetlistException;
use Setlist\Application\Exception\SetlistDoesNotExistException;
use Setlist\Application\Exception\SetlistNameNotUniqueException;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistNameRepository;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Exception\Setlist\InvalidDateException;
use Setlist\Domain\Value\Uuid;

class UpdateSetlistHandler
{
    private $setlistRepository;
    private $setlistNameRepository;
    private $setlistHandlerHelper;

    public function __construct(
        SetlistRepository $setlistRepository,
        SetlistNameRepository $setlistNameRepository,
        SetlistHandlerHelper $setlistHandlerHelper
    ) {
        $this->setlistRepository = $setlistRepository;
        $this->setlistNameRepository = $setlistNameRepository;
        $this->setlistHandlerHelper = $setlistHandlerHelper;
    }

    public function __invoke(UpdateSetlist $command)
    {
        $uuid = Uuid::create($command->uuid());
        $setlist = $this->setlistRepository->get($uuid);

        $this->guard($command, $setlist);

        $acts = $this->setlistHandlerHelper->getActsForSetlist($command->acts());
        $actCollection = ActCollection::create(...$acts);
        $dateTime = DateTime::createFromFormat(Setlist::DATE_TIME_FORMAT, $command->date());

        if (empty($dateTime)) {
            throw new InvalidDateException('Invalid date provided');
        }

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

        if (!$this->setlistNameRepository->nameIsUnique($command->name(), $command->uuid())) {
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
