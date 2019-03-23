<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Publishing;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Sourcing\EventStream;
use SplPriorityQueue;

/**
 * Buffers published events until a flush is invoked.
 *
 * Events will be published in the order they occurred, regardless of the order in which they were registered.
 */
class BufferedEventPublisher implements EventPublisher
{
    /** @var SplPriorityQueue */
    private $queue;

    /** @var EventPublisher */
    private $eventPublisher;


    public function __construct(EventPublisher $eventPublisher)
    {
        $this->queue          = new SplPriorityQueue();
        $this->eventPublisher = $eventPublisher;
    }


    /**
     * Flushes all events from the buffer, publishing each in turn.
     */
    public function flush() : void
    {
        while ($nextEvent = $this->dequeue()) {
            $this->eventPublisher->publish($nextEvent);
        }
    }


    /**
     * @inheritdoc
     */
    public function registerListener(callable $callable, $eventClass = null) : void
    {
        $this->eventPublisher->registerListener($callable, $eventClass);
    }


    public function publish(Event $domainEvent) : void
    {
        $this->queue($domainEvent);
    }


    public function publishStream(EventStream $eventStream) : void
    {
        foreach ($eventStream as $domainEvent) {
            $this->queue($domainEvent);
        }
    }


    /**
     * Adds a new event to the queue.
     */
    private function queue(Event $domainEvent) : void
    {
        $priority = $this->determinePriority($domainEvent);

        $this->queue->insert($domainEvent, $priority);
    }


    private function dequeue() : ?Event
    {
        if ($this->queue->isEmpty()) {
            return null;
        }

        return $this->queue->extract();
    }


    private function determinePriority(Event $domainEvent) : int
    {
        // FIXME: This needs to be a timestamp with as much resolution as possible.
        return $domainEvent->occurredOn()->timestamp();
    }
}
