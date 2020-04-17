<?php

namespace Annotations\Annotations;

class Regex implements Rule
{
    /**
     * @Required
     * @var string
     */
    public string $value;

    /**
     * @inheritDoc
     */
    public function valid($entity, object $annotation): bool
    {
        $value = $entity->getName();
        return preg_match($this->value, $annotation->$value);
    }

    public function getErrorMessage(): string
    {
        return "Regex error";
    }
}