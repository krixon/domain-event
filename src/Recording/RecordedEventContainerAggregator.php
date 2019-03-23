<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Recording;

use function array_merge;

/**
 * Aggregates multiple RecordedEventContainers together.
 */
class RecordedEventContainerAggregator implements RecordedEventContainer
{
    /** @var RecordedEventContainer[] */
    private $recorders = [];


    public function __construct(RecordedEventContainer ...$recorders)
    {
        foreach ($recorders as $recorder) {
            $this->addEventRecorder($recorder);
        }
    }


    /**
     * @inheritdoc
     */
    public function recordedEvents() : array
    {
        $events = [];

        foreach ($this->recorders as $recorder) {
            $events = array_merge($events, $recorder->recordedEvents());
        }

        return $events;
    }


    public function eraseRecordedEvents() : void
    {
        foreach ($this->recorders as $recorder) {
            $recorder->eraseRecordedEvents();
        }
    }


    private function addEventRecorder(RecordedEventContainer $recorder) : void
    {
        $this->recorders[] = $recorder;
    }
}
