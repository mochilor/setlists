<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class SongContext extends BaseContext implements Context
{
    /**
     * @var array
     */
    private $songList;

    /**
     * @var array
     */
    private $songStats;

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

        Assert::assertEquals(
            200,
            self::$responseCode
        );

        $this->checkMultipleSongs($response, count(self::$persistedSongs));

        $this->songList = json_decode($response, true);
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

        Assert::assertEquals(
            200,
            self::$responseCode
        );

        $this->checkMultipleSongs($response, $arg2 - $arg1);

        $this->songList = json_decode($response, true);
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

        Assert::assertEquals(
            200,
            self::$responseCode
        );

        $this->checkMultipleSongs($response, count(self::$persistedSongs) - $arg1);

        $this->songList = json_decode($response, true);
    }

    /**
     * @Given the api must be able to show me a list with songs from: :arg1 to: :arg2 filtered by the word: :arg3
     */
    public function theApiMustBeAbleToShowMeAListWithSongsFromToFilteredByTheWord($arg1, $arg2, $arg3)
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/songs?interval=' . $arg1 . ',' . $arg2 . '&title=' . $arg3
        );

        Assert::assertEquals(
            200,
            self::$responseCode
        );

        $maxSong = $arg2 - $arg1;
        $count = 0;

        foreach (self::$persistedSongs as $keyPersistedSong => $persistedSong) {
            if ($count == $maxSong) {
                break;
            }

            $persistedTitle = strtolower($persistedSong['title']);
            if (strstr($persistedTitle, strtolower($arg3))) {
                $count++;
            }
        }

        $this->checkMultipleSongs($response, $count);

        $this->songList = json_decode($response, true);
    }

    /**
     * @Given the api must be able to show me a list with songs filtered by the word: :arg1
     */
    public function theApiMustBeAbleToShowMeAListWithSongsFilteredByTheWord($arg1)
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/songs?title=' . $arg1
        );

        Assert::assertEquals(
            200,
            self::$responseCode
        );
        $count = 0;

        foreach (self::$persistedSongs as $keyPersistedSong => $persistedSong) {

            $persistedTitle = strtolower($persistedSong['title']);
            if (strstr($persistedTitle, strtolower($arg1))) {
                $count++;
            }
        }

        $this->checkMultipleSongs($response, $count);

        $this->songList = json_decode($response, true);
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

    /**
     * @Then the api must return a response with code: :arg2 if I request the song with id: :arg1
     */
    public function theApiMustReturnAResponseWithCodeIfIRequestTheSongWithId($arg1, $arg2)
    {
        $this->request(
            'get',
            $this->apiUrl . '/song/' . $arg1
        );

        Assert::assertEquals(
            $arg2,
            self::$responseCode
        );
    }

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

    /**
     * @Given /^the songs in the list will be these ones:$/
     */
    public function theSongsInTheListWillBeTheseOnes(TableNode $table)
    {
        foreach ($table as $keyRow => $row) {
            Assert::assertEquals(
                $row['id'],
                $this->songList[$keyRow]['id']
            );
            Assert::assertEquals(
                $row['title'],
                $this->songList[$keyRow]['title']
            );
        }
    }

    /**
     * @When I request the api service to show me the setlists to which the song with id: :arg1 belongs
     */
    public function iRequestTheApiServiceToShowMeTheSetlistsToWhichTheSongWithIdBelongs($arg1)
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/song/stats/' . $arg1
        );

        $count = 0;

        foreach (self::$persistedSetlists as $persistedSetlist) {
            foreach ($persistedSetlist['acts'] as $act) {
                foreach ($act as $song) {
                    if ($song['id'] == $arg1) {
                        $count++;
                    }
                }
            }
        }

        $this->checkMultipleSetlists($response, $count, false);

        $this->songStats = json_decode($response, true);
    }

    /**
     * @Then the api must return the following setlists:
     */
    public function theApiMustReturnTheFollowingSetlists(TableNode $table)
    {
        $setlists = $this->getSetlistsFromTableNode($table);

        foreach ($setlists as $setlistKey => $setlist) {
            Assert::assertEquals(
                $setlist['id'],
                $this->songStats[$setlistKey]['id']
            );
            Assert::assertEquals(
                $setlist['name'],
                $this->songStats[$setlistKey]['name']
            );
            Assert::assertEquals(
                $setlist['description'],
                $this->songStats[$setlistKey]['description']
            );
            Assert::assertEquals(
                $setlist['date'],
                $this->songStats[$setlistKey]['date']
            );
        }
    }
}
