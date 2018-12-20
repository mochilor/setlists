<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class updateSongsContext extends BaseContext implements Context
{
    /**
     * @Given /^I want to change its data to the following values:$/
     */
    public function iWantToChangeItsDataToTheFollowingValues(TableNode $table)
    {
        $this->setSongsFromTableNode($table);
    }

    /**
     * @When /^I request the api service to update the song$/
     */
    public function iRequestTheApiServiceToUpdateTheSong()
    {
        $song = self::$songs[0];

        $formParams = [];
        if (isset($song['title'])) {
            $formParams['title'] = $song['title'];
        }

        if (isset($song['visibility'])) {
            $formParams['visibility'] = $song['visibility'];
        }

        $options = [
            'form_params' => $formParams
        ];

        $this->request(
            'patch',
            $this->apiUrl . '/song/' . $song['id'],
            $options
        );

        if (self::$responseCode == 200) {
            self::$persistedSongs = self::$songs;
        }
    }

    /**
     * @Then /^the song should be updated$/
     */
    public function theSongShouldBeUpdated()
    {
        $this->checkSong(self::$persistedSongs[0]);
    }

    /**
     * @Given /^the existent songs should be exactly like:$/
     */
    public function theExistentSongsShouldBeExactlyLike(TableNode $table)
    {
        $songs = $this->getSongsFromTableNode($table);

        Assert::assertEquals(
            $songs,
            self::$persistedSongs
        );
    }

    /**
     * @Given I want to update a song with the following values:
     */
    public function iWantToUpdateASongWithTheFollowingValues(TableNode $table)
    {
        $this->setSongsFromTableNode($table);
    }
}
