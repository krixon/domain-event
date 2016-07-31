<?php

namespace Krixon\DomainEvent\Sourcing;

use Krixon\DomainEvent\Event;
use Krixon\Identity\Identifiable;
use Krixon\Identity\IdentityProvider;

/**
 * @method EventStreamId id()
 */
final class EventStream implements IdentityProvider, \Countable, \IteratorAggregate, \ArrayAccess
{
    use Identifiable;
    
    /**
     * @var Event[]
     */
    private $events;
    
    /**
     * @var int
     */
    private $firstEventNumber;
    
    /**
     * @var int
     */
    private $lastEventNumber;
    
    
    /**
     * @param EventStreamId $eventStreamId    The event stream ID.
     * @param Event[]       $events           The events contained in the stream.
     * @param int           $firstEventNumber The number of the first event in the stream (0-based).
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(EventStreamId $eventStreamId, array $events, $firstEventNumber = 0)
    {
        if (empty($events)) {
            throw new \InvalidArgumentException('Event stream must contain at least one event.');
        }
        
        $this->id               = $eventStreamId;
        $this->firstEventNumber = $firstEventNumber;
        $this->lastEventNumber  = $firstEventNumber - 1;
        
        foreach ($events as $event) {
            $this->appendEvent($event);
        }
    }
    
    
    /**
     * @return Event[]
     */
    public function events() : array
    {
        return $this->events;
    }
    
    
    /**
     * Returns the number of the first event in the stream.
     *
     * For a complete stream this will be 0.
     * For a partial stream it might be greater than 0.
     * 
     * @return int
     */
    public function firstEventNumber() : int
    {
        return $this->firstEventNumber;
    }
    
    
    /**
     * Returns the number of the last event in the stream.
     *
     * @return int
     */
    public function lastEventNumber()
    {
        return $this->lastEventNumber;
    }
    
    
    /**
     * Determines if this is a partial event stream.
     * 
     * A partial stream is one which represents only part of a complete stream. A partial stream might be encountered
     * along with a snapshot, with the snapshot providing a base state and the partial stream representing the state
     * changes since the snapshot.
     * 
     * @return bool
     */
    public function isPartial()
    {
        return $this->firstEventNumber() > -1;
    }
    
    
    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->events);
    }
    
    
    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->events);
    }
    
    
    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->events[$offset]);
    }
    
    
    /**
     * @inheritdoc
     * 
     * @return Event
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->events[$offset] : null;
    }
    
    
    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Event) {
            throw new \TypeError(sprintf('Value must be an instance of %s.', Event::class));
        }
        
        $this->events[$offset] = $value;
    }
    
    
    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->events[$offset]);
    }
    
    
    /**
     * Appends a new event to the stream and advances the play head accordingly.
     * 
     * @param Event $domainEvent
     */
    private function appendEvent(Event $domainEvent)
    {
        $this->events[++$this->lastEventNumber] = $domainEvent;
    }
}
