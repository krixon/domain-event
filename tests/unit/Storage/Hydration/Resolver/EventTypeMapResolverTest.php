<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\TestUnit\Storage\Hydration\Resolver;

use Krixon\DomainEvent\Storage\Hydration\Resolver\EventTypeMapResolver;
use Krixon\DomainEvent\Storage\Hydration\Resolver\Exception\CannotResolveEventClass;
use PHPUnit\Framework\TestCase;

class EventTypeMapResolverTest extends TestCase
{
    public function testReturnsExpectedEventClass() : void
    {
        $resolver = new EventTypeMapResolver(['item.renamed' => 'App\Event\Item\ItemRenamed']);

        $this->assertSame('App\Event\Item\ItemRenamed', $resolver->resolve('item.renamed'));
    }


    public function testRejectsUnknownEventType() : void
    {
        $this->expectException(CannotResolveEventClass::class);
        $this->expectExceptionMessage("Cannot resolve an event class from the event type 'item.delete'");

        $resolver = new EventTypeMapResolver(['item.renamed' => 'App\Event\Item\ItemRenamed']);

        $resolver->resolve('item.delete');
    }
}