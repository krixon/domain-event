<?php

namespace Krixon\DomainEvent\Publishing;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Sourcing\EventStream;

/**
 * Buffers published events until a flush is invoked.
 * 
 * Events will be published in the order they occurred, regardless of the order in which they were registered.
 */
class BufferedEventPublisher implements EventPublisher
{
    /**
     * @var \SplPriorityQueue
     */
    private $queue;
    
    /**
     * @var EventPublisher
     */
    private $eventPublisher;
    
    
    /**
     * @param EventPublisher $eventPublisher
     */
    public function __construct(EventPublisher $eventPublisher)
    {
        $this->queue          = new \SplPriorityQueue;
        $this->eventPublisher = $eventPublisher;
    }
    
    
    /**
     * Flushes all events from the buffer, publishing each in turn.
     */
    public function flush()
    {
        while ($nextEvent = $this->dequeue()) {
            $this->eventPublisher->publish($nextEvent);
        }
    }
    
    
    /**
     * @inheritdoc
     */
    public function registerListener(callable $callable, $eventClass = null)
    {
        $this->eventPublisher->registerListener($callable, $eventClass);
    }
    
    
    /**
     * @inheritdoc
     */
    public function publish(Event $domainEvent)
    {
        $this->queue($domainEvent);
    }
    
    
    /**
     * @inheritdoc
     */
    public function publishStream(EventStream $eventStream)
    {
        foreach ($eventStream as $domainEvent) {
            $this->queue($domainEvent);
        }
    }
    
    
    /**
     * Adds a new event to the queue.
     *
     * @param Event $domainEvent
     */
    private function queue(Event $domainEvent)
    {
        $priority = $this->determinePriority($domainEvent);
        
        $this->queue->insert($domainEvent, $priority);
    }
    
    
    /**
     * @inheritdoc
     */
    private function dequeue()
    {
        if ($this->queue->isEmpty()) {
            return null;
        }
        
        return $this->queue->extract();
    }
    
    
    /**
     * @param Event $domainEvent
     *
     * @return int
     */
    private function determinePriority(Event $domainEvent)
    {
        // FIXME: This needs to be a timestamp with as much resolution as possible.
        return $domainEvent->occurredOn()->timestamp();
    }
}
