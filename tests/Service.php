<?php

namespace Tests;

use Tests\AnnotationSimple as Simple;
use Tests\AnnotationSimpleDeuxiemeSyntaxe;
use Tests\AnnotationComplexeAvecCles;

/**
 * Class Service
 * @Simple //Alias
 * @AnnotationSimpleDeuxiemeSyntaxe()
 * @AnnotationComplexe(a=1) // Doit échouer car aucun paramètre ou field
 * @AnnotationComplexeAvecCles(attention = 2,    b=2, c=3) // Bien tester si les fields existent
 * @AnnotationInexistante() // Doit échouer
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