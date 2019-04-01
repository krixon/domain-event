<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage\Hydration;

use Krixon\DomainEvent\Event;

interface EventHydrator
{
    /**
     * @param mixed[] $data
     */
    public function hydrate(array $data) : Event;



    /**
     * @return mixed[]
     */
    public function dehydrate(Event $event) : array;
}
