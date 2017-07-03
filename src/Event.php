<?php

namespace Krixon\DomainEvent;

use Krixon\DateTime\DateTime;

interface Event
{
    /**
     * The time at which the event occurred.
     *
     * @return DateTime
     */
    public function occurredOn() : DateTime;


    /**
     * The version of the event.
     *
     * This should generally start at 0 and be bumped each time the structure of an event's payload changes. This
     * allows code processing the event to adapt accordingly.
     *
     * @return int
     */
    public function eventVersion() : int;


    /**
     * The type of event.
     *
     * This is a string which uniquely identifies the type of event. The fully-qualified class name would be a good
     * value for this, but it can be anything.
     *
     * @return string
     */
    public function eventType() : string;
}
