<?php

declare(strict_types=1);

namespace Krixon\DomainEvent\Storage\Hydration;

use Krixon\DomainEvent\Event;
use Krixon\DomainEvent\Storage\Hydration\Exception\HydrationFailed;
use Krixon\DomainEvent\Storage\Hydration\Resolver\EventClassResolver;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use function array_merge;
use function sprintf;
use function ucfirst;

class EventReflectionHydrator implements EventHydrator
{
    private $classResolver;


    public function __construct(EventClassResolver $classResolver)
    {
        $this->classResolver = $classResolver;
    }


    /**
     * @inheritDoc
     */
    public function hydrate(array $data) : Event
    {
        if (!isset($data['eventType'])) {
            throw new HydrationFailed('Cannot hydrate event without an event type.');
        }

        $class = $this->classResolver->resolve($data['eventType']);

        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $exception) {
            throw new HydrationFailed(
                sprintf("Cannot hydrate event, class '%s' could not be found.", $class),
                0,
                $exception
            );
        }

        $event = $reflection->newInstanceWithoutConstructor();

        if (!($event instanceof Event)) {
            throw new HydrationFailed(
                sprintf("Cannot hydrate event as the class '%s' is not an instance of event.", $class)
            );
        }

        foreach ($this->getProperties($reflection) as $property) {
            $this->setEventProperty($event, $property, $data);
        }

        return $event;
    }



    /**
     * @inheritDoc
     */
    public function dehydrate(Event $event) : array
    {
        $data = [];

        $reflection = new ReflectionClass($event);

        foreach ($this->getProperties($reflection) as $property) {
            $data[$property->getName()] = $this->getEventPropertyValue($event, $property);
        }

        $data['eventType'] = $event::eventType();

        return $data;
    }


    /**
     * @return mixed
     */
    private function getEventPropertyValue(Event $event, ReflectionProperty $property)
    {
        // First check to see if we have a getter method. If we do, use that to get the value instead.
        $method = $this->findGetterForProperty($property);

        if ($method) {
            return $method->invoke($event);
        }

        $property->setAccessible(true);

        return $property->getValue($event);
    }


    /**
     * @param mixed[] $data
     */
    private function setEventProperty(Event $event, ReflectionProperty $property, array $data) : void
    {
        if (!isset($data[$property->getName()])) {
            return;
        }

        $property->setAccessible(true);
        $property->setValue($event, $data[$property->getName()]);
    }


    private function findGetterForProperty(ReflectionProperty $property) : ?ReflectionMethod
    {
        $reflection = $property->getDeclaringClass();
        $methodName = sprintf('get%s', ucfirst($property->getName()));
        $method     = $this->getMethodOrNull($reflection, $methodName);

        // If we can't find a method explicitly prefixed with 'get' then try finding a property matching the parameter
        // name exactly.
        if (!$method) {
            $method = $this->getMethodOrNull($reflection, $property->getName());
        }

        // Only use the method if it looks like a getter.
        if ($method && $method->isPublic() && !$method->isStatic() && $method->getNumberOfParameters() === 0) {
            return $method;
        }

        return null;
    }


    private function getMethodOrNull(ReflectionClass $reflection, string $name) : ?ReflectionMethod
    {
        try {
            return $reflection->getMethod($name);
        } catch (ReflectionException $exception) {
            return null;
        }
    }


    /**
     * @return ReflectionProperty[]
     */
    private function getProperties(ReflectionClass $reflection) : array
    {
        $properties = $reflection->getProperties();
        $parent     = $reflection->getParentClass();

        while ($parent) {
            $properties = array_merge($properties, $parent->getProperties(ReflectionProperty::IS_PRIVATE));
            $parent     = $parent->getParentClass();
        }

        return $properties;
    }
}