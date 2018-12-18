<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class HandleSongsContext extends MinkContext implements Context
{
    /**
     * @var array
     */
    private $songs = [];

    /**
     * @var array
     */
    private $persistedSongs = [];

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var int
     */
    private $expectedCode = 200;

    /**
     * @var int
     */
    private $responseCode = 200;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('API_URL');
    }

    /**
     * @static
     * @BeforeScenario
     */
    public static function prepareDatabase()
    {
        echo "Preparing database...\n\n";
        Artisan::call('migrate:fresh');
    }

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
        foreach ($this->persistedSongs as $song) {
            $this->checkSong($song);
        }
    }

    /**
     * @Then the api must show me all the songs if I request them
     */
    public function theApiMustShowMeAllTheSongsIfIRequestThem()
    {
        $this->expectedCode = 200;

        $result = $this->request(
            'get',
            $this->apiUrl . '/songs'
        );

        $this->checkMultipleSongs($result, count($this->persistedSongs));
    }

    /**
     * @Then the api must be able to show me a list with songs from: :arg1 to: :arg2
     */
    public function theApiMustBeAbleToShowMeAListWithSongsFromTo($arg1, $arg2)
    {
        $this->expectedCode = 200;

        $result = $this->request(
            'get',
            $this->apiUrl . '/songs?interval=' . $arg1 . ',' . $arg2
        );

        $this->checkMultipleSongs($result, $arg2 - $arg1);
    }

    /**
     * @Then the api must be able to show me a list with songs from: :arg1 to the end
     */
    public function theApiMustBeAbleToShowMeAListWithSongsFromToTheEnd($arg1)
    {
        $this->expectedCode = 200;

        $result = $this->request(
            'get',
            $this->apiUrl . '/songs?interval=' . $arg1 . ',999'
        );

        $this->checkMultipleSongs($result, count($this->persistedSongs) - $arg1);
    }

    /**
     * @param string $verb
     * @param string $url
     * @param array $options
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $verb, string $url, array $options = [])
    {
        try {
            $result = $this->client->request($verb, $url, $options);
            $this->responseCode = $result->getStatusCode();
            $result = (string) $result->getBody();
        } catch (RuntimeException $e) {
            $this->responseCode = $e->getResponse()->getStatusCode();
            $result = '';
        }

        Assert::assertEquals(
            $this->expectedCode,
            $this->responseCode
        );

        return $result;
    }

    /**
     * @param array $song
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function checkSong(array $song): void
    {
        $this->expectedCode = 200;

        $result = $this->request(
            'get',
            $this->apiUrl . '/song/' . $song['id']
        );

        Assert::assertJson($result);

        $responseSong = json_decode($result, true);

        Assert::assertArrayHasKey('id', $responseSong);
        Assert::assertArrayHasKey('title', $responseSong);
        Assert::assertArrayHasKey('is_visible', $responseSong);
        Assert::assertArrayHasKey('creation_date', $responseSong);
        Assert::assertArrayHasKey('update_date', $responseSong);

        Assert::assertEquals(
            $responseSong['id'],
            $song['id']
        );

        Assert::assertEquals(
            $responseSong['title'],
            $song['title']
        );

        Assert::assertEquals(
            $responseSong['is_visible'],
            $song['visibility']
        );
    }

    /**
     * @param string $result
     * @param int $songsCount
     */
    private function checkMultipleSongs(string $result, int $songsCount): void
    {
        Assert::assertJson($result);

        $responseSongs = json_decode($result, true);

        Assert::assertEquals(
            $songsCount,
            count($responseSongs)
        );

        $count = 0;
        foreach ($responseSongs as $responseSong) {

            Assert::assertArrayHasKey('id', $responseSong);
            Assert::assertArrayHasKey('title', $responseSong);
            Assert::assertArrayHasKey('is_visible', $responseSong);
            Assert::assertArrayHasKey('creation_date', $responseSong);
            Assert::assertArrayHasKey('update_date', $responseSong);

            foreach ($this->persistedSongs as $song) {
                if ($responseSong['id'] == $song['id']) {
                    Assert::assertEquals(
                        $responseSong['id'],
                        $song['id']
                    );

                    Assert::assertEquals(
                        $responseSong['title'],
                        $song['title']
                    );

                    Assert::assertEquals(
                        $responseSong['is_visible'],
                        $song['visibility']
                    );

                    $count++;
                }
            }
        }

        Assert::assertEquals(
            $count,
            count($responseSongs)
        );
    }

    /**
     * @Then the api must return an error response with code: :arg1
     */
    public function theApiMustReturnAnErrorResponseWithCode($arg1)
    {
        Assert::assertEquals(
            $arg1,
            $this->responseCode
        );
    }

    /**
     * @Then the api must not return any song when I request all the stored songs
     */
    public function theApiMustNotReturnAnySongWhenIRequestAllTheStoredSongs()
    {
        $this->expectedCode = 200;

        $result = $this->request(
            'get',
            $this->apiUrl . '/songs'
        );

        $this->checkMultipleSongs($result, 0);
    }

    /**
     * @Given the following song exists:
     */
    public function theFollowingSongExists(TableNode $table)
    {
        $this->setSongsFromTableNode($table);
        $this->requestSongCreation();
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
        $this->expectedCode = 200;

        $options = [
            'form_params' => [
                'title' => $this->songs[0]['title'],
                'visibility' => $this->songs[0]['visibility'],
            ]
        ];

        $this->request(
            'patch',
            $this->apiUrl . '/song/' . $this->songs[0]['id'],
            $options
        );

        $this->persistedSongs = $this->songs;
    }

    /**
     * @Then /^the song should be updated$/
     */
    public function theSongShouldBeUpdated()
    {
        $this->checkSong($this->persistedSongs[0]);
    }

    /**
     * @param TableNode $table
     */
    private function setSongsFromTableNode(TableNode $table): void
    {
        $this->resetSongs();
        $this->expectedCode = 201;

        foreach ($table as $row) {
            $song = [];
            if (isset($row['id'])) {
                $song['id'] = $row['id'];
            }
            if (isset($row['title'])) {
                $song['title'] = $row['title'];
            }

            $song['visibility'] = isset($row['visibility']) ? $row['visibility'] : 1;

            if (!isset($row['id']) || !isset($row['title'])) {
                $this->expectedCode = 500;
            }

            $this->songs[] = $song;
        }
    }

    private function requestSongCreation(): void
    {
        foreach ($this->songs as $song) {
            $params = [];

            if (isset($song['id'])) {
                $params['id'] = $song['id'];
            }
            if (isset($song['title'])) {
                $params['title'] = $song['title'];
            }
            if (isset($song['visibility'])) {
                $params['visibility'] = $song['visibility'];
            }

            $options = ['form_params' => $params];

            $this->request(
                'post',
                $this->apiUrl . '/song',
                $options
            );
        }

        $this->persistSongs();
    }

    private function resetSongs()
    {
        $this->songs = [];
    }

    /**
     * @param TableNode $table
     */
    private function validateSongsToBePersisted(TableNode $table): void
    {
        foreach ($table as $row) {
            foreach ($this->persistedSongs as $key => $persistedSong) {
                if ($persistedSong['id'] == $row['id'] || $persistedSong['title'] == $row['title']) {
                    $this->expectedCode = 409;
                    break 2;
                }
            }
        }
    }

    private function persistSongs(): void
    {
        $this->persistedSongs = array_merge($this->persistedSongs, $this->songs);
    }
}
