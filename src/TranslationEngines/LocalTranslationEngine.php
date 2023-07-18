<?php

namespace BlameButton\PhpLangParser\TranslationEngines;

class LocalTranslationEngine implements TranslationEngine
{
    /**
     * Translate a string into French
     *
     * @param string $source
     * @param string $target
     * @param string $content the original string to translate
     * @return string|null the translated string
     */
    public function translate(string $source, string $target, string $content): string|null
    {
        return [
            'My title' => 'Mon titre',
            'My subtitle' => 'Mon sous-titre',
            'My body' => 'Mon corps',
        ][$content] ?? $content;
    }
}