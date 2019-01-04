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
     * @var array
     */
    protected static $setlists = [];

    /**
     * @var array
     */
    protected static $persistedSetlists = [];

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
            //throw new Exception($e->getMessage());
            self::$responseCode = $e->getResponse()->getStatusCode();
            $result = '';
        }

//        Assert::assertEquals(
//            self::$expectedCode,
//            self::$responseCode
//        );

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

        Assert::assertEquals(
            200,
            self::$responseCode
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
     * @param array $setlist
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function checkSetlist(array $setlist): void
    {
        $result = $this->request(
            'get',
            $this->apiUrl . '/setlist/' . $setlist['id']
        );

        Assert::assertEquals(
            200,
            self::$responseCode
        );

        Assert::assertJson($result);

        $responseSetlist = json_decode($result, true);

        Assert::assertArrayHasKey('id', $responseSetlist);
        Assert::assertArrayHasKey('name', $responseSetlist);
        Assert::assertArrayHasKey('description', $responseSetlist);
        Assert::assertArrayHasKey('date', $responseSetlist);
        Assert::assertArrayHasKey('creation_date', $responseSetlist);
        Assert::assertArrayHasKey('update_date', $responseSetlist);
        Assert::assertArrayHasKey('acts', $responseSetlist);

        Assert::assertEquals(
            $responseSetlist['id'],
            $setlist['id']
        );

        Assert::assertEquals(
            $responseSetlist['name'],
            $setlist['name']
        );

        Assert::assertEquals(
            $responseSetlist['description'],
            $setlist['description'] ?? ''
        );

        Assert::assertEquals(
            $responseSetlist['date'],
            $setlist['date']
        );

        $keyAct = 0;
        foreach ($setlist['acts'] as $act) {
            $keySong = 0;
            foreach ($act as $song) {
                Assert::assertEquals(
                    $song['id'],
                    $responseSetlist['acts'][$keyAct][$keySong]['id']
                );
                Assert::assertEquals(
                    $song['title'],
                    $responseSetlist['acts'][$keyAct][$keySong]['title']
                );
                $keySong++;
            }
            $keyAct++;
        }
    }

    /**
     * @param string $response
     * @param int $setlistsCount
     */
    protected function checkMultipleSetlists(string $response, int $setlistsCount): void
    {
        Assert::assertJson($response);

        $responseSetlists = json_decode($response, true);

        Assert::assertEquals(
            $setlistsCount,
            count($responseSetlists)
        );

        $count = 0;
        foreach ($responseSetlists as $responseSetlist) {

            Assert::assertArrayHasKey('id', $responseSetlist);
            Assert::assertArrayHasKey('name', $responseSetlist);
            Assert::assertArrayHasKey('description', $responseSetlist);
            Assert::assertArrayHasKey('date', $responseSetlist);
            Assert::assertArrayHasKey('creation_date', $responseSetlist);
            Assert::assertArrayHasKey('update_date', $responseSetlist);
            Assert::assertArrayHasKey('acts', $responseSetlist);

            foreach (self::$persistedSetlists as $setlist) {
                if ($responseSetlist['id'] == $setlist['id']) {
                    Assert::assertEquals(
                        $responseSetlist['id'],
                        $setlist['id']
                    );

                    Assert::assertEquals(
                        $responseSetlist['name'],
                        $setlist['name']
                    );

                    Assert::assertEquals(
                        $responseSetlist['description'],
                        $setlist['description'] ?? ''
                    );

                    Assert::assertEquals(
                        $responseSetlist['date'],
                        $setlist['date']
                    );

                    $keyAct = 0;
                    foreach ($setlist['acts'] as $act) {
                        $keySong = 0;
                        foreach ($act as $song) {
                            Assert::assertEquals(
                                $song['id'],
                                $responseSetlist['acts'][$keyAct][$keySong]['id']
                            );
                            Assert::assertEquals(
                                $song['title'],
                                $responseSetlist['acts'][$keyAct][$keySong]['title']
                            );
                            $keySong++;
                        }
                        $keyAct++;
                    }

                    $count++;
                }
            }
        }

        Assert::assertEquals(
            $count,
            count($responseSetlists)
        );
    }

    /**
     * @param TableNode $table
     */
    protected function setSongsFromTableNode(TableNode $table): void
    {
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

            $songs[] = $song;
        }

        return $songs;
    }

    /**
     * @param TableNode $table
     */
    protected function setSetlistsFromTableNode(TableNode $table): void
    {
        self::$setlists = $this->getSetlistsFromTableNode($table);
    }

    /**
     * @param TableNode $table
     * @return array
     */
    protected function getSetlistsFromTableNode(TableNode $table): array
    {
        $setlists = [];

        foreach ($table as $row) {
            $setlist = [];
            if (isset($row['id'])) {
                $setlist['id'] = $row['id'];
            }
            if (isset($row['name'])) {
                $setlist['name'] = $row['name'];
            }
            if (isset($row['description'])) {
                $setlist['description'] = $row['description'];
            }
            if (isset($row['date'])) {
                $setlist['date'] = $row['date'];
            }

            $setlists[] = $setlist;
        }

        return $setlists;
    }

    /**
     * @param string $setlistId
     * @param int $actKey
     * @param array $act
     */
    protected function addActToSetlist(string $setlistId, int $actKey, array $act)
    {
        foreach (self::$setlists as $setlistKey => $setlist) {
            if ($setlist['id'] == $setlistId) {
                self::$setlists[$setlistKey]['acts'][$actKey] = $act;
            }
        }
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

            if (self::$responseCode == 201) {
                $this->persistSong($song);
            }
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function requestSetlistCreation(): void
    {
        foreach (self::$setlists as $setlist) {
            $params = [];

            if (isset($setlist['id'])) {
                $params['id'] = $setlist['id'];
            }
            if (isset($setlist['name'])) {
                $params['name'] = $setlist['name'];
            }
            if (isset($setlist['date'])) {
                $params['date'] = $setlist['date'];
            }
            if (isset($setlist['description'])) {
                $params['description'] = $setlist['description'];
            }
            if (isset($setlist['acts'])) {
                foreach ($setlist['acts'] as $actKey => $act) {
                    foreach ($act as $songKey => $song) {
                        $params["acts[$actKey][$songKey]"] = $song['id'];
                    }
                }
            }

            $options = ['form_params' => $params];

            $this->request(
                'post',
                $this->apiUrl . '/setlist',
                $options
            );

            if (self::$responseCode == 201) {
                $this->persistSetlist($setlist);
            }
        }
    }

    /**
     * @param array $setlist
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function requestSetlistUpdate(array $setlist): void
    {
        $params = [];
        $params['id'] = $setlist['id'];
        if (isset($setlist['name'])) {
            $params['name'] = $setlist['name'];
        }
        if (isset($setlist['date'])) {
            $params['date'] = $setlist['date'];
        }
        if (isset($setlist['description'])) {
            $params['description'] = $setlist['description'];
        }
        if (isset($setlist['acts'])) {
            foreach ($setlist['acts'] as $actKey => $act) {
                foreach ($act as $songKey => $song) {
                    $params["acts[$actKey][$songKey]"] = $song['id'];
                }
            }
        }

        $options = ['form_params' => $params];


        $this->request(
            'patch',
            $this->apiUrl . '/setlist/' . $setlist['id'],
            $options
        );

        if (self::$responseCode == 200) {
            $this->updateSetlist($setlist);
        }
    }

    /**
     * @param string $setlistId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function requestSetlistDelete(string $setlistId): void
    {
        $this->request(
            'delete',
            $this->apiUrl . '/setlist/' . $setlistId
        );

        if (self::$responseCode == 200) {
            $this->deleteSetlist($setlistId);
        }
    }

    /**
     * @param array $song
     * @return bool
     */
    protected function checkSongExistence(array $song)
    {
        foreach (self::$persistedSongs as $persistedSong) {
            if ($song['id'] == $persistedSong['id']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $song
     */
    protected function persistSong(array $song): void
    {
        self::$persistedSongs[] = $song;
    }

    /**
     * @param array $setlist
     */
    protected function persistSetlist(array $setlist): void
    {
        self::$persistedSetlists[] = $setlist;
    }

    /**
     * @param array $setlist
     */
    protected function updateSetlist(array $setlist): void
    {
        foreach (self::$persistedSetlists as $persistedSetlistKey => $persistedSetlist) {
            if ($persistedSetlist['id'] == $setlist['id']) {
                self::$persistedSetlists[$persistedSetlistKey] = $setlist;
                break;
            }
        }
    }

    /**
     * @param string $setlistId
     */
    protected function deleteSetlist(string $setlistId): void
    {
        foreach (self::$persistedSetlists as $persistedSetlistKey => $persistedSetlist) {
            if ($persistedSetlist['id'] == $setlistId) {
                unset(self::$persistedSetlists[$persistedSetlistKey]);
                break;
            }
        }
    }

    protected function persistSetlists(): void
    {
        self::$persistedSetlists = array_merge(self::$persistedSetlists, self::$setlists);
    }

    public static function resetSongs(): void
    {
        self::$songs = [];
    }

    public static function resetPersistedSongs(): void
    {
        self::$persistedSongs = [];
    }

    public static function resetSetlists(): void
    {
        self::$setlists = [];
    }

    public static function resetPersistedSetlists(): void
    {
        self::$persistedSetlists = [];
    }

    public static function resetCodes(): void
    {
        self::$responseCode = 200;
    }
}
