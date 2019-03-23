<?php

declare(strict_types=1);

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

    private $domainEventPublisher;

    private $recording = false;


    public function __construct(EventPublisher $eventPublisher)
    {
        $this->domainEventPublisher = $eventPublisher;
    }


    public function startRecording() : void
    {
        $this->recording = true;
    }


    public function stopRecording() : void
    {
        $this->recording = false;
    }


    /**
     * @inheritdoc
     */
    public function registerListener(callable $callable, $eventClass = null) : void
    {
        $this->domainEventPublisher->registerListener($callable, $eventClass);
    }


    public function publish(Event $domainEvent) : void
    {
        $this->domainEventPublisher->publish($domainEvent);

        if (!$this->recording) {
            return;
        }

        $this->recordEvent($domainEvent);
    }


    public function publishStream(EventStream $eventStream) : void
    {
        foreach ($eventStream as $event) {
            $this->publish($event);
        }
    }
}
