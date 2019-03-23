<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage;

use Krixon\DomainEvent\Sourcing\EventStreamId;
use Krixon\DomainEvent\Sourcing\Snapshot;

interface SnapshotCapableEventStore
{
    public function addSnapshot(EventStreamId $eventStreamId, Snapshot $snapshot) : void;


    public function latestSnapshot(EventStreamId $eventStreamId) : Snapshot;
}
