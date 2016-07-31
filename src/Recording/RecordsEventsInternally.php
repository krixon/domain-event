<?php

namespace Krixon\DomainEvent\Recording;

use Krixon\DomainEvent\Event;

/**
 * Provides an implementation for classes which record domain events in an internal property.
 * 
 * The provides an implementation of @see EventRecorder.
 */
trait RecordsEventsInternally
{
    private $recordedEvents = [];
    
    
    /**
     * @see EventRecorder
     */
    public function recordedEvents()
    {
        return $this->recordedEvents;
    }
    
    
    /**
     * @see EventRecorder
     */
    public function eraseRecordedEvents()
    {
        $this->recordedEvents = [];
    }
    
    
    /**
     * Records a new domain event.
     * 
     * @param Event $event
     *
     * @return $this
     */
    protected function recordEvent(Event $event)
    {
        $this->recordedEvents[] = $event;
        
        return $this;
    }
}
