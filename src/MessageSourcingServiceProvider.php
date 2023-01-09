<?php

namespace AliReaza\Laravel\MessageBus\MessageSourcing;

use Illuminate\Support\ServiceProvider;
use AliReaza\Laravel\MessageBus\MessageSourcing\Commands\StoreMessageFromKafkaCommand;

class MessageSourcingServiceProvider extends ServiceProvider
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
