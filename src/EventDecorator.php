<?php

declare(strict_types=1);

namespace Krixon\DomainEvent;

use Krixon\DateTime\DateTime;

abstract class EventDecorator implements Event
{
    private $wrapped;

    public function __construct(Event $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    public function occurredOn() : DateTime
    {
        return $this->wrapped->occurredOn();
    }


    public function eventVersion() : int
    {
        return $this->wrapped->eventVersion();
    }


    public function eventType() : string
    {
        return $this->wrapped->eventType();
    }
}
