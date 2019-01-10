<?php

namespace App\Http\Payload;

class ActsCleaner
{
    public function cleanActs(array $acts): array
    {
        foreach ($acts as $actKey => $act) {
            foreach ($act as $songKey => $songId) {
                if (empty($songId)) {
                    unset($acts[$actKey][$songKey]);
                }
            }

            if (empty($acts[$actKey])) {
                unset($acts[$actKey]);
            }
        }

        return $acts;
    }
}