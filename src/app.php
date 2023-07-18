<?php

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Translate a string into French
 *
 * @param string $original the original string to translate
 *
 * @return string|null the translated string
 */
function translate(string $original): string|null
{
    return [
        'My title' => 'Mon titre',
        'My subtitle' => 'Mon sous-titre',
        'My body' => 'Mon corps',
    ][$original] ?? $original;
}

$parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

$code = file_get_contents(__DIR__ . '/../lang/en.php');

$stmts = $parser->parse($code);

$traverser = new NodeTraverser();
$traverser->addVisitor(new class extends NodeVisitorAbstract {
    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Expr\ArrayItem) {
            if ($node->value instanceof String_) {
                $node->value->value = translate($node->value->value);
            }
        }
    }
});

$stmts = $traverser->traverse($stmts);

$printer = new PhpParser\PrettyPrinter\Standard();

echo $printer->prettyPrintFile($stmts) . PHP_EOL;

//echo (new NodeDumper)->dump($ast) . PHP_EOL;