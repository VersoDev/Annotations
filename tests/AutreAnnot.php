<?php

namespace Tests;

/**
 * Class AutreAnnot
 * @Annotation
 * @package Tests
 */
class AutreAnnot
{
    /**
     * @var string
     */
    private string $test;

    public function __construct(string $test)
    {
        $this->test = $test;
    }

    /**
     * @return string
     */
    public function getTest(): string
    {
        return $this->test;
    }
}