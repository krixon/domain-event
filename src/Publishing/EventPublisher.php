<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Publishing;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Sourcing\EventStream;

interface EventPublisher
{
    /**
     * Registers a new listener.
     *
     * @param callable             $callable   The callback which will be invoked if a relevant event is published.
     * @param string|string[]|null $eventClass The event type to listen to, an array of event types, or null for
     *                                         all events.
     */
    public function registerListener(callable $callable, $eventClass = null) : void;


    /**
     * Publishes a domain event.
     *
     * If an event is currently being published, the new event will be queued until it has finished.
     */
    public function publish(Event $domainEvent) : void;


    /**
     * Publishes a stream of domain events.
     */
    public function publishStream(EventStream $eventStream) : void;
}
