<?php

namespace BlameButton\PhpLangParser\TranslationEngines;

interface TranslationEngine
{
    public function translate(string $source, string $target, array $content): array;
}