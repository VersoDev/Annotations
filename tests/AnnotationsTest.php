<?php

namespace Tests;

use Annotations\Entities\AnnotatedClass;
use Annotations\Entities\AnnotatedMethod;
use ReflectionClass;
use ReflectionMethod;

class AnnotationsTest
{
    public function test()
    {
        $method = new ReflectionMethod(Service::class, "testServ");
        $amethod = new AnnotatedMethod($method);

        $amethod->getAnnotations();

        //var_dump($amethod->getAnnotations());
    }
}