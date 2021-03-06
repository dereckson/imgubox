<?php

namespace ImguBox\Tests\Integrated\ConsoleCommands;

use ImguBox\Tests\TestCase;
use ImguBox\Tests\Support\FactoryTools;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use ImguBox\Jobs\FetchImages;
use Artisan;

class FetchFavoritesCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;
    use FactoryTools;

    public function test_fetch_favs_fires_job()
    {
        $this->setupUsers();

        $this->expectsJobs(FetchImages::class);

        Artisan::call('imgubox:fetchFavs');
    }
}
