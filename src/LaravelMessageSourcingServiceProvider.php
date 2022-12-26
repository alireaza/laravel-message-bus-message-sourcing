<?php

namespace AliReaza\Laravel\Gateway\Message\Sourcing;

use Illuminate\Support\ServiceProvider;
use AliReaza\Laravel\Gateway\Message\Sourcing\Commands\StoreMessageFromKafkaCommand;

class LaravelMessageSourcingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . DIRECTORY_SEPARATOR . 'Migrations');

        $this->commands(StoreMessageFromKafkaCommand::class);
    }
}
