<?php

use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class ForceDeleteSongsContext extends BaseContext implements Context
{
    /**
     * @When /^I request the api service to force delete the song$/
     */
    public function iRequestTheApiServiceToForceDeleteTheSong()
    {
        $song = self::$songs[0];

        $this->request(
            'delete',
            $this->apiUrl . '/song/' . $song['id'] . '/force'
        );

        if (self::$responseCode == 200) {
            foreach (self::$persistedSongs as $key => $persistedSong) {
                if ($persistedSong['id'] == $song['id']) {
                    unset(self::$persistedSongs[$key]);
                }
            }
        }
    }

    /**
     * @When I request the api service to force delete the song with id: :arg1
     */
    public function iRequestTheApiServiceToForceDeleteTheSongWithId($arg1)
    {
        $this->request(
            'delete',
            $this->apiUrl . '/song/' . $arg1 . '/force'
        );
    }
}
