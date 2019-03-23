<?php

declare(strict_types=1);

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
     *
     * @return Event[]
     */
    public function recordedEvents() : array
    {
        return $this->recordedEvents;
    }


    /**
     * @see EventRecorder
     */
    public function eraseRecordedEvents() : void
    {
        $this->recordedEvents = [];
    }


    protected function recordEvent(Event $event) : void
    {
        $this->recordedEvents[] = $event;
    }
}
