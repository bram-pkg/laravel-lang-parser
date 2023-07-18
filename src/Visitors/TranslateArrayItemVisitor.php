<?php

namespace BlameButton\PhpLangParser\Visitors;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

class TranslateArrayItemVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Expr\ArrayItem) {
            if ($node->value instanceof String_) {
                $node->value->value = translate($node->value->value);
            }
        }
    }
}