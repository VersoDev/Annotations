<?php

namespace Annotations\Annotations;

/**
 * Class Enum
 * @package Annotations\Annotations
 * @Annotation
 */
class Enum
{
    /**
     * @var mixed
     */
    public $args;

    /**
     * Enum constructor.
     * @param mixed ...$args
     */
    public function __construct(...$args)
    {
    }
}