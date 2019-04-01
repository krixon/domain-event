<?php

declare(strict_types=1);

namespace Krixon\DomainEvent;

use Krixon\DateTime\DateTime;

abstract class BaseEvent implements Event
{
    protected $eventVersion = 0;

    private $occurredOn;


    public function __construct()
    {
        $this->occurredOn = DateTime::now();
    }


    final public function occurredOn() : DateTime
    {
        return $this->occurredOn;
    }


    final public function eventVersion() : int
    {
        return $this->eventVersion;
    }


    public static function eventType() : string
    {
        return static::class;
    }
}
