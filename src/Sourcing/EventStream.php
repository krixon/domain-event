<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Sourcing;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Krixon\DomainEvent\Event;
use Krixon\Identity\Identifiable;
use Krixon\Identity\IdentityProvider;
use TypeError;
use function count;
use function sprintf;

/**
 * @method EventStreamId id()
 */
final class EventStream implements IdentityProvider, Countable, IteratorAggregate, ArrayAccess
{
    use Identifiable;

    private $events;
    private $firstEventNumber;
    private $lastEventNumber;


    /**
     * @param Event[] $events
     * @param int     $firstEventNumber The number of the first event in the stream (0-based).
     */
    public function __construct(EventStreamId $eventStreamId, array $events, int $firstEventNumber = 0)
    {
        if (empty($events)) {
            throw new InvalidArgumentException('Event stream must contain at least one event.');
        }

        if ($firstEventNumber < 0) {
            throw new InvalidArgumentException('First event number cannot be less than 0.');
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
     */
    public function firstEventNumber() : int
    {
        return $this->firstEventNumber;
    }


    /**
     * Returns the number of the last event in the stream.
     */
    public function lastEventNumber() : int
    {
        return $this->lastEventNumber;
    }


    /**
     * Determines if this is a partial event stream.
     *
     * A partial stream is one which represents only part of a complete stream. A partial stream might be encountered
     * along with a snapshot, with the snapshot providing a base state and the partial stream representing the state
     * changes since the snapshot.
     */
    public function isPartial() : bool
    {
        return $this->firstEventNumber() > 0;
    }


    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->events);
    }


    public function count() : int
    {
        return count($this->events);
    }


    /**
     * @inheritdoc
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->events[$offset]);
    }


    /**
     * @inheritdoc
     */
    public function offsetGet($offset) : ?Event
    {
        return $this->offsetExists($offset) ? $this->events[$offset] : null;
    }


    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value) : void
    {
        if (!($value instanceof Event)) {
            throw new TypeError(sprintf('Value must be an instance of %s.', Event::class));
        }

        $this->events[$offset] = $value;
    }


    /**
     * @inheritdoc
     */
    public function offsetUnset($offset) : void
    {
        unset($this->events[$offset]);
    }


    /**
     * Appends a new event to the stream and advances the play head accordingly.
     */
    private function appendEvent(Event $domainEvent) : void
    {
        $this->events[++$this->lastEventNumber] = $domainEvent;
    }
}
