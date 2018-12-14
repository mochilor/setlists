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
class FeatureContext extends MinkContext implements Context
{
    /**
     * @var array
     */
    private $songs = [];

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
        foreach ($table as $row) {
            $song = [];
            if (isset($row['id'])) {
                $song['id'] = $row['id'];
            }
            if (isset($row['title'])) {
                $song['title'] = $row['title'];
            }

            if (!isset($row['id']) || !isset($row['title'])) {
                $this->expectedCode = 500;
            } else {
                $this->expectedCode = 201;
            }

            foreach ($this->songs as $key => $storedSong) {
                if ($storedSong['id'] == $song['id'] || $storedSong['title'] == $song['title']) {
                    $this->expectedCode = 409;
                    unset($this->songs[$key]);
                }
            }

            $this->songs[] = $song;
        }
    }

    /**
     * @When I request the api service to create the songs
     */
    public function iRequestTheApiServiceToCreateTheSongs()
    {
        foreach ($this->songs as $song) {
            $params = [];

            if (isset($song['id'])) {
                $params['id'] = $song['id'];
            }
            if (isset($song['title'])) {
                $params['title'] = $song['title'];
            }

            $options = ['form_params' => $params];

            $this->request(
                'post',
                $this->apiUrl . '/song',
                $this->expectedCode,
                $options
            );
        }
    }

    /**
     * @Then the api must show me any of the songs if I request them by their id
     */
    public function theApiMustShowMeAnyOfTheSongsIfIRequestThemByTheirId()
    {
        foreach ($this->songs as $song) {
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
        }
    }

    /**
     * @Then the api must show me all the songs if I request them
     */
    public function theApiMustShowMeAllTheSongsIfIRequestThem()
    {
        $result = $this->request(
            'get',
            $this->apiUrl . '/songs'
        );

        $this->checkMultipleSongs($result, count($this->songs));
    }

    /**
     * @Then the api must be able to show me a list with songs from: :arg1 to: :arg2
     */
    public function theApiMustBeAbleToShowMeAListWithSongsFromTo($arg1, $arg2)
    {
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
        $result = $this->request(
            'get',
            $this->apiUrl . '/songs?interval=' . $arg1 . ',999'
        );

        $this->checkMultipleSongs($result, count($this->songs) - $arg1);
    }

    /**
     * @param string $verb
     * @param string $url
     * @param int $expectedCode
     * @param array $options
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $verb, string $url, int $expectedCode = 200, array $options = [])
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
            $expectedCode,
            $this->responseCode
        );

        return $result;
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

            foreach ($this->songs as $song) {
                if ($responseSong['id'] == $song['id']) {
                    Assert::assertEquals(
                        $responseSong['id'],
                        $song['id']
                    );

                    Assert::assertEquals(
                        $responseSong['title'],
                        $song['title']
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
        $this->iWantToCreateSongsWithValues($table);
        $this->iRequestTheApiServiceToCreateTheSongs();
    }
}
