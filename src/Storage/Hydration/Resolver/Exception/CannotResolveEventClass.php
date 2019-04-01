<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage\Hydration\Resolver\Exception;

use Krixon\DomainEvent\Storage\Exception\EventStoreException;
use RuntimeException;
use function sprintf;

class CannotResolveEventClass extends RuntimeException implements EventStoreException
{
    public function __construct(string $type)
    {
        parent::__construct(sprintf("Cannot resolve an event class from the event type '%s'", $type));
    }
}
