<?php

namespace BlameButton\PhpLangParser;

use BlameButton\PhpLangParser\Printers\PrettyPrinter;
use BlameButton\PhpLangParser\TranslationEngines\TranslationEngine;
use BlameButton\PhpLangParser\Visitors\LaravelTranslationFileVisitor;
use InvalidArgumentException;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;

final class TranslationTool
{
    private readonly Parser $parser;

    /**
     * @var Node[]
     */
    private array $statements = [];

    private function __construct(
        private readonly string $code
    ) {
        $this->parser = (new ParserFactory())
            ->create(ParserFactory::PREFER_PHP7);
    }

    public function translateUsingEngine(TranslationEngine $engine): self
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(
            new LaravelTranslationFileVisitor($engine)
        );

        $this->statements = $traverser->traverse(
            $this->parser->parse($this->code)
        );

        return $this;
    }

    public function prettyPrint(): void
    {
        $printer = new PrettyPrinter();

        echo $printer->prettyPrintFile($this->statements) . PHP_EOL;
    }

    public static function forFile(string $file): self
    {
        if (! file_exists($file)) {
            throw new InvalidArgumentException("File $file does not exist");
        }

        return new TranslationTool(file_get_contents($file));
    }
}