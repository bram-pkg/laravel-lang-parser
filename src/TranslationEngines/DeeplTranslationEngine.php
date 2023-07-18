<?php

namespace BlameButton\PhpLangParser\TranslationEngines;

class DeeplTranslationEngine implements TranslationEngine
{

    public function translate(string $source, string $target, array $content): array
    {
        return $content;
    }
}