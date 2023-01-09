<?php

namespace AliReaza\Laravel\MessageBus\MessageSourcing\Commands;

use AliReaza\Laravel\MessageBus\MessageSourcing\Handlers\StoreMessageToDatabaseHandler;
use AliReaza\Laravel\MessageBus\Kafka\Commands\KafkaMessagesHandlerCommand;
use AliReaza\MessageBus\Message;
use AliReaza\MessageBus\MessageHandlerInterface;
use AliReaza\MessageBus\MessageInterface;

class StoreMessageFromKafkaCommand extends KafkaMessagesHandlerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message-bus:kafka-store-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store messages from Apache Kafka to database.';

    protected function addInputTopic(): void
    {
    }

    protected function getTopic(): string
    {
        return '^[A-Za-z]+.*';
    }

    protected function addInputPartition(): void
    {
    }

    protected function getPartitions(): ?array
    {
        return null;
    }

    protected function handleMessage(MessageHandlerInterface $message_handler): void
    {
        $message = $this->getMessage();

        $handlers = $this->getHandlers($message);

        foreach ($handlers as $handler) {
            $message_handler->addHandler($message, $handler);
        }

        $message_handler->handle($message);
    }

    private function getMessage(): MessageInterface
    {
        $name = $this->getTopic();

        return new Message(name: $name);
    }

    protected function getHandlers(MessageInterface $message): iterable
    {
        if (empty($message->getName())) {
            return [];
        }

        return [new StoreMessageToDatabaseHandler()];
    }
}
