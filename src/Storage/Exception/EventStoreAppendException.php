<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage\Exception;

/**
 * Thrown by an event store when it cannot appended a given event to the store.
 */
class EventStoreAppendException extends \RuntimeException implements EventStoreException
{
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}