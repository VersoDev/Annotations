<?php

namespace Annotations;

use Annotations\Annotations\Rule;
use Annotations\Exceptions\NonDeclaredAnnotationException;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class AnnotationReader
{
    /**
     * @param ReflectionClass $class
     * @return object[]
     */
    public function getClassAnnotations(ReflectionClass $class): array
    {
        try {
            return $this->getAnnotations($class, $class);
        } catch (Exception $e) {
        }

        return [];
    }

    public function getClassAnnotation(ReflectionClass $class, string $annotation): ?object
    {

    }

    public function getMethodAnnotations(ReflectionMethod $method, string $annotation): array
    {
        try {
            return $this->getAnnotations($method->getDeclaringClass(), $method);
        } catch (NonDeclaredAnnotationException $ignored) {
        } catch (Exception $e) {
        }
    }

    public function getMethodAnnoation(ReflectionMethod $method, string $annotation): ?object
    {

    }

    public function getPropertyAnnotations(ReflectionProperty $property): array
    {
        try {
            $annot = $this->getAnnotations($property->getDeclaringClass(), $property);
            return $annot;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getPropertyAnnotation(ReflectionProperty $property, string $annotation): ?object
    {

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

    /**
     * @param ReflectionClass $declaringClass
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $entity
     * @return array
     * @throws Exception
     */
    private function getAnnotations($declaringClass, $entity): array
    {
        $annotationsImports = $this->parseAnnotationsImports($declaringClass->getFileName(), $declaringClass->getStartLine());

        $annotationsParser = new AnnotationsParser($entity->getDocComment());
        $parsedAnnotations = $annotationsParser->parseAnnotations();

        $annotations = [];
        foreach ($parsedAnnotations as $name => $args) {
            if (class_exists($name) || class_exists($declaringClass->getNamespaceName() . '\\' . $name) || isset($annotationsImports[$name])) {
                $className = $annotationsImports[$name] ?? (class_exists($name) ? $name : $declaringClass->getNamespaceName() . '\\' . $name);
                $reflectionClass = new ReflectionClass($className);

                $instance = $reflectionClass->newInstance(); // instance of the annotation

                foreach ($args as $key => $value) {
                    if (is_string($key)) {
                        if ($reflectionClass->hasProperty($key)) {
                            $instance->$key = $value;
                        } else {
                            // Pas de propriété $key déclarée
                            throw new Exception();
                        }
                    } else if (count($args) === 1) {
                        // On autorise la syntaxe simplifiée (sans clé)
                        $instance->value = $value;
                    }
                }

                foreach ($this->getClassAnnotations($reflectionClass) as $annotation) {
                    if ($annotation instanceof Rule) {
                        if (!$annotation->valid($entity, $instance)) {
                            echo $annotation->getErrorMessage();
                            return [];
                        }
                    }
                }

                foreach ($reflectionClass->getProperties() as $property) {
                    foreach ($this->getPropertyAnnotations($property) as $annotation) {
                        if ($annotation instanceof Rule) {
                            if (!$annotation->valid($property, $instance)) {
                                echo $annotation->getErrorMessage();
                                return [];
                            }
                        }
                    }
                }

                $annotations[$className] = $instance;
            } else {
                // Erreur : annotation n'existe pas où n'est pas déclarée avec @Annotation
                throw new NonDeclaredAnnotationException();
            }
        }

        return $annotations;
    }
}