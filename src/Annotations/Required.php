<?php

namespace Annotations\Annotations;

/**
 * Class Required
 * @package Annotations\Annotations
 * @Annotation
 */
class Required implements Rule
{
    public function valid($entity, $annotation): bool
    {
        $value = $entity->getName();
        return isset($annotation->$value);
    }

    public function getErrorMessage(): string
    {
        return "Erreur Required";
    }
}