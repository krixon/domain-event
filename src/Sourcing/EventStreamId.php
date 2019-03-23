<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Sourcing;

use Krixon\Identity\Identifier;
use Krixon\Identity\ProvidesIdentityWhenInvoked;
use Krixon\Identity\StoresIdentityAsSingleString;

class EventStreamId implements Identifier
{
    use StoresIdentityAsSingleString;
    use ProvidesIdentityWhenInvoked;

    public function __construct(string $streamName)
    {
        $this->id = $streamName;
    }
}
