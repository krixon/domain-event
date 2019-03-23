<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Test\Publishing;

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


    protected function setUp() : void
    {
        parent::setUp();

        $this->publisher = new SynchronousEventPublisher();
        $this->listener1 = new TraceableEventListener();
        $this->listener2 = new TraceableEventListener();

        $this->assertFalse($this->listener1->wasInvoked());
        $this->assertFalse($this->listener2->wasInvoked());
    }


    public function testPublishesEventsToListeners() : void
    {
        $event = $this->getMockEvent();

        $this->publisher->registerListener($this->listener1);
        $this->publisher->registerListener($this->listener2);

        $this->publisher->publish($event);

        $this->assertTrue($this->listener1->wasInvokedWith($event));
        $this->assertTrue($this->listener2->wasInvokedWith($event));
    }


    /**
     * @return MockObject|Event
     */
    private function getMockEvent()
    {
        return $this
            ->getMockBuilder(Event::class)
            ->enableOriginalConstructor()
            ->enableProxyingToOriginalMethods()
            ->getMockForAbstractClass();
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
