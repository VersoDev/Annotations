<?php

namespace Annotations\Annotations;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class Target
 * @Annotation
 * @package Annotations\Annotations
 */
class Target implements Rule
{
    /**
     * @Required
     * @Enum(["CLASS", "METHOD", "PROPERTY"])
     * @var string
     */
    public string $value;

    public function valid($entity, object $annotation): bool
    {
        switch ($this->value) {
            case "CLASS":
                return $entity instanceof ReflectionClass;
                break;

            case "METHOD":
                return $entity instanceof ReflectionMethod;
                break;

            case "PROPERTY":
                return $entity instanceof ReflectionProperty;
                break;

            default:
                // Erreur
                return false;
                break;
        }
    }

    public function getErrorMessage(): string
    {
        return "Erreur Target";
    }
}