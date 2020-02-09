<?php

namespace Annotations\Annotations;

/**
 * Class Target
 * @package Annotations\Annotations
 * @Annotation
 */
class Target
{
    /**
     * @var string
     */
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }
}