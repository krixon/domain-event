<?php
namespace Krixon\DomainEvent\Test\Recording;

use Krixon\DomainEvent\BaseEvent;
use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Recording\RecordedEventContainer;
use Krixon\DomainEvent\Recording\RecordedEventContainerAggregator;
use Krixon\DomainEvent\Recording\RecordsEventsInternally;

/**
 * @coversDefaultClass Krixon\DomainEvent\Recording\RecordedEventContainerAggregator
 * @covers             <protected>
 * @covers             <private>
 */
class RecordedEventContainerAggregatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RecordedEventContainerAggregator
     */
    private $aggregator;

    /**
     * @var TestRecordedEventContainer
     */
    private $container1;

    /**
     * @var TestRecordedEventContainer
     */
    private $container2;


    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->container1 = new TestRecordedEventContainer;
        $this->container2 = new TestRecordedEventContainer;

        $this->aggregator = new RecordedEventContainerAggregator([$this->container1, $this->container2]);
    }


    public function testAggregatesEvents()
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
     * @return \PHPUnit_Framework_MockObject_MockObject|BaseEvent
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

    public function record(Event $event)
    {
        $this->recordEvent($event);
    }
}
