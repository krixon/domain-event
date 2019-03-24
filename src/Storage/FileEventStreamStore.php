<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage;

use Krixon\DomainEvent\Hydration\EventHydrator;
use Krixon\DomainEvent\Sourcing\EventStream;
use Krixon\DomainEvent\Sourcing\EventStreamId;
use Krixon\DomainEvent\Storage\Exception\EventStreamNotFound;

/**
 * Stores events in the filesystem for quick prototyping.
 */
class FileEventStreamStore implements EventStreamStore
{
    private $hydrator;
    private $storageDirectory;

    public function __construct(EventHydrator $hydrator, string $storageDirectory)
    {
        $this->hydrator         = $hydrator;
        $this->storageDirectory = $storageDirectory;
    }

    public function eventStream(EventStreamId $eventStreamId) : EventStream
    {
        // TODO: Implement eventStream() method.
    }

    public function eventStreamSinceEventNumber(EventStreamId $eventStreamId, int $eventNumber) : EventStream
    {
        // TODO: Implement eventStreamSinceEventNumber() method.
    }

    public function appendEvents(EventStream $eventStream) : void
    {
        foreach ($eventStream->events() as $event) {

            $data = $this->hydrator->dehydrate($event);

            // json_encode data
            // store json encoded data in steam specific file
            // Files can be named by stream id?
        }
    }

    public function purge() : void
    {
        // TODO: Implement purge() method.
    }
}