<?php

namespace Krixon\DomainEvent;

use Krixon\DateTime\DateTime;

abstract class EventDecorator implements Event
{
    /**
     * @var Event
     */
    private $wrapped;


    /**
     * @param Event $wrapped
     */
    public function __construct(Event $wrapped)
    {
        $this->wrapped = $wrapped;
    }


    /**
     * @inheritdoc
     */
    public function occurredOn() : DateTime
    {
        return $this->wrapped->occurredOn();
    }


    /**
     * @inheritdoc
     */
    public function eventVersion() : int
    {
        return $this->wrapped->eventVersion();
    }
}
