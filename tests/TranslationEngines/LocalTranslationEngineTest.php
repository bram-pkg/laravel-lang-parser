<?php

namespace Tests\TranslationEngines;

use BlameButton\PhpLangParser\TranslationEngines\LocalTranslationEngine;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \BlameButton\PhpLangParser\TranslationEngines\LocalTranslationEngine
 */
class LocalTranslationEngineTest extends TestCase
{
    /**
     * @covers ::translate
     */
    public function testItTranslatesTitle()
    {
        $engine = new LocalTranslationEngine();

        $this->assertEquals(
            'Mon titre',
            $engine->translate('en', 'fr', 'My title')
        );
    }

    /**
     * @covers ::translate
     */
    public function testItReturnsInputWhenNotMapped()
    {
        $engine = new LocalTranslationEngine();

        $this->assertEquals(
            'Something that is unmapped',
            $engine->translate('en', 'fr', 'Something that is unmapped')
        );

    }
}
