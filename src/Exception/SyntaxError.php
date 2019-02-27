<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\Exception;

use Phpro\PreciseMath\ExpressionLanguage\Token;

final class SyntaxError extends RuntimeException
{
    public static function fromExpressionCursor(string $message, int $cursor, string $expression): self
    {
        return new self(
            sprintf('%s around position %d for expression `%s`.', $message, $cursor, $expression)
        );
    }

    public static function unexpectedCharacter(string $character, int $cursor, string $expression): self
    {
        return self::fromExpressionCursor('Unexpected character "'.$character.'"', $cursor, $expression);
    }

    public static function unexpectedToken(Token $token, string $expression): self
    {
        return self::fromExpressionCursor(
            sprintf('Unexpected token "%s" of value "%s"', $token->type(), $token->value()),
            $token->cursor(),
            $expression
        );
    }

    public static function unexpectedTokenFromExpectation(
        Token $token,
        string $expression,
        string $expectedType,
        ?string $expectedValue = null,
        ?string $additionalInfo = null
    ): self {
        return self::fromExpressionCursor(
            sprintf(
                '%sUnexpected token "%s" of value "%s" ("%s" expected%s)',
                $additionalInfo ? $additionalInfo.'. ' : '',
                $token->type(),
                $token->value(),
                $expectedType,
                $expectedValue ? sprintf(' with value "%s"', $expectedValue) : ''
            ),
            $token->cursor(),
            $expression
        );
    }

    public static function unclosedParenthesis(string $openingParenthesis, int $openingCursor, string $expression): self
    {
        return self::fromExpressionCursor('Unclosed "'.$openingParenthesis.'"', $openingCursor, $expression);
    }

    public static function unknownOperator(string $operator): self
    {
        return new self('The parser does not know how to handle the operator "'.$operator.'".');
    }

    public static function unknownVariable(string $name, array $variables): self
    {
        return new self('Unknown variable "'.$name.'".'.self::guessName($name, array_keys($variables)));
    }

    public static function unknownFunction(string $name, array $functions): self
    {
        return new self('Unknown function "'.$name.'".'.self::guessName($name, array_keys($functions)));
    }

    private static function guessName(string $name, array $proposals): string
    {
        $minScore = INF;
        foreach ($proposals as $proposal) {
            $distance = levenshtein($name, $proposal);
            if ($distance < $minScore) {
                $guess = $proposal;
                $minScore = $distance;
            }
        }

        if (!isset($guess) || $minScore >= 3) {
            return '';
        }

        return sprintf(' Did you mean "%s"?', $guess);
    }
}
