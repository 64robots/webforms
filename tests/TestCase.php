<?php

namespace R64\Webforms\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use R64\Webforms\WebformsServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
        Config::set('webforms.user_model', 'R64\Webforms\Tests\Feature\Models\User');
        Config::set('app.key', 'base64:7j5abcyXMlpbac9JXHbF6zC2kKuuBTfIjHvZ0ry5uVo=');

        $this->withFactories(__DIR__.'/database/factories');

        Route::webforms('webforms');
    }

    protected function getPackageProviders($app)
    {
        return [
            WebformsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Schema::dropAllTables();

        include_once __DIR__.'/database/migrations/create_fake_webforms_tables.php.stub';
        include_once __DIR__.'/../database/migrations/create_webforms_tables.php.stub';
        (new \CreateFakeWebformsTables())->up();
        (new \CreateWebformsTables())->up();
    }
}
