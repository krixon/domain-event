<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage\Exception;

use RuntimeException;

class EventStreamNotFound extends RuntimeException implements EventStoreException
{
}
