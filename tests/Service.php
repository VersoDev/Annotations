<?php

namespace Tests;

use Tests\AnnotationSimple as Simple;
use Tests\AnnotationSimpleDeuxiemeSyntaxe;

use Tests\AnnotationComplexeAvecCles;

/**
 * Class Service
 * @AnnotationComplexeAvecCles(attention=3, b="test") // Bien tester si les fields existent
 * @AnnotationComplexe("dslk")
 * @package test
 */
class Service
{
    /**
     * @Testa(a=1, b=2)
     * @AutreAnnot("test")
     */
    public function testServ($test)
    {

    }
}