<?php

namespace AliReaza\Laravel\MessageBus\MessageSourcing\Handlers;

use AliReaza\Laravel\MessageBus\MessageSourcing\Models\StoredMessage;
use AliReaza\MessageBus\HandlerInterface;
use AliReaza\MessageBus\MessageInterface;

class StoreMessageToDatabaseHandler implements HandlerInterface
{
    public function __invoke(MessageInterface $message): void
    {
        StoredMessage::insertOrIgnore([
            'message_id' => $message->getMessageId(),
            'causation_id' => $message->getCausationId(),
            'correlation_id' => $message->getCorrelationId(),
            'name' => $message->getName(),
            'content' => $message->getContent(),
            'timestamp' => $message->getTimestamp(),
        ]);
    }
}
