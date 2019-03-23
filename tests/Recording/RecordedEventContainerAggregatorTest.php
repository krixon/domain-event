<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Test\Recording;

use Krixon\DomainEvent\BaseEvent;
use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Recording\RecordedEventContainer;
use Krixon\DomainEvent\Recording\RecordedEventContainerAggregator;
use Krixon\DomainEvent\Recording\RecordsEventsInternally;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecordedEventContainerAggregatorTest extends TestCase
{
    /** @var RecordedEventContainerAggregator */
    private $aggregator;

    /** @var TestRecordedEventContainer */
    private $container1;

    /** @var TestRecordedEventContainer */
    private $container2;


    protected function setUp() : void
    {
        parent::setUp();

        $this->container1 = new TestRecordedEventContainer();
        $this->container2 = new TestRecordedEventContainer();

        $this->aggregator = new RecordedEventContainerAggregator($this->container1, $this->container2);
    }


    public function testAggregatesEvents() : void
    {
        $events   = [];
        $events[] = $this->getMockEvent();
        $events[] = $this->getMockEvent();
        $events[] = $this->getMockEvent();

        $this->container1->record($events[0]);
        $this->container1->record($events[1]);
        $this->container2->record($events[2]);

        $aggregated = $this->aggregator->recordedEvents();

        $this->assertSameSize($events, $aggregated);
        $this->assertContainsOnlyInstancesOf(Event::class, $aggregated);

        foreach ($events as $key => $input) {
            $this->assertSame($aggregated[$key], $input);
        }
    }


    /**
     * @return MockObject|BaseEvent
     */
    private function getMockEvent()
    {
        return $this
            ->getMockBuilder(BaseEvent::class)
            ->enableOriginalConstructor()
            ->enableProxyingToOriginalMethods()
            ->getMockForAbstractClass();
    }
}


class TestRecordedEventContainer implements RecordedEventContainer
{
    use RecordsEventsInternally;

    public function record(Event $event) : void
    {
        $this->recordEvent($event);
    }
}
