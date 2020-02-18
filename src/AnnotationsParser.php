<?php

namespace Annotations;

class AnnotationsParser
{
    /**
     * @var array
     */
    private static array $ignoredAnnotations = [
        'param',
        'package'
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
     * @return array
     */
    public function parseAnnotations(): array
    {
        $ws = '[\s ]*'; // Whitespace sequence
        preg_match_all("#@([a-zA-Z\\\\]+){$ws}(?:\((.+)?\))?#", $this->docComment, $matchedAnnotations, PREG_SET_ORDER);

        // Keep only not ignored annotations
        $matchedAnnotations = array_filter($matchedAnnotations, function ($matchedAnnotation) {
            return !(in_array($matchedAnnotation[1], self::$ignoredAnnotations));
        });

        $annotations = [];
        foreach ($matchedAnnotations as $matchedAnnotation) {
            $args = [];

            if (isset($matchedAnnotation[2])) {
                // If the annotation contains args, then parse them
                foreach (explode(',', $matchedAnnotation[2]) as $arg) {
                    preg_match("#(\w+){$ws}={$ws}(.+)#", $arg, $matchedArg);
                    // Parse it using json
                    $value = json_decode($matchedArg[2]);

                    $args[$matchedArg[1]] = $value ?? $matchedArg[2];
                }
            }

            $annotations[$matchedAnnotation[1]] = $args;
        }

        return $annotations;
    }

    /**
     * @param string $annotation
     * @return bool
     */
    public function isMarkedWith(string $annotation): bool
    {
        return preg_match("#@{$annotation}#", $this->docComment);
    }
}