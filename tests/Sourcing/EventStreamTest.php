<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Test\Sourcing;

use InvalidArgumentException;
use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Sourcing\EventStream;
use Krixon\DomainEvent\Sourcing\EventStreamId;
use PHPUnit\Framework\TestCase;

class EventStreamTest extends TestCase
{
    public function testRejectsStreamWithNoEvents() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Event stream must contain at least one event.');

        $steamId = new EventStreamId('foo');

        new EventStream($steamId, []);
    }


    public function testRejectsEventNumberLessThanZero() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('First event number cannot be less than 0.');

        $streamId = new EventStreamId('foo');

        new EventStream($streamId, [$this->mockEvent()], -1);
    }


    /**
     * @dataProvider isPartialExpectationsProvider
     */
    public function testCanDetermineIfStreamIsPartial(EventStream $stream, bool $expected) : void
    {
        $this->assertSame($stream->isPartial(), $expected);
    }


    /**
     * @return mixed[]
     */
    public function isPartialExpectationsProvider() : array
    {
        $streamId = new EventStreamId('foo');

        return [
            [
                new EventStream($streamId, [$this->mockEvent()]),
                false,
            ],
            [
                new EventStream($streamId, [$this->mockEvent()], 0),
                false,
            ],
            [
                new EventStream($streamId, [$this->mockEvent()], 1),
                true,
            ],
            [
                new EventStream($streamId, [$this->mockEvent()], 100),
                true,
            ],
        ];
    }


    private function mockEvent() : Event
    {
        return $this->createMock(Event::class);
    }
}
