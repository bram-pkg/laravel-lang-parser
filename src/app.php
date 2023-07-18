<?php

use BlameButton\PhpLangParser\TranslationEngines\LocalTranslationEngine;
use BlameButton\PhpLangParser\Visitors\TranslateArrayItemVisitor;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard as Printer;

require __DIR__ . '/../vendor/autoload.php';

$code = file_get_contents(__DIR__ . '/../lang/en.php');

// Parse source file
$parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
$stmts = $parser->parse($code);
// Traverse the AST using visitors
$traverser = new NodeTraverser();
$traverser->addVisitor(new TranslateArrayItemVisitor(new LocalTranslationEngine()));
$stmts = $traverser->traverse($stmts);

$printer = new Printer();
echo $printer->prettyPrintFile($stmts) . PHP_EOL;

//echo (new NodeDumper)->dump($ast) . PHP_EOL;