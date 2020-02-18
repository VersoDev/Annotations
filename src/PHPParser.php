<?php

namespace Annotations;

use ReflectionClass;
use ReflectionException;
use SplFileObject;

class PHPParser
{
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var int
     */
    private int $startingLine;

    /**
     * PHPParser constructor.
     * @param string $filename
     * @param int $startingLine
     */
    public function __construct(string $filename, int $startingLine)
    {
        $this->filename = $filename;
        $this->startingLine = $startingLine;
    }

    /**
     * @return array
     */
    public function parseImports(): array
    {
        $content = $this->parseFile($this->startingLine);

        $ws = '[\s ]'; // Whitespace sequence
        preg_match_all("#use{$ws}+([a-zA-Z\\\\]+)(?:{$ws}+as{$ws}+([a-zA-Z]+))?#i", $content, $matchedImports, PREG_SET_ORDER);

        $imports = [];
        foreach ($matchedImports as $matchedImport) {
            try {
                $alias = $matchedImport[2] ?? (new ReflectionClass($matchedImport[1]))->getShortName();
                $imports[$alias] = $matchedImport[1];
            } catch (ReflectionException $ignored) {
            }
        }

        return $imports;
    }

    /**
     * @param int $nbl
     * @return string
     */
    private function parseFile(int $nbl): string
    {
        $file = new SplFileObject($this->filename, 'r');

        $content = "";
        $lines = 0;
        while (($line = $file->fgets()) && $lines++ < $nbl) {
            $content .= $line;
        }

        return $content;
    }
}