<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\Exception;

final class SyntaxError extends RuntimeException
{
    public static function fromExpressionCursor(string $message, int $cursor, string $expression): self
    {
        return new self(
            sprintf('%s around position %d for expression `%s`.', $message, $cursor, $expression)
        );
    }
}
