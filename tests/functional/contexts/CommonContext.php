<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Illuminate\Support\Facades\Artisan;

class CommonContext extends RawMinkContext implements Context
{
    /**
     * @static
     * @BeforeScenario
     */
    public static function prepareData()
    {
        self::resetDatabase();
        self::resetAttributes();
    }

    private static function resetDatabase()
    {

        echo "Preparing database...\n";
        Artisan::call('migrate:fresh');
    }

    private static function resetAttributes()
    {
        echo "Resetting Context attributes...\n";
        BaseContext::resetSongs();
        BaseContext::resetPersistedSongs();
        BaseContext::resetSetlists();
        BaseContext::resetPersistedSetlists();
        BaseContext::resetCodes();
    }
}
