<?php

use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;

class BaseContext extends RawMinkContext
{
    /**
     * @var array
     */
    protected static $songs = [];

    /**
     * @var array
     */
    protected static $persistedSongs = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var int
     */
    protected static $expectedCode = 200;

    /**
     * @var int
     */
    protected static $responseCode = 200;

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
     * @param string $verb
     * @param string $url
     * @param array $options
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(string $verb, string $url, array $options = [])
    {
        try {
            $result = $this->client->request($verb, $url, $options);
            self::$responseCode = $result->getStatusCode();
            $result = (string) $result->getBody();
        } catch (RuntimeException $e) {
            self::$responseCode = $e->getResponse()->getStatusCode();
            $result = '';
        }

        Assert::assertEquals(
            self::$expectedCode,
            self::$responseCode
        );

        return $result;
    }

    /**
     * @param array $song
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function checkSong(array $song): void
    {
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
     * @param string $response
     * @param int $songsCount
     */
    protected function checkMultipleSongs(string $response, int $songsCount): void
    {
        Assert::assertJson($response);

        $responseSongs = json_decode($response, true);

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

            foreach (self::$persistedSongs as $song) {
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
     * @param TableNode $table
     */
    protected function setSongsFromTableNode(TableNode $table): void
    {
        self::$expectedCode = 201;
        self::$songs = $this->getSongsFromTableNode($table);
    }

    /**
     * @param TableNode $table
     * @return array
     */
    protected function getSongsFromTableNode(TableNode $table): array
    {
        $songs = [];

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
                self::$expectedCode = 500;
            }

            $songs[] = $song;
        }

        return $songs;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function requestSongCreation(): void
    {
        foreach (self::$songs as $song) {
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

        if (self::$responseCode == 201) {
            $this->persistSongs();
        }
    }

    /**
     * @param TableNode $table
     */
    protected function validateSongsToBePersisted(TableNode $table): void
    {
        foreach ($table as $row) {
            foreach (self::$persistedSongs as $key => $persistedSong) {
                if ($persistedSong['id'] == $row['id'] || $persistedSong['title'] == $row['title']) {
                    self::$expectedCode = 409;
                    return;
                }
            }
        }
    }
    protected function persistSongs(): void
    {
        self::$persistedSongs = array_merge(self::$persistedSongs, self::$songs);
    }

    public static function resetSongs(): void
    {
        self::$songs = [];
    }

    public static function resetPersistedSongs(): void
    {
        self::$persistedSongs = [];
    }

    public static function resetCodes(): void
    {
        self::$expectedCode = 200;
        self::$responseCode = 200;
    }
}