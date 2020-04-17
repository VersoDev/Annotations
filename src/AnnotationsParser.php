<?php

namespace Annotations;

class AnnotationsParser
{
    /**
     * @var array
     * @todo Complete the list
     */
    private static array $ignoredAnnotations = [
        'Annotation',
        'param',
        'package',
        'var'
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
                var_dump($matchedAnnotation[2]);
                echo '<br>';
                $openingChars = "\"'\[{";
                $closingChars = "\"'\]}";
                $explodedArgs = preg_split("/[^{$openingChars}],{$ws}(\d+|[a-z]+[^{$closingChars}]|[{$openingChars}])/", $matchedAnnotation[2]);
                //$explodedArgs = preg_split("#,(?=(?:[^{$openingChars}]*{$closingChars}[^{$openingChars}]*{$closingChars})*[^{$closingChars}]*$)#", $matchedAnnotation[2]);

                echo '<pre>';
                var_dump($explodedArgs);
                echo '</pre>';

                foreach ($explodedArgs as $arg) {
                    preg_match("#(\w+){$ws}={$ws}(.+)#", $arg, $matchedArg);

                    // Parse it using json
                    if (isset($matchedArg[2])) {
                        $args[$matchedArg[1]] = json_decode($matchedArg[2]);
                    } else {
                        // On autorise la syntaxe simplifiée si il n'y a qu'un seul argument
                        $args[] = json_decode($explodedArgs[0]);
                    }
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