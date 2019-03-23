<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage;

use Krixon\DomainEvent\Sourcing\EventStream;
use Krixon\DomainEvent\Sourcing\EventStreamId;
use Krixon\DomainEvent\Storage\Exception\EventStreamNotFound;

interface EventStreamStore
{
    /**
     * @throws EventStreamNotFound If the stream does not exist.
     */
    public function eventStream(EventStreamId $eventStreamId) : EventStream;


    /**
     * @throws EventStreamNotFound If the stream or event number does not exist.
     */
    public function eventStreamSinceEventNumber(EventStreamId $eventStreamId, int $eventNumber) : EventStream;


    public function appendEvents(EventStream $eventStream) : void;


    public function purge() : void;
}
