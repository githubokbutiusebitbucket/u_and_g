<?php

namespace Tests;

use App\Exceptions\Handler;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use Tests\MigrateAndSeedDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MigrateAndSeedDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    /**
     * https://gist.github.com/adamwathan/125847c7e3f16b88fa33a9f8b42333da
     * @return [type] [description]
     */
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }

            public function report(\Exception $e)
            {
            }

            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }

    protected function signIn($user = null)
    {
        $user = $user ?: factory(User::class)->create();
        // $this->actingAs($user);
        Passport::actingAs($user);

        return $this;
    }
}
