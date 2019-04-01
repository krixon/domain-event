<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage\Hydration\Resolver;

/**
 * Can be used when the event class is used as the event type.
 */
class EventTypeClassResolver implements EventClassResolver
{
    public function resolve(string $eventType) : string
    {
        return $eventType;
    }
}
