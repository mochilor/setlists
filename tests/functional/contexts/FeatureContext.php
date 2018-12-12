<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * @var string
     */
    private $songTitle;

    /**
     * @var string
     */
    private $songId;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var bool
     */
    private static $readyDB = false;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->prepareDB();
        $this->client = new Client();
        $this->apiUrl = env('API_URL');
    }

    private function prepareDB()
    {
        if (!self::$readyDB) {
            echo "Preparing database...\n\n";
            Artisan::call('migrate:fresh');
            self::$readyDB = true;
        }
    }

    /**
     * @static
     * @beforeSuite
     */
    public static function bootstrapLaravel()
    {
        // Example!
    }

    /**
     * @Given I want to create a song with title :arg1 and id :arg2
     */
    public function iWantToCreateASongWithTitleAndId($arg1, $arg2)
    {
        $this->songTitle = $arg1;
        $this->songId = $arg2;
    }

    /**
     * @When I request the api service to create the song
     */
    public function iRequestTheApiServiceToCreateTheSong()
    {
        $options = [
            'form_params' => [
                'id' => $this->songId,
                'title' => $this->songTitle,
            ],
        ];

        $this->request('post', $this->apiUrl . '/song', $options);
    }

    /**
     * @Then the song should have been created
     */
    public function theSongShouldHaveBeenCreated()
    {
        $result = $this->request('get', $this->apiUrl . '/song/' . $this->songId);

        Assert::assertJson($result);

        $song = json_decode($result, true);

        Assert::assertArrayHasKey('id', $song);
        Assert::assertArrayHasKey('title', $song);
        Assert::assertArrayHasKey('is_visible', $song);
        Assert::assertArrayHasKey('creation_date', $song);
        Assert::assertArrayHasKey('update_date', $song);

        Assert::assertEquals(
            $song['id'],
            $this->songId
        );

        Assert::assertEquals(
            $song['title'],
            $this->songTitle
        );
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
        $result = $this->client->request($verb, $url, $options);

        return (string) $result->getBody();
    }
}
