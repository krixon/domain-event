<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage\Hydration\Exception;

use Krixon\DomainEvent\Storage\Exception\EventStoreException;
use RuntimeException;

class HydrationFailed extends RuntimeException implements EventStoreException
{
}
