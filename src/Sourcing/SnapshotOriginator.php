<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Sourcing;

/**
 * Classes implementing this are "originators" in the memento pattern.
 *
 * The support having a snapshot of their state taken and restored. This is a useful optimisation when event sourcing
 * where there might be many events in a stream. Rather than replaying each event every time an aggregate is loaded,
 * a snapshot can be used to quickly initialiseFromEventStream a recent state so that fewer events need to be replayed.
 *
 * Snapshots supplement rather than replace event streams. The full event stream is the canonical source of state.
 * Snapshots are a performance optimisation only. It should always be possible to discard all snapshots and use just
 * the event stream to fully restore an aggregate's state.
 */
interface SnapshotOriginator
{
    public function snapshot() : Snapshot;


    public function applySnapshot(Snapshot $snapshot) : void;
}
