<?php

namespace Krixon\DomainEvent\Test;

use Krixon\DateTime\DateTime;
use Krixon\DomainEvent\BaseEvent;
use Krixon\DomainEvent\Event;
use PHPUnit\Framework\TestCase;

class BaseEventTest extends TestCase
{
    public function testRecordsOccurredOnDuringConstruction() : void
    {
        $now   = DateTime::now();
        $event = $this->concreteEvent();

        // Timestamps should be equal within a couple of seconds.
        static::assertEqualsWithDelta($now->timestamp(), $event->occurredOn()->timestamp(), 2);
    }


    public function testEventVersioningStartsFromZero() : void
    {
        $event = $this->concreteEvent();

        static::assertSame(0, $event->eventVersion());
    }


    public function testEventTypeIsFullyQualifiedClassName() : void
    {
        $event = $this->concreteEvent();

        static::assertSame(get_class($event), $event->eventType());
    }


    private static function concreteEvent() : Event
    {
        return new class extends BaseEvent {};
    }
}
