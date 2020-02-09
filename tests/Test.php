<?php

namespace Tests;

use Annotations\Annotations\Target;

/**
 * Class Test
 * @Annotation
 * @Target("PROPERTY")
 * @package Tests
 */
class Test
{
    /**
     * @var int
     */
    private int $a;
    /**
     * @var int
     */
    private int $b;

    public function __construct(int $a, int $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    /**
     * @return int
     */
    public function getA(): int
    {
        return $this->a;
    }

    /**
     * @return int
     */
    public function getB(): int
    {
        return $this->b;
    }
}