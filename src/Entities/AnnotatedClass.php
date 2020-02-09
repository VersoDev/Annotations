<?php

namespace Annotations\Entities;

use ReflectionClass;

class AnnotatedClass extends AnnotatedEntity
{
    /**
     * @var ReflectionClass
     */
    private ReflectionClass $class;

    public function __construct(ReflectionClass $class)
    {
        parent::__construct($class->getFileName(), $class);

        $this->class = $class;
    }
}