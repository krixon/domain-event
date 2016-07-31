<?php

namespace Krixon\DomainEvent\Recording;

use Krixon\DomainEvent\Event;

interface EventRecorder
{
    /**
     * Records a new domain event.
     * 
     * @param Event $domainEvent
     *
     * @return void
     */
    public function record(Event $domainEvent);
}
