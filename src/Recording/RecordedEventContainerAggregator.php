<?php

namespace Krixon\DomainEvent\Recording;

/**
 * Aggregates multiple RecordedEventContainers together.
 */
class RecordedEventContainerAggregator implements RecordedEventContainer
{
    /**
     * @var RecordedEventContainer[]
     */
    private $recorders = [];
    
    
    /**
     * @param RecordedEventContainer[] $recorders
     */
    public function __construct(array $recorders)
    {
        foreach ($recorders as $recorder) {
            $this->addEventRecorder($recorder);
        }
    }
    
    
    /**
     * @inheritdoc
     */
    public function recordedEvents()
    {
        $events = [];
    
        foreach ($this->recorders as $recorder) {
            $events = array_merge($events, $recorder->recordedEvents());
        }
        
        return $events;
    }
    
    
    /**
     * @inheritdoc
     */
    public function eraseRecordedEvents()
    {
        foreach ($this->recorders as $recorder) {
            $recorder->eraseRecordedEvents();
        }
    }
    
    
    /**
     * Adds a new recorder to the set.
     * 
     * @param RecordedEventContainer $recorder
     */
    private function addEventRecorder(RecordedEventContainer $recorder)
    {
        $this->recorders[] = $recorder;
    }
}
