<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Recording;

use Krixon\DomainEvent\Event;

interface EventRecorder
{
    public function record(Event $domainEvent) : void;
}
