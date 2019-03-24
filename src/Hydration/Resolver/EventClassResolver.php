<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Hydration\Resolver;

/**
 * Resolves the event class from the event type.
 */
interface EventClassResolver
{
    public function resolve(string $eventType) : string;
}
