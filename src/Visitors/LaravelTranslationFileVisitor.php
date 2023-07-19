<?php

namespace BlameButton\PhpLangParser\Visitors;

use BlameButton\PhpLangParser\Exceptions\UnsupportedFileExtension;
use BlameButton\PhpLangParser\TranslationEngines\TranslationEngine;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitorAbstract;

class LaravelTranslationFileVisitor extends NodeVisitorAbstract
{
    private array $key = [];
    private array $translatedArrayKeys = [];

    public function __construct(
        private readonly TranslationEngine $translationEngine,
        private readonly string|null       $prefix = null,
        private readonly array             $override = [],
    ) {
        if ($this->prefix !== null) {
            $this->key[] = $this->prefix;
        }
    }

    public function enterNode(Node $node): void
    {
        if (! $node instanceof Node\Expr\ArrayItem) {
            return;
        }

        // If the array item contains an array as its value, and the key is a string, then
        // append the item's key to $this->key array, to keep track of the current loop's hierarchy.
        if ($node->value instanceof Node\Expr\Array_ && $node->key instanceof String_) {
            $this->key[] = $node->key->value;

            return;
        }

        // If the array item contains a string as its value, translate the item's value and
        // if the array item's key is a string, we have reached the end of this path in the array's shape,
        // append the tracked key plus the current array item's key to the list of keys that were translated.
        if ($node->value instanceof String_ && $node->key instanceof String_) {
            $key = join('.', [...$this->key, $node->key->value]);

            $translatedValue = $this->override[$key]
                ?? $this->translationEngine->translate('en', 'fr', [$node->value->value])[0];

            // Use the translation engine (passed in the constructor) to translate the array item's value.
            $node->value->value = $translatedValue;

            $this->translatedArrayKeys[$key] = $translatedValue;
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

        $this->printTranslatedArrayKeys();
    }

    private function printTranslatedArrayKeys(): void
    {
        foreach ($this->translatedArrayKeys as $key => $value) {
            echo "- $key => \"$value\"" . PHP_EOL;
        }
    }
}