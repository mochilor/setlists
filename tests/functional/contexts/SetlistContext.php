<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class SetlistContext extends BaseContext implements Context
{
    private $acts = [];
    private $updatedSetlist = [];

    /**
     * @Given /^(I want to prepare some acts with the following data|The songs are sorted in the following acts):$/
     */
    public function iWantToPrepareSomeActsWithTheFollowingData(TableNode $table)
    {
        $this->setActs($table);
    }

    /**
     * @param TableNode $table
     */
    private function setActs(TableNode $table)
    {
        $acts = [];
        foreach ($table as $row) {
            $actSong = null;
            foreach (self::$songs as $song) {
                if ($song['id'] == $row['song_id']) {
                    $actSong = $song;
                    break;
                }
            }

            if (empty($actSong)) {
                // Simulate a non existent song:
                $actSong = [
                    'id' => $row['song_id'],
                    'title' => '',
                    'visibility' => '',
                ];
            }

            $acts[$row['act_number']][$row['song_order']] = $actSong;
        }

        foreach ($acts as $actKey => $act) {
            ksort($act);
            $acts[$actKey] = array_values($act);
        }

        ksort($acts);
        $this->acts = array_values($acts);
    }

    /**
     * @Given /^I want to add the acts to some setlists with the following data:$/
     */
    public function iWantToAddTheActsToSomeSetlistsWithTheFollowingData(TableNode $table)
    {
        $this->setSetlistsFromTableNode($table);

        foreach (self::$setlists as $setlist) {
            foreach ($this->acts as $actKey => $act) {
                $this->addActToSetlist($setlist['id'], $actKey, $act);
            }
        }
    }

    /**
     * @Given /^The acts belong to (a setlist|some setlists) with the following data:$/
     */
    public function theActsBelongToASetlistWithTheFollowingData(TableNode $table)
    {
        $this->setSetlistsFromTableNode($table);

        foreach (self::$setlists as $setlist) {
            foreach ($this->acts as $actKey => $act) {
                $this->addActToSetlist($setlist['id'], $actKey, $act);
            }
        }

        $this->requestSetlistCreation();
    }

    /**
     * @When /^I request the api service to create the setlists?$/
     */
    public function iRequestTheApiServiceToCreateTheSetlists()
    {
        $this->requestSetlistCreation();
    }

    /**
     * @Then the api must show me the setlist if I request it by its id
     */
    public function theApiMustShowMeTheSetlistIfIRequestItByItsId()
    {
        Assert::assertNotEmpty(self::$persistedSetlists);

        foreach (self::$persistedSetlists as $setlist) {
            $this->checkSetlist($setlist);
        }
    }

    /**
     * @Given /^the api must show me all the setlists if I request them$/
     */
    public function theApiMustShowMeAllTheSetlistsIfIRequestThem()
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/setlists'
        );

        Assert::assertEquals(
            200,
            self::$responseCode
        );

        $this->checkMultipleSetlists($response, count(self::$persistedSetlists));
    }

    /**
     * @Given /^the api must be able to show me a list with setlists from: (\d+) to: (\d+)$/
     */
    public function theApiMustBeAbleToShowMeAListWithSetlistsFromTo($arg1, $arg2)
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/setlists?interval=' . $arg1 . ',' . $arg2
        );

        Assert::assertEquals(
            200,
            self::$responseCode
        );

        $this->checkMultipleSetlists($response, $arg2 - $arg1);
    }

    /**
     * @Then the api must be able to show me a list with setlists from: :arg1 to the end
     */
    public function theApiMustBeAbleToShowMeAListWithSetlistsFromToTheEnd($arg1)
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/setlists?interval=' . $arg1 . ',999'
        );

        Assert::assertEquals(
            200,
            self::$responseCode
        );

        $this->checkMultipleSetlists($response, count(self::$persistedSetlists) - $arg1);
    }

    /**
     * @Given /^the api must not return any setlist when I request all the stored setlists$/
     */
    public function theApiMustNotReturnAnySetlistWhenIRequestAllTheStoredSetlists()
    {
        $response = $this->request(
            'get',
            $this->apiUrl . '/setlists'
        );

        $this->checkMultipleSetlists($response, 0);
    }

    /**
     * @Given /^I want to create an empty setlist with the following data:$/
     */
    public function iWantToCreateAnEmptySetlistWithTheFollowingData(TableNode $table)
    {
        $this->setSetlistsFromTableNode($table);
    }

    /**
     * @Given /^no Setlist exist$/
     */
    public function noSetlistExist()
    {
        //
    }

    /**
     * @When I request the api to show me the setlist with id: :arg1
     */
    public function iRequestTheApiToShowMeTheSetlistWithId($arg1)
    {
        $this->request(
            'get',
            $this->apiUrl . '/setlist/' . $arg1
        );
    }

    /**
     * @Given /^I want to update the setlist with the following data:$/
     */
    public function iWantToUpdateTheSetlistWithTheFollowingData(TableNode $table)
    {
        $this->updatedSetlist = $this->getSetlistsFromTableNode($table)[0];
        $this->updatedSetlist['acts'] = $this->acts;
    }

    /**
     * @When /^I request the api service to update the setlist$/
     */
    public function iRequestTheApiServiceToUpdateTheSetlist()
    {
        $this->requestSetlistUpdate($this->updatedSetlist);
    }

    /**
     * @Given I want to update the acts for the first setlist with the following data:
     */
    public function iWantToUpdateTheActsForTheFirstSetlistWithTheFollowingData(TableNode $table)
    {
        $this->setActs($table);

        unset(self::$setlists[0]['acts']);

        foreach ($this->acts as $actKey => $act) {
            $this->addActToSetlist(self::$setlists[0]['id'], $actKey, $act);
        }

        $this->updatedSetlist = self::$setlists[0];
    }

    /**
     * @Given /^I want to update the acts for the first setlist with empty data$/
     */
    public function iWantToUpdateTheActsForTheFirstSetlistWithEmptyData()
    {
        unset(self::$setlists[0]['acts']);

        $this->updatedSetlist = self::$setlists[0];
    }

    /**
     * @When I request the api service to delete the Setlist with id: :arg1
     */
    public function iRequestTheApiServiceToDeleteTheSetlistWithId($arg1)
    {
        $this->requestSetlistDelete($arg1);
    }
}
