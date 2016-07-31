<?php

namespace Krixon\DomainEvent\Recording;

use Krixon\DomainEvent\Event;

/**
 * Implementors record domain events for future retrieval.
 */
interface RecordedEventContainer
{
    /**
     * Returns all recorded domain events.
     * 
     * @return Event[]
     */
    public function recordedEvents();
    
    
    /**
     * Erases the internal domain event record.
     * 
     * @return void
     */
    public function eraseRecordedEvents();
}
