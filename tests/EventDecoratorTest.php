<?php

namespace Krixon\DomainEvent\Test;

use Krixon\DomainEvent\BaseEvent;
use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\EventDecorator;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Krixon\DomainEvent\EventDecorator
 * @covers <protected>
 * @covers <private>
 */
class EventDecoratorTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testInstanceCanBeCreated()
    {
        $original  = $this->createMock(Event::class);
        $decorated = $this->concreteDecorator($original);

        static::assertInstanceOf(EventDecorator::class, $decorated);
    }


    /**
     * @covers ::occurredOn
     * @covers ::eventVersion
     * @covers ::eventType
     */
    public function testDelegatesToDecoratedEventByDefault()
    {
        $original  = $this->concreteEvent();
        $decorated = $this->concreteDecorator($original);

        static::assertSame($original->eventType(), $decorated->eventType());
        static::assertSame($original->eventVersion(), $decorated->eventVersion());
        static::assertTrue($original->occurredOn()->equals($decorated->occurredOn()));
    }


    private function concreteDecorator($event) : EventDecorator
    {
        return new class($event) extends EventDecorator {};
    }


    private function concreteEvent() : Event
    {
        return new class extends BaseEvent {};
    }
}
