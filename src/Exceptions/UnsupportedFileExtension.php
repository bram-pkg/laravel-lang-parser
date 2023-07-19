<?php

namespace BlameButton\PhpLangParser\Exceptions;

use Exception;
use Throwable;

class UnsupportedFileExtension extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("File extension \"$message\" is not supported.", $code, $previous);
    }
}