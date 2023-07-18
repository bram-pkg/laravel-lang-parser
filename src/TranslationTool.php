<?php

namespace BlameButton\PhpLangParser;

use BlameButton\PhpLangParser\TranslationEngines\TranslationEngine;
use BlameButton\PhpLangParser\Visitors\TranslateArrayItemVisitor;
use InvalidArgumentException;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;

class TranslationTool
{
    private readonly Parser $parser;

    private function __construct(
        private readonly string $file
    ) {
        $this->parser = (new ParserFactory())
            ->create(ParserFactory::PREFER_PHP7);
    }

    public function translateUsingEngine(TranslationEngine $engine)
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new TranslateArrayItemVisitor($engine));

        $traverser->traverse(
            $this->parser->parse(file_get_contents($this->file))
        );
    }

    public static function forFile(string $file): self
    {
        if (! file_exists($file)) {
            throw new InvalidArgumentException("File $file does not exist");
        }

        return new TranslationTool($file);
    }
}