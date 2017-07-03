<?php

namespace Krixon\DomainEvent\Test;

use Krixon\DateTime\DateTime;
use Krixon\DomainEvent\BaseEvent;
use Krixon\DomainEvent\Event;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Krixon\DomainEvent\BaseEvent
 * @covers <protected>
 * @covers <private>
 */
class BaseEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::occurredOn
     */
    public function testRecordsOccurredOnDuringConstruction()
    {
        $now   = DateTime::now();
        $event = $this->concreteEvent();

        // Timestamps should be equal within a couple of seconds.
        static::assertEquals($now->timestamp(), $event->occurredOn()->timestamp(), '', 2);
    }


    /**
     * @covers ::eventVersion
     */
    public function testEventVersioningStartsFromZero()
    {
        $event = $this->concreteEvent();

        static::assertSame(0, $event->eventVersion());
    }


    /**
     * @covers ::eventType
     */
    public function testEventTypeIsFullyQualifiedClassName()
    {
        $event = $this->concreteEvent();

        static::assertSame(get_class($event), $event->eventType());
    }


    private function concreteEvent() : Event
    {
        return new class extends BaseEvent {};
    }
}
