<?php

namespace Annotations\Entities;

use ReflectionMethod;

class AnnotatedMethod extends AnnotatedEntity
{
    /**
     * @var ReflectionMethod
     */
    private ReflectionMethod $method;

    /**
     * AnnotatedMethod constructor.
     * @param ReflectionMethod $method
     */
    public function __construct(ReflectionMethod $method)
    {
        parent::__construct($method->getFileName(), $method);

        $this->method = $method;
    }
}