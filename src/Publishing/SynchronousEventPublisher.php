<?php

namespace Krixon\DomainEvent\Publishing;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Sourcing\EventStream;

/**
 * Publishes domain events to registered listeners in order.
 * 
 * No new event will be published while publishing is in progress. For example if the publication of an event causes
 * a change in the state of a different part of the domain model, any events arising from that state change will be
 * queued behind the current event until handling has completed.
 */
class SynchronousEventPublisher implements EventPublisher
{
    /**
     * @var bool
     */
    private $publishing  = false;
    
    /**
     * @var Event[]
     */
    private $queue = [];
    
    /**
     * @var callable[]
     */
    private $listeners = [];
    
    
    /**
     * @inheritdoc
     */
    public function registerListener(callable $callable, $eventClass = null)
    {
        if (!is_array($eventClass)) {
            $eventClass = [$eventClass];
        }
        
        foreach ($eventClass as $class) {
            $this->listeners[(string)$class][] = $callable;
        }
    }
    
    
    /**
     * @inheritdoc
     */
    public function publish(Event $domainEvent)
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
    
    
    /**
     * @inheritdoc
     */
    public function publishStream(EventStream $eventStream)
    {
        if (!$this->hasListeners()) {
            return;
        }
        
        foreach ($eventStream as $event) {
            $this->publish($event);
        }
    }
    
    
    /**
     * @return bool
     */
    private function hasListeners()
    {
        return !empty($this->listeners);
    }
    
    
    /**
     * @param Event $domainEvent
     */
    private function doPublish(Event $domainEvent)
    {
        $class = get_class($domainEvent);
        
        if (isset($this->listeners[$class])) {
            foreach ($this->listeners[$class] as $listener) {
                call_user_func($listener, $domainEvent);
            }
        }
        
        if (isset($this->listeners[''])) {
            foreach ($this->listeners[''] as $listener) {
                call_user_func($listener, $domainEvent);
            }
        }
    }
}
