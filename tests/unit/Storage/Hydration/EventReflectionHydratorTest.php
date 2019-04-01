<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\TestUnit\Storage\Hydration;

use Krixon\DateTime\DateTime;
use Krixon\DomainEvent\BaseEvent;
use Krixon\DomainEvent\Storage\Hydration\EventReflectionHydrator;
use Krixon\DomainEvent\Storage\Hydration\Exception\HydrationFailed;
use Krixon\DomainEvent\Storage\Hydration\Resolver\EventClassResolver;
use Krixon\DomainEvent\TestResource\Event\ItemRenamedEvent;
use PHPUnit\Framework\TestCase;
use function mb_strtoupper;

class EventReflectionHydratorTest extends TestCase
{
    public function testCanHydrateEvent() : void
    {
        $classResolver = $this->createMock(EventClassResolver::class);

        $classResolver
            ->method('resolve')
            ->with('item.renamed')
            ->willReturn('Krixon\DomainEvent\TestResource\Event\ItemRenamedEvent');

        $hydrator = new EventReflectionHydrator($classResolver);

        $occurredOn = DateTime::now();

        $data = [
            'eventType'    => 'item.renamed',
            'eventVersion' => 5,
            'occurredOn'   => $occurredOn,
            'id'           => 'be7b9323-d505-468b-a14e-f38f49c7a4a7',
            'name'         => 'Super cool item',
            'description'  => 'An item that is really cool!',
            'oldName'      => 'Average at best item!',
        ];

        /** @var ItemRenamedEvent $event */
        $event = $hydrator->hydrate($data);

        $this->assertInstanceOf(ItemRenamedEvent::class, $event);
        $this->assertSame('be7b9323-d505-468b-a14e-f38f49c7a4a7', $event->id());
        $this->assertSame('Super cool item', $event->name());
        $this->assertSame('An item that is really cool!', $event->description());
        $this->assertSame('Average at best item!', $event->oldName());
        $this->assertSame(5, $event->eventVersion());
        $this->assertTrue($occurredOn->equals($event->occurredOn()));
    }


    public function testRejectsHydrationIfClassDoesNotExist() : void
    {
        $this->expectException(HydrationFailed::class);
        $this->expectExceptionMessage("Cannot hydrate event, class 'Krixon\\DomainEvent\\TestResource\\Event\\NonExistentEvent' could not be found.");

        $classResolver = $this->createMock(EventClassResolver::class);

        $classResolver
            ->method('resolve')
            ->with('non-existent')
            ->willReturn('Krixon\DomainEvent\TestResource\Event\NonExistentEvent');

        $hydrator = new EventReflectionHydrator($classResolver);

        $hydrator->hydrate(['eventType' => 'non-existent']);
    }


    public function testRejectsHydrationIfClassIsNotEvent() : void
    {
        $this->expectException(HydrationFailed::class);
        $this->expectExceptionMessage("Cannot hydrate event as the class '\\stdClass' is not an instance of event.");

        $classResolver = $this->createMock(EventClassResolver::class);

        $classResolver
            ->method('resolve')
            ->with('not-an-event-class')
            ->willReturn('\stdClass');

        $hydrator = new EventReflectionHydrator($classResolver);

        $hydrator->hydrate(['eventType' => 'not-an-event-class']);
    }


    public function testCanDeHydrateEvent() : void
    {
        $classResolver = $this->createMock(EventClassResolver::class);

        $hydrator = new EventReflectionHydrator($classResolver);

        $now = DateTime::now();

        $event = new ItemRenamedEvent(
            'be7b9323-d505-468b-a14e-f38f49c7a4a7',
            'Super cool item',
            'An item that is really cool!',
            'Average at best item!'
        );


        $data = $hydrator->dehydrate($event);

        $this->assertArrayHasKey('eventType', $data);
        $this->assertArrayHasKey('eventVersion', $data);
        $this->assertArrayHasKey('occurredOn', $data);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('oldName', $data);

        $this->assertSame('item.renamed', $data['eventType']);
        $this->assertSame(2, $data['eventVersion']);
        $this->assertSame('be7b9323-d505-468b-a14e-f38f49c7a4a7', $data['id']);
        $this->assertSame('Super cool item', $data['name']);
        $this->assertSame('An item that is really cool!', $data['description']);
        $this->assertSame('Average at best item!', $data['oldName']);

        // Timestamps should be equal within a couple of seconds.
        $this->assertEqualsWithDelta($now->timestamp(), $data['occurredOn']->timestamp(), 2);
    }


    public function testDoesUseGettersForDehydration() : void
    {
        $classResolver = $this->createMock(EventClassResolver::class);

        $hydrator = new EventReflectionHydrator($classResolver);

        $event = new class extends BaseEvent {
            private $foo = 'foo';
            private $bar = 'bar';

            public function getFoo() : string
            {
                return mb_strtoupper($this->foo);
            }


            public function bar() : string
            {
                return mb_strtoupper($this->bar);
            }
        };


        $data = $hydrator->dehydrate($event);

        $this->assertArrayHasKey('foo', $data);
        $this->assertArrayHasKey('bar', $data);

        $this->assertSame('FOO', $data['foo']);
        $this->assertSame('BAR', $data['bar']);
    }
}
