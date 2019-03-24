<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Hydration;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Hydration\Resolver\EventClassResolver;

class EventHydrator
{
    private $classResolver;

    public function __construct(EventClassResolver $classResolver)
    {
        $this->classResolver = $classResolver;
    }

    public function hydrate(string $type, array $data) : Event
    {
        $class = $this->classResolver->resolve($type);

        if (!class_exists($class)) {
            // throw
        }
    }


    public function dehydrate(Event $event) : array
    {
        // Extract child properties.

        return [
            'occurredOn'   => $event->occurredOn(),
            'eventVersion' => $event->eventVersion(),
            'eventType'    => $event->eventType(),
        ];
    }
}