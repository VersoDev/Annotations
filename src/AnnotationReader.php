<?php

namespace Annotations;

use ReflectionClass;
use ReflectionException;

class AnnotationReader
{
    /**
     * @param ReflectionClass $class
     * @return array
     */
    public function getClassAnnotations(ReflectionClass $class): array
    {
        $annotationsImports = $this->parseAnnotationsImports($class->getFileName(), $class->getStartLine());
        $namespace = $class->getNamespaceName();

        $annotationsParser = new AnnotationsParser($class->getDocComment());
        $parsedAnnotations = $annotationsParser->parseAnnotations();

        $annotations = [];
        foreach ($parsedAnnotations as $name => $args) {
            if (class_exists($namespace . '\\' . $name) || isset($annotationsImports[$name])) {
                try {
                    $className = $annotationsImports[$name] ?? $name;
                    $reflectionClass = new ReflectionClass($className);

                    $instance = $reflectionClass->newInstance();

                    foreach ($args as $key => $value) {
                        if ($reflectionClass->hasProperty($key)) {
                            $instance->$key = $value;
                        } else {
                            // Pas de propriété $key déclarée
                        }
                    }

                    $annotations[$className] = $instance;
                } catch (ReflectionException $e) {

                }
            } else {
                // Erreur : annotation n'existe pas où n'est pas déclarée avec @Annotation
            }
        }

        return $annotations;
    }

    private function parseAnnotationsImports(string $filename, int $startingLine): array
    {
        $phpParser = new PHPParser($filename, $startingLine);
        $imports = $phpParser->parseImports();

        return array_filter($imports, function ($import) {
            if ($docComment = (new ReflectionClass($import))->getDocComment()) {
                return (new AnnotationsParser($docComment))->isMarkedWith('Annotation');
            } else {
                return false;
            }
        }, ARRAY_FILTER_USE_BOTH);
    }
}