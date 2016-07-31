<?php

namespace Krixon\DomainEvent\Publishing;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Recording\RecordedEventContainer;
use Krixon\DomainEvent\Recording\RecordsEventsInternally;
use Krixon\DomainEvent\Sourcing\EventStream;

/**
 * Event publisher which can record any events it publishes.
 * 
 * This is especially useful when testing.
 */
class RecordingEventPublisher implements EventPublisher, RecordedEventContainer
{
    use RecordsEventsInternally;
    
    /**
     * @var EventPublisher
     */
    private $domainEventPublisher;
    
    /**
     * @var bool
     */
    private $recording = false;
    
    
    /**
     * @param EventPublisher $eventPublisher
     */
    public function __construct(EventPublisher $eventPublisher)
    {
        $this->domainEventPublisher = $eventPublisher;
    }
    
    
    /**
     * @return void
     */
    public function startRecording()
    {
        $this->recording = true;
    }
    
    
    /**
     * @return void
     */
    public function stopRecording()
    {
        $this->recording = false;
    }
    
    
    /**
     * @inheritdoc
     */
    public function registerListener(callable $callable, $eventClass = null)
    {
        $this->domainEventPublisher->registerListener($callable, $eventClass);
    }
    
    
    /**
     * @inheritdoc
     */
    public function publish(Event $domainEvent)
    {
        $this->domainEventPublisher->publish($domainEvent);
        
        if ($this->recording) {
            $this->recordEvent($domainEvent);
        }
    }
    
    
    /**
     * @inheritdoc
     */
    public function publishStream(EventStream $eventStream)
    {
        foreach ($eventStream as $event) {
            $this->publish($event);
        }
    }
}
