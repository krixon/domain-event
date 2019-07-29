<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\TestUnit\Publishing;

use Krixon\DomainEvent\BaseEvent;
use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Publishing\SynchronousEventPublisher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SynchronousEventPublisherTest extends TestCase
{
    /** @var SynchronousEventPublisher */
    private $publisher;

    /** @var TraceableEventListener */
    private $listener1;

    /** @var TraceableEventListener */
    private $listener2;

    /** @var TraceableEventListener */
    private $listener3;

    /** @var TraceableEventListener */
    private $listener4;


    protected function setUp() : void
    {
        parent::setUp();

        $this->publisher = new SynchronousEventPublisher();
        $this->listener1 = new TraceableEventListener();
        $this->listener2 = new TraceableEventListener();
        $this->listener3 = new TraceableEventListener();
        $this->listener4 = new TraceableEventListener();

        $this->assertFalse($this->listener1->wasInvoked());
        $this->assertFalse($this->listener2->wasInvoked());
        $this->assertFalse($this->listener3->wasInvoked());
        $this->assertFalse($this->listener4->wasInvoked());
    }


    public function testPublishesEventsListeners() : void
    {
        $this->publisher->registerListener($this->listener1);
        $this->publisher->registerListener($this->listener2);
        $this->publisher->registerListener($this->listener3, BaseEvent::class);
        $this->publisher->registerListener($this->listener4, SuperEvent::class);

        $baseEvent  = new class extends BaseEvent {};
        $superEvent = new SuperEvent();

        $this->publisher->publish($baseEvent);

        // Global listeners should always be invoked by a published event.
        $this->assertTrue($this->listener1->wasInvokedWith($baseEvent));
        $this->assertTrue($this->listener2->wasInvokedWith($baseEvent));

        // Listener 3 should have been invoked as it's scoped to the base event.
        $this->assertTrue($this->listener3->wasInvokedWith($baseEvent));

        // Listener 4 should not have been invoked as it's scoped to the super event.
        $this->assertFalse($this->listener4->wasInvoked());

        $this->publisher->publish($superEvent);

        // Global listeners should always be invoked by a published event.
        $this->assertTrue($this->listener1->wasInvokedWith($superEvent));
        $this->assertTrue($this->listener2->wasInvokedWith($superEvent));

        // Listener 3 should have been invoked as the super event is a child of the base event.
        $this->assertTrue($this->listener3->wasInvokedWith($superEvent));

        // Listener 4 should have been invoked as it's scoped to the super event.
        $this->assertTrue($this->listener4->wasInvokedWith($superEvent));
    }
}


class TraceableEventListener
{
    private $invokedWith = false;


    public function wasInvoked() : bool
    {
        return $this->invokedWith !== false;
    }


    public function wasInvokedWith(Event $event) : bool
    {
        return $event === $this->invokedWith;
    }


    public function __invoke(Event $event) : void
    {
        $this->invokedWith = $event;
    }
}


class SuperEvent extends BaseEvent {

}