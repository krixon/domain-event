<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Recording;

use Krixon\DomainEvent\Event;

/**
 * Implementors record domain events for future retrieval.
 */
interface RecordedEventContainer
{
    /**
     * @return Event[]
     */
    public function recordedEvents() : array;


    public function eraseRecordedEvents() : void;
}
