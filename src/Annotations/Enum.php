<?php

namespace Annotations\Annotations;

class Enum implements Rule
{
    /**
     * @Required
     * @var array
     */
    public array $value;

    public function valid($entity, object $annotation): bool
    {
        $value = $entity->getName();
        return in_array($annotation->$value, $this->value);
    }

    public function getErrorMessage(): string
    {
        return 'Error Enum';
    }
}