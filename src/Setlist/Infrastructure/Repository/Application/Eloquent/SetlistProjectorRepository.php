<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository as SetlistProjectorRepositoryInterface;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;
use Setlist\Infrastructure\Repository\Application\Eloquent\Model\SetlistProjection;

class SetlistProjectorRepository implements SetlistProjectorRepositoryInterface
{
    public function save(SetlistWasCreated $event)
    {
        $data = $this->prepareData($event);

        $setlistProjection = new SetlistProjection();
        $setlistProjection->id = (string) $event->id();
        $setlistProjection->data = $data;
        $setlistProjection->save();
    }

    private function prepareData(SetlistWasCreated $event): string
    {
        $data = [
            'id' => (string) $event->id(),
            'name' => $event->name(),
            'date' => $event->formattedDate(),
            'creation_date' => $event->formattedCreationDate(),
            'update_date' => $event->formattedUpdateDate(),
        ];

        $actsArray = $this->getActsArray($event->actCollection());

        $data['acts'] = $actsArray;

        return json_encode($data);
    }

    public function changeName(SetlistChangedItsName $event)
    {
        $setlistProjection = SetlistProjection::find($event->id());

        if (!$setlistProjection) {
            // Throw Exception?
            return;
        }

        $data = json_decode($setlistProjection->data, true);
        $data['name'] = $event->name();
        $setlistProjection->data = json_encode($data);
        $setlistProjection->save();
    }

    public function changeDate(SetlistChangedItsDate $event)
    {
        $setlistProjection = SetlistProjection::find($event->id());

        if (!$setlistProjection) {
            // Throw Exception?
            return;
        }

        $data = json_decode($setlistProjection->data, true);
        $data['date'] = $event->formattedDate();
        $setlistProjection->data = json_encode($data);
        $setlistProjection->save();
    }

    public function changeActCollection(SetlistChangedItsActCollection $event)
    {
        $setlistProjection = SetlistProjection::find($event->id());

        if (!$setlistProjection) {
            // Throw Exception?
            return;
        }

        $data = json_decode($setlistProjection->data, true);
        $actsArray = $this->getActsArray($event->actCollection());
        $data['acts'] = $actsArray;
        $setlistProjection->data = json_encode($data);
        $setlistProjection->save();
    }

    public function delete(SetlistWasDeleted $event)
    {
        SetlistProjection::destroy($event->id());
    }

    private function getActsArray(ActCollection $actCollection): array
    {
        $actsArray = [];

        foreach ($actCollection as $act) {
            $actSongs = [];
            foreach ($act->songCollection() as $song) {
                $actSongs[] = [
                    'id' => (string)$song->id(),
                    'title' => $song->title(),
                    'is_visible' => $song->isVisible(),
                    'creation_date' => $song->formattedCreationDate(),
                    'update_date' => $song->formattedUpdateDate(),
                ];
            }

            $actsArray[] = $actSongs;
        }

        return $actsArray;
    }
}
