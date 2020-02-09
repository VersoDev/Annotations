<?php

namespace Annotations;

class PHPParser
{
    /**
     * @var string
     */
    private string $filename;

    /**
     * PHPParser constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function parseUseStatements(): array
    {
        $content = file_get_contents(__DIR__ . '/Entities/AnnotatedMethod.php');
        //$content = file_get_contents($this->filename);
        /*$content = <<<EOT
        use Tests\Test;
        use Tests\AutreAnnot;
        EOT;*/

        echo $content;

        preg_match_all("#use +(.+);#", $content, $statements);

        return $statements[1];
    }
}