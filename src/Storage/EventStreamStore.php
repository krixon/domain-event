<?php

namespace Krixon\DomainEvent\Storage;

use Krixon\DomainEvent\Sourcing\EventStream;
use Krixon\DomainEvent\Sourcing\EventStreamId;
use Krixon\DomainEvent\Storage\Exception\EventStreamNotFoundException;

interface EventStreamStore
{
    /**
     * @param EventStreamId $eventStreamId
     *
     * @return EventStream
     * @throws EventStreamNotFoundException If the stream does not exist.
     */
    public function eventStream(EventStreamId $eventStreamId) : EventStream;
    
    
    /**
     * @param EventStreamId $eventStreamId
     * @param int           $eventNumber
     *
     * @return EventStream
     * @throws EventStreamNotFoundException If the stream or event number does not exist.
     */
    public function eventStreamSinceEventNumber(EventStreamId $eventStreamId, $eventNumber) : EventStream;
    
    
    /**
     * @param EventStream $eventStream
     */
    public function appendEvents(EventStream $eventStream);
    
    
    /**
     * @return void
     */
    public function purge();
}
