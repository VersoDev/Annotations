<?php

namespace Tests;

use Annotations\AnnotationReader;
use ReflectionClass;

class AnnotationsTest
{
    public function test()
    {
        $reader = new AnnotationReader();
        $annots = $reader->getClassAnnotations(new ReflectionClass(Service::class));

        echo '<pre>';
        var_dump($annots);
        echo '</pre>';

        //$reader->getClassAnnotations(new ReflectionClass(Service::class));
    }
}