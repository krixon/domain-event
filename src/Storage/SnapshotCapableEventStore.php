<?php

namespace Krixon\DomainEvent\Storage;

use Krixon\DomainEvent\Sourcing\EventStreamId;
use Krixon\DomainEvent\Sourcing\Snapshot;

interface SnapshotCapableEventStore
{
    /**
     * @param EventStreamId $eventStreamId
     * @param Snapshot      $snapshot
     *
     * @return void
     */
    public function addSnapshot(EventStreamId $eventStreamId, Snapshot $snapshot);
    
    
    /**
     * @param EventStreamId $eventStreamId
     *
     * @return Snapshot
     */
    public function latestSnapshot(EventStreamId $eventStreamId);
}
