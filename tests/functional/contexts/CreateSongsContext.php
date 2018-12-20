<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class CreateSongsContext extends BaseContext implements Context
{
    /**
     * @Given I want to create songs with values:
     * @param TableNode $table
     */
    public function iWantToCreateSongsWithValues(TableNode $table)
    {
        $this->setSongsFromTableNode($table);
    }

    /**
     * @When I request the api service to create the songs
     */
    public function iRequestTheApiServiceToCreateTheSongs()
    {
        $this->requestSongCreation();
    }

    /**
     * @Then the api must show me any of the songs if I request them by their id
     */
    public function theApiMustShowMeAnyOfTheSongsIfIRequestThemByTheirId()
    {
        foreach (self::$persistedSongs as $song) {
            $this->checkSong($song);
        }
    }

    /**
     * @Then the api must show me all the songs if I request them
     */
    public function theApiMustShowMeAllTheSongsIfIRequestThem()
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/songs'
        );

        $this->checkMultipleSongs($response, count(self::$persistedSongs));
    }

    /**
     * @Then the api must be able to show me a list with songs from: :arg1 to: :arg2
     */
    public function theApiMustBeAbleToShowMeAListWithSongsFromTo($arg1, $arg2)
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/songs?interval=' . $arg1 . ',' . $arg2
        );

        $this->checkMultipleSongs($response, $arg2 - $arg1);
    }

    /**
     * @Then the api must be able to show me a list with songs from: :arg1 to the end
     */
    public function theApiMustBeAbleToShowMeAListWithSongsFromToTheEnd($arg1)
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/songs?interval=' . $arg1 . ',999'
        );

        $this->checkMultipleSongs($response, count(self::$persistedSongs) - $arg1);
    }

    /**
     * @Then /^the api must return a response with code: (\d+)$/
     */
    public function theApiMustReturnAResponseWithCode($arg1)
    {
        Assert::assertEquals(
            $arg1,
            self::$responseCode
        );
    }

    /**
     * @Then the api must not return any song when I request all the stored songs
     */
    public function theApiMustNotReturnAnySongWhenIRequestAllTheStoredSongs()
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/songs'
        );

        $this->checkMultipleSongs($response, 0);
    }

    /**
     * @Given /^the following songs? exists:$/
     */
    public function theFollowingSongExists(TableNode $table)
    {
        $this->setSongsFromTableNode($table);
        $this->requestSongCreation();
    }

    /**
     * @Given no Song exist
     */
    public function noSongExist()
    {
        //
    }

    /**
     * @When I request the api to show me the song with id: :arg1
     */
    public function iRequestTheApiToShowMeTheSongWithId($arg1)
    {
        $this->request(
            'get',
            $this->apiUrl . '/song/' . $arg1
        );
    }
}
