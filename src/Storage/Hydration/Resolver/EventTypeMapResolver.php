<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage\Hydration\Resolver;

use Krixon\DomainEvent\Storage\Hydration\Resolver\Exception\CannotResolveEventClass;

class EventTypeMapResolver implements EventClassResolver
{
    private $map;


    /**
     * @param string[] $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }


    public function resolve(string $eventType) : string
    {
        if (!isset($this->map[$eventType])) {
            throw new CannotResolveEventClass($eventType);
        }

        return $this->map[$eventType];
    }
}
