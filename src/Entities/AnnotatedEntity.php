<?php

namespace Annotations\Entities;

use Annotations\Annotations\Target;
use Annotations\AnnotationsParser;
use Annotations\PHPParser;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class AnnotatedEntity
{
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var ReflectionClass|ReflectionMethod
     */
    private $entity;

    /**
     * AnnotatedEntity constructor.
     * @param string $filename
     * @param ReflectionClass|ReflectionMethod $entity
     */
    public function __construct(string $filename, $entity)
    {
        $this->filename = $filename;
        $this->entity = isset($entity) ? $entity : $filename;
    }

    /**
     * @param string $annotation
     * @return mixed
     */
    public function getAnnotation(string $annotation)
    {
        try {
            if ((new AnnotationsParser(new ReflectionClass($annotation)))->hasAnnotation("Annotation")) {
                // $annotation is a valid annotation
                $args = (new AnnotationsParser($this->entity->getDocComment()))->parse($annotation);

                return new $annotation(...$args);
            } else {
                // Error
            }
        } catch (ReflectionException $e) {
        }
    }

    /**
     * @return array
     */
    public function getAnnotations(): array
    {
        try {
            $phpParser = new PHPParser($this->filename);
            $imports = $phpParser->parseUseStatements();

            // Keep only imports which refer to a class marked with an annotation
            $annotationsImports = array_filter($imports, function ($element) {
                return (new AnnotationsParser(new ReflectionClass($element)))->hasAnnotation("Annotation");
            });

            // Class names without namespace
            $baseNames = array_map(function ($element) {
                return (new ReflectionClass($element))->getShortName();
            }, $annotationsImports);

            $annotationsNames = array_combine($baseNames, $annotationsImports);

            $annotationParser = new AnnotationsParser($this->entity->getDocComment());
            $annotations = $annotationParser->parse();

            $instances = [];
            foreach ($annotations as $name => $params) {
                if (class_exists($name) || in_array($name, $baseNames)) {
                    $reflectionClass = new ReflectionClass($annotationsNames[$name]);
                    $annotationClass = new AnnotatedClass($reflectionClass);

                    $properties = $annotationClass->getAnnotations();

                    // Verifications
                    if (array_key_exists(Target::class, $properties)) {
                        // PROPERTY, METHOD, CLASS
                        $type = 'Reflection' . ucfirst(strtolower($properties[Target::class]->type));
                        if (!($this->entity instanceof $type)) {
                            // Error Annotation not placed on a good entity
                            echo "Error : Not good entity";
                            die();
                        }
                    }

                    $instances[$annotationsNames[$name]] = new $annotationsNames[$name](...$params);
                } else {
                    // Error, annotation doesn't exist
                    echo 'Annotation doesn\'t exist';
                }
            }

            return $instances;
        } catch (ReflectionException $ignored) {

        }
    }
}