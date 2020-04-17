<?php

namespace Annotations\Annotations;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

interface Rule
{
    /**
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $entity
     * @param object $annotation
     * @return bool
     */
    public function valid($entity, object $annotation): bool;

    public function getErrorMessage(): string;
}