<?php

namespace Krixon\DomainEvent;

use Krixon\DateTime\DateTime;

abstract class BaseEvent implements Event
{
    /**
     * @var int
     */
    protected $eventVersion = 0;
    
    /**
     * @var DateTime
     */
    private $occurredOn;
    
    
    public function __construct()
    {
        $this->occurredOn = DateTime::now();
    }
    
    
    /**
     * @return DateTime
     */
    final public function occurredOn() : DateTime
    {
        return $this->occurredOn;
    }
    
    
    /**
     * @return int
     */
    final public function eventVersion() : int
    {
        return $this->eventVersion;
    }
}
