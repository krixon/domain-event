<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Sourcing\EventStream;
use Krixon\DomainEvent\Sourcing\EventStreamId;
use Krixon\DomainEvent\Sourcing\Snapshot;
use Krixon\DomainEvent\Storage\Exception\EventStoreConcurrencyViolation;
use Krixon\DomainEvent\Storage\Exception\EventStreamNotFound;
use LogicException;
use function array_keys;
use function array_search;
use function array_slice;
use function sprintf;

class MemoryEventStreamStore implements EventStreamStore, SnapshotCapableEventStore
{
    private $streams   = [];
    private $snapshots = [];


    public function eventStream(EventStreamId $eventStreamId) : EventStream
    {
        $id = $eventStreamId();

        if (!isset($this->streams[$id])) {
            throw new EventStreamNotFound(sprintf("Event stream '%s' does not exist.", $id));
        }

        return new EventStream($eventStreamId, $this->streams[$id]);
    }


    public function eventStreamSinceEventNumber(EventStreamId $eventStreamId, int $eventNumber) : EventStream
    {
        $id = $eventStreamId();

        if (!isset($this->streams[$id])) {
            throw new EventStreamNotFound(sprintf("Event stream '%s' does not exist.", $id));
        }

        $key = array_search($eventNumber, array_keys($this->streams[$id]), true);

        if ($key === false) {
            throw new EventStreamNotFound(sprintf(
                'Event store does not contain an event at version %d for stream %s.',
                $eventNumber,
                $id
            ));
        }

        $events = array_slice($this->streams[$id], $key, null, true);

        return new EventStream($eventStreamId, $events, $eventNumber);
    }


    public function appendEvents(EventStream $eventStream) : void
    {
        $id = $eventStream->id()->id();

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


    public function purge() : void
    {
        $this->streams   = [];
        $this->snapshots = [];
    }


    public function addSnapshot(EventStreamId $eventStreamId, Snapshot $snapshot) : void
    {
        throw new LogicException('Add snapshot has not been implemented');
    }


    public function latestSnapshot(EventStreamId $eventStreamId) : Snapshot
    {
        throw new LogicException('Latest snapshot has not been implemented');
    }


    /**
     * @param Event[] $events
     */
    private function checkForConcurrencyViolation(int $headVersion, array &$events) : void
    {
        if (isset($events[$headVersion])) {
            // TODO: In future employ a conflict resolution mechanism here. Only certain events actually conflict with
            // each other, so we should ignore violations for others.
            // http://danielwhittaker.me/2014/09/29/handling-concurrency-issues-cqrs-event-sourced-system/

            // TODO: Different exception specific to concurrency violation.
            throw new EventStoreConcurrencyViolation(sprintf(
                'An event with head version %d is already committed.',
                $headVersion
            ));
        }
    }
}
