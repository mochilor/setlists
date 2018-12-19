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
        self::$expectedCode = 200;

        $formParams = [];
        if (isset(self::$songs[0]['title'])) {
            $formParams['title'] = self::$songs[0]['title'];
        } else{
            self::$expectedCode = 500;
        }
        if (isset(self::$songs[0]['visibility'])) {
            $formParams['visibility'] = self::$songs[0]['visibility'];
        }

        $options = [
            'form_params' => $formParams
        ];

        $this->request(
            'patch',
            $this->apiUrl . '/song/' . self::$songs[0]['id'],
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
}
