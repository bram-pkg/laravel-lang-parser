<?php

namespace BlameButton\PhpLangParser\Printers;

use PhpParser\PrettyPrinter\Standard as StandardPrettyPrinter;

class PrettyPrinter extends StandardPrettyPrinter
{
    protected function pMaybeMultiline(array $nodes, bool $trailingComma = false): string
    {
        return $this->pCommaSeparatedMultiline($nodes, $trailingComma) . $this->nl;
    }
}