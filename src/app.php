<?php

use BlameButton\PhpLangParser\Printers\PrettyPrinter;
use BlameButton\PhpLangParser\TranslationEngines\LocalTranslationEngine;
use BlameButton\PhpLangParser\Visitors\LaravelTranslationFileVisitor;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

require __DIR__ . '/../vendor/autoload.php';

$languageFilePath = __DIR__ . '/../lang/en.php';
$code = file_get_contents($languageFilePath);

// Parse source file
$parser = (new ParserFactory())
    ->create(ParserFactory::PREFER_PHP7);

$stmts = $parser->parse($code);

// Traverse the AST using visitors
$traverser = new NodeTraverser();
$traverser->addVisitor(new LaravelTranslationFileVisitor(
    new LocalTranslationEngine(),
    str_replace(dirname(__DIR__) . '/lang/', '', realpath($languageFilePath)),
    [
        'navigation.profile.settings' => 'Settings but in French',
    ]
));
$stmts = $traverser->traverse($stmts);

$printer = new PrettyPrinter();

echo PHP_EOL;
echo 'Output:' . str_repeat(PHP_EOL, 2);
echo $printer->prettyPrintFile($stmts) . PHP_EOL;
