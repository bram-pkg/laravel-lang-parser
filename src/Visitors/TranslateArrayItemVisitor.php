<?php

namespace BlameButton\PhpLangParser\Visitors;

use BlameButton\PhpLangParser\TranslationEngines\TranslationEngine;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

class TranslateArrayItemVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly TranslationEngine $translationEngine,
    ) {
    }

    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Expr\ArrayItem) {
            if ($node->value instanceof String_) {
                $string = $node->value;

                $string->value = $this->translationEngine->translate(
                    'en',
                    'fr',
                    [$string->value],
                )[0];
            }
        }
    }
}