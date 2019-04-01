<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\TestResource\Event;

class ItemRenamedEvent extends ItemEvent
{
    protected $eventVersion = 2;
    private $oldName;

    public function __construct(string $id, string $name, string $description, string $oldName)
    {
        parent::__construct($id, $name, $description);

        $this->oldName = $oldName;
    }


    public function oldName() : string
    {
        return $this->oldName;
    }


    public static function eventType() : string
    {
        return 'item.renamed';
    }
}
