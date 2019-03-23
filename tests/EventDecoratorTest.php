<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Test;

use Krixon\DomainEvent\BaseEvent;
use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\EventDecorator;
use PHPUnit\Framework\TestCase;

class EventDecoratorTest extends TestCase
{
    public function testInstanceCanBeCreated() : void
    {
        $original  = $this->createMock(Event::class);
        $decorated = $this->concreteDecorator($original);

        static::assertInstanceOf(EventDecorator::class, $decorated);
    }


    public function testDelegatesToDecoratedEventByDefault() : void
    {
        $original  = $this->concreteEvent();
        $decorated = $this->concreteDecorator($original);

        static::assertSame($original->eventType(), $decorated->eventType());
        static::assertSame($original->eventVersion(), $decorated->eventVersion());
        static::assertTrue($original->occurredOn()->equals($decorated->occurredOn()));
    }


    private static function concreteDecorator(Event $event) : EventDecorator
    {
        return new class($event) extends EventDecorator {
        };
    }


    private static function concreteEvent() : Event
    {
        return new class extends BaseEvent {
        };
    }
}
