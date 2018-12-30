<?php

use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class DeleteSongsContext extends BaseContext implements Context
{
    /**
     * @When /^I request the api service to delete the song$/
     */
    public function iRequestTheApiServiceToDeleteTheSong()
    {
        $song = self::$songs[0];

        $this->request(
            'delete',
            $this->apiUrl . '/song/' . $song['id']
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
     * @When I request the api service to delete the song with id: :arg1
     */
    public function iRequestTheApiServiceToDeleteTheSongWithId($arg1)
    {
        $this->request(
            'delete',
            $this->apiUrl . '/song/' . $arg1
        );
    }
}
