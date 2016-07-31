<?php

namespace Krixon\DomainEvent\Test\Publishing;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Publishing\SynchronousEventPublisher;

/**
 * @coversDefaultClass Krixon\DomainEvent\Publishing\SynchronousEventPublisher
 * @covers <protected>
 * @covers <private>
 */
class SynchronousEventPublisherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SynchronousEventPublisher
     */
    private $publisher;
    
    /**
     * @var TraceableEventListener
     */
    private $listener1;
    
    /**
     * @var TraceableEventListener
     */
    private $listener2;
    
    
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->publisher = new SynchronousEventPublisher;
        $this->listener1 = new TraceableEventListener;
        $this->listener2 = new TraceableEventListener;
        
        $this->assertFalse($this->listener1->wasInvoked());
        $this->assertFalse($this->listener2->wasInvoked());
    }
    
    
    public function testPublishesEventsToListeners()
    {
        $event = $this->getMockEvent();
        
        $this->publisher->registerListener($this->listener1);
        $this->publisher->registerListener($this->listener2);
        
        $this->publisher->publish($event);
        
        $this->assertTrue($this->listener1->wasInvokedWith($event));
        $this->assertTrue($this->listener2->wasInvokedWith($event));
    }
    
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Event
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
    
    
    /**
     * @return bool
     */
    public function wasInvoked()
    {
        return $this->invokedWith !== false;
    }
    
    
    public function wasInvokedWith($event)
    {
        return $event === $this->invokedWith;
    }
    
    
    public function __invoke($event)
    {
        $this->invokedWith = $event;
    }
}
