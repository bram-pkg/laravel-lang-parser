<?php

namespace BlameButton\PhpLangParser\Visitors;

use BlameButton\PhpLangParser\TranslationEngines\TranslationEngine;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

class TranslateArrayItemVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly TranslationEngine $translationEngine,
    ) {
    }

    private array $key = [];
    private array $keys = [];

    public function enterNode(Node $node): void
    {
        if (! $node instanceof Node\Expr\ArrayItem) {
            return;
        }

        // If the array item contains an array as its value, and the key is a string, then
        // append the item's key to $this->key array, to keep track of the current loop's hierarchy.
        if ($node->value instanceof Node\Expr\Array_) {
            if ($node->key instanceof String_) {
                $this->key[] = $node->key->value;
            }

            return;
        }

        // If the array item contains a string as its value, translate the item's value.
        if ($node->value instanceof String_) {
            // If the array item's key is a string, we have reached the end of this path in the array's shape,
            // append the tracked key plus the current array item's key to the list of keys that were translated.
            if ($node->key instanceof String_) {
                $this->keys[] = trim(join('.', $this->key) . '.' . $node->key->value, '.');
            }

            // Use the translation engine (passed in the constructor) to translate the array item's value.
            $node->value->value = $this->translationEngine->translate(
                'en',
                'fr',
                [$node->value->value],
            )[0];
        }
    }

    public function leaveNode(Node $node): void
    {
        if ($node instanceof Node\Expr\ArrayItem) {
            if ($node->value instanceof Node\Expr\Array_) {
                array_pop($this->key);
            }
        }
    }

    public function afterTraverse(array $nodes): void
    {
        echo 'Keys found:' . PHP_EOL;

        foreach ($this->keys as $item) {
            echo "- $item" . PHP_EOL;
        }
    }
}