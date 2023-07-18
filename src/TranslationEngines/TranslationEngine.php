<?php

namespace BlameButton\PhpLangParser\TranslationEngines;

interface TranslationEngine
{
    public function translate(string $source, string $target, string $content): string|null;
}