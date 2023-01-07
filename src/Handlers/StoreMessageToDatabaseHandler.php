<?php

namespace AliReaza\Laravel\Gateway\Message\Sourcing\Handlers;

use AliReaza\Laravel\Gateway\Message\Sourcing\Models\StoredMessage;
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
