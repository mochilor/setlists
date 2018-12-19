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
        $this->validateSongsToBePersisted($table);
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
        self::$expectedCode = 200;

        foreach (self::$persistedSongs as $song) {
            $this->checkSong($song);
        }
    }

    /**
     * @Then the api must show me all the songs if I request them
     */
    public function theApiMustShowMeAllTheSongsIfIRequestThem()
    {
        self::$expectedCode = 200;

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
        self::$expectedCode = 200;

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
        self::$expectedCode = 200;

        $response = $this->request(
            'get',
            $this->apiUrl . '/songs?interval=' . $arg1 . ',999'
        );

        $this->checkMultipleSongs($response, count(self::$persistedSongs) - $arg1);
    }

    /**
     * @Then the api must return an error response with code: :arg1
     */
    public function theApiMustReturnAnErrorResponseWithCode($arg1)
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
        self::$expectedCode = 200;

        $response = $this->request(
            'get',
            $this->apiUrl . '/songs'
        );

        $this->checkMultipleSongs($response, 0);
    }

    /**
     * @Given the following song exists:
     */
    public function theFollowingSongExists(TableNode $table)
    {
        $this->setSongsFromTableNode($table);
        $this->requestSongCreation();
    }
}
