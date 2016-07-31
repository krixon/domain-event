<?php

namespace Krixon\DomainEvent\Storage;

use Krixon\DomainEvent\Sourcing\EventStream;
use Krixon\DomainEvent\Sourcing\EventStreamId;
use Krixon\DomainEvent\Sourcing\Snapshot;
use Krixon\DomainEvent\Storage\Exception\EventStoreAppendException;
use Krixon\DomainEvent\Storage\Exception\EventStreamNotFoundException;

class MemoryEventStreamStore implements EventStreamStore, SnapshotCapableEventStore
{
    private $streams   = [];
    private $snapshots = [];
    
    
    /**
     * @inheritdoc
     */
    public function eventStream(EventStreamId $eventStreamId) : EventStream
    {
        $id = $eventStreamId();
        
        if (!isset($this->streams[$id])) {
            throw new EventStreamNotFoundException("Event stream '$id' does not exist.");
        }
        
        return new EventStream($eventStreamId, $this->streams[$id]);
    }
    
    
    /**
     * @inheritdoc
     */
    public function eventStreamSinceEventNumber(EventStreamId $eventStreamId, $eventNumber) : EventStream
    {
        $id = $eventStreamId();
        
        if (!isset($this->streams[$id])) {
            throw new EventStreamNotFoundException("Event stream '$id' does not exist.");
        }
        
        $key = array_search($eventNumber, array_keys($this->streams[$id]), true);
        
        if (false === $key) {
            throw new EventStreamNotFoundException(sprintf(
                'Event store does not contain an event at version %d for stream %s.',
                $eventNumber,
                $id
            ));
        }
    
        $events = array_slice($this->streams[$id], $key, null, true);
        
        return new EventStream($eventStreamId, $events, $eventNumber);
    }
    
    
    /**
     * @inheritdoc
     */
    public function appendEvents(EventStream $eventStream)
    {
        $id = (string)$eventStream->id();
        
        if (!isset($this->streams[$id])) {
            $this->streams[$id] = [];
        }
        
        // Check to see if an event has been submitted since the stream was loaded.
        // If so there is a concurrency violation which must be either resolved or raised as an error.
        $this->checkForConcurrencyViolation($eventStream->firstEventNumber(), $this->streams[$id]);
        
        foreach ($eventStream as $eventNumber => $event) {
            $this->streams[$id][$eventNumber] = $event;
        }
    }
    
    
    /**
     * @inheritdoc
     */
    public function purge()
    {
        $this->streams   = [];
        $this->snapshots = [];
    }
    
    
    /**
     * @inheritdoc
     */
    public function addSnapshot(EventStreamId $eventStreamId, Snapshot $snapshot)
    {
        // TODO: Implement addSnapshot() method.
    }
    
    
    /**
     * @inheritdoc
     */
    public function latestSnapshot(EventStreamId $eventStreamId)
    {
        // TODO: Implement latestSnapshot() method.
    }
    
    
    private function checkForConcurrencyViolation($headVersion, array &$events)
    {
        if (isset($events[$headVersion])) {
            
            // TODO: In future employ a conflict resolution mechanism here. Only certain events actually conflict with
            // each other, so we should ignore violations for others.
            // http://danielwhittaker.me/2014/09/29/handling-concurrency-issues-cqrs-event-sourced-system/
            
            // TODO: Different exception specific to concurrency violation.
            throw new EventStoreAppendException(sprintf(
                'An event with head version %d is already committed.',
                $headVersion
            ));
        }
    }
}
