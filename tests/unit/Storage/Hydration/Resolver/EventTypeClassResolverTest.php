<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\TestUnit\Storage\Hydration\Resolver;

use Krixon\DomainEvent\Storage\Hydration\Resolver\EventTypeClassResolver;
use PHPUnit\Framework\TestCase;

class EventTypeClassResolverTest extends TestCase
{
    public function testReturnsExpectedEventClass() : void
    {
        $resolver = new EventTypeClassResolver();

        $this->assertSame('App\Event\Foo', $resolver->resolve('App\Event\Foo'));
    }
}