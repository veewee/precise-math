<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;

/**
 * @internal
 */
final class TokenStream
{
    /**
     * @var Token
     */
    private $current;

    /**
     * @var Token[]
     */
    private $tokens;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var string
     */
    private $expression;

    public function __construct(array $tokens, string $expression)
    {
        $this->tokens = $tokens;
        $this->current = $tokens[0] ?? Token::eof(0);
        $this->expression = $expression;
    }

    public function toString(): string
    {
        return implode("\n", array_map(function (Token $token) {
            return $token->toString();
        }, $this->tokens));
    }

    public function current(): Token
    {
        return $this->current;
    }

    public function next(): void
    {
        ++$this->position;
        if (!isset($this->tokens[$this->position])) {
            throw SyntaxError::fromExpressionCursor(
                'Unexpected end of expression',
                $this->current->cursor(),
                $this->expression
            );
        }
        $this->current = $this->tokens[$this->position];
    }

    public function expect(string $type, ?string $value = null, ?string $message = null): void
    {
        $token = $this->current;
        if (!$token->test($type, $value)) {
            throw SyntaxError::unexpectedTokenFromExpectation(
                $token,
                $this->expression,
                $type,
                $value,
                $message
            );
        }
        $this->next();
    }

    public function isEOF(): bool
    {
        return Token::EOF_TYPE === $this->current->type();
    }

    public function expression(): string
    {
        return $this->expression;
    }
}
