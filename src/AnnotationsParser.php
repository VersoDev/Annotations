<?php

namespace Annotations;

use ReflectionClass;
use ReflectionException;
use function Sodium\add;

class AnnotationsParser
{
    private static array $ignoredAnnotations = [
        "param"
    ];

    /**
     * @var string
     */
    private string $docComment;

    /**
     * AnnotationsParser constructor.
     * @param string $docComment
     */
    public function __construct(string $docComment)
    {
        $this->docComment = $docComment;
    }

    /**
     * @param string $annotation
     * @return array
     * @throws ReflectionException
     */
    public function parse(string $annotation = ''): array
    {
        //$name = empty($annotation) ? '.+' : addslashes($annotation) . '|' . (new ReflectionClass($annotation))->getShortName();

        preg_match_all("#@(.+) *\((.+)\)#", $this->docComment, $data);

        $annotations = [];

        foreach ($data[1] as $key => $name) {
            $annotations[$name] = explode(",", str_replace(" ", "", $data[2][$key]));
        }

        return $annotations;
    }

    public function hasAnnotation(string $annotation): bool
    {
        return preg_match("#@$annotation#", $this->docComment);
    }
}