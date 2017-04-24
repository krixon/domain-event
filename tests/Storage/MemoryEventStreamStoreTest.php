<?php

namespace Krixon\DomainEvent\Test\Storage;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Sourcing\EventStream;
use Krixon\DomainEvent\Sourcing\EventStreamId;
use Krixon\DomainEvent\Storage\MemoryEventStreamStore;

/**
 * @coversDefaultClass Krixon\DomainEvent\Storage\MemoryEventStreamStore
 * @covers <protected>
 * @covers <private>
 */
class MemoryEventStreamStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::appendEvents
     */
    public function testAppendWith()
    {
        $eventStore  = new MemoryEventStreamStore;
        $eventStream = $this->createEventStream(5);
        
        $eventStore->appendEvents($eventStream);
    
        $eventStream = $eventStore->eventStream($eventStream->id());
        
        $this->assertCount(5, $eventStream);
        $this->assertSame(4, $eventStream->lastEventNumber());
        
        $eventStream = $this->createEventStream(3, $eventStream->lastEventNumber() + 1);
        
        $eventStore->appendEvents($eventStream);
        
        $eventStream = $eventStore->eventStream($eventStream->id());
        
        $this->assertCount(8, $eventStream);
        $this->assertSame(7, $eventStream->lastEventNumber());
    }
    
    
    private function createEventStream($numEvents, $firstEventNumber = -1, $streamName = 'test')
    {
        $eventStreamId = new EventStreamId($streamName);
        
        return new EventStream($eventStreamId, $this->createEvents($numEvents), $firstEventNumber);
    }
    
    
    private function createEvents($amount)
    {
        $events = [];
        
        for ($i = 0; $i < $amount; $i++) {
            $events[] = $this->getMockForAbstractClass(Event::class);
        }
        
        return $events;
    }
}