<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Publishing;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Sourcing\EventStream;
use function array_shift;
use function call_user_func;
use function get_class;
use function is_array;

/**
 * Publishes domain events to registered listeners in order.
 *
 * No new event will be published while publishing is in progress. For example if the publication of an event causes
 * a change in the state of a different part of the domain model, any events arising from that state change will be
 * queued behind the current event until handling has completed.
 */
class SynchronousEventPublisher implements EventPublisher
{
    private $publishing = false;

    /** @var Event[] */
    private $queue = [];

    /** @var callable[] */
    private $listeners = [];


    /**
     * @inheritdoc
     */
    public function registerListener(callable $callable, $eventClass = null) : void
    {
        if (!is_array($eventClass)) {
            $eventClass = [$eventClass];
        }

        foreach ($eventClass as $class) {
            $this->listeners[(string) $class][] = $callable;
        }
    }


    public function publish(Event $domainEvent) : void
    {
        if (!$this->hasListeners()) {
            return;
        }

        $this->queue[] = $domainEvent;

        if ($this->publishing) {
            return;
        }

        $this->publishing = true;

        try {
            while ($domainEvent = array_shift($this->queue)) {
                $this->doPublish($domainEvent);
            }
        } finally {
            $this->publishing = false;
        }
    }


    public function publishStream(EventStream $eventStream) : void
    {
        if (!$this->hasListeners()) {
            return;
        }

        foreach ($eventStream as $event) {
            $this->publish($event);
        }
    }


    private function hasListeners() : bool
    {
        return !empty($this->listeners);
    }


    private function doPublish(Event $domainEvent) : void
    {
        foreach ($this->listeners as $class => $listeners) {
            if (is_a($domainEvent, $class)) {
                foreach ($listeners as $listener) {
                    call_user_func($listener, $domainEvent);
                }
            }
        }

        if (!isset($this->listeners[''])) {
            return;
        }

        foreach ($this->listeners[''] as $listener) {
            call_user_func($listener, $domainEvent);
        }
    }
}
