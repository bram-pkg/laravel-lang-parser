<?php

namespace BlameButton\PhpLangParser\TranslationEngines;

class LocalTranslationEngine implements TranslationEngine
{
    /**
     * Translate a string into French
     *
     * @param string $source
     * @param string $target
     * @param string[] $content the original strings to translate
     * @return string|null the translated string
     */
    public function translate(string $source, string $target, array $content): array
    {
        $mapping = [
            'My title' => 'Mon titre',
            'My subtitle' => 'Mon sous-titre',
            'My body' => 'Mon corps',
            'Home' => 'Chez ons',
            'Settings' => 'Paramètres',
        ];

        return array_map(fn ($string) => $mapping[$string] ?? $string, $content);
    }
}