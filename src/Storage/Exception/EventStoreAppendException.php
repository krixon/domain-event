<?php

namespace Krixon\DomainEvent\Storage\Exception;

class EventStoreAppendException extends EventStoreException
{
    /**
     * @inheritdoc
     */
    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
