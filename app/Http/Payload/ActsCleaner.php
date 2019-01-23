<?php

namespace App\Http\Payload;

class ActsCleaner
{
    public function cleanActs($acts): array
    {
        if (!is_array($acts)) {
            return [];
        }

        foreach ($acts as $actKey => $act) {
            if (!is_array($act)) {
                unset($acts[$actKey]);
                continue;
            }

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