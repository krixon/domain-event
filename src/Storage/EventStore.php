<?php

namespace Krixon\DomainEvent\Storage;

use Krixon\DomainEvent\Event;

interface EventStore
{
    public function append(Event $domainEvent);
}
