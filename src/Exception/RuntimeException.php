<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\Exception;

class RuntimeException extends \RuntimeException
{
    public static function fromException(\Throwable $error): self
    {
        return new self($error->getMessage(), $error->getCode(), $error);
    }
}
