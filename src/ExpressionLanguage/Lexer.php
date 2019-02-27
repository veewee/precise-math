<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;

/**
 * @internal
 */
final class Lexer
{
    public function tokenize(string $expression): TokenStream
    {
        $expression = str_replace(["\r", "\n", "\t", "\v", "\f"], ' ', $expression);
        $cursor = 0;
        $tokens = [];
        $brackets = [];
        $end = \mb_strlen($expression);

        while ($cursor < $end) {
            if (' ' === $expression[$cursor]) {
                ++$cursor;
                continue;
            }

            if (preg_match('/[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?/A', $expression, $match, 0, $cursor)) {
                // numbers: float, int + scientific notation
                $number = $match[0];
                $tokens[] = new Token(Token::NUMBER_TYPE, $number, $cursor + 1);
                $cursor += \mb_strlen($match[0]);
            } elseif ('(' === $expression[$cursor]) {
                // Opening parenthesis
                $brackets[] = [$expression[$cursor], $cursor];
                $tokens[] = new Token(Token::PUNCTUATION_TYPE, $expression[$cursor], $cursor + 1);
                ++$cursor;
            } elseif (')' === $expression[$cursor]) {
                // Closing parenthesis
                if (empty($brackets)) {
                    throw SyntaxError::unexpectedCharacter($expression[$cursor], $cursor, $expression);
                }

                array_pop($brackets);
                $tokens[] = new Token(Token::PUNCTUATION_TYPE, $expression[$cursor], $cursor + 1);
                ++$cursor;
            } elseif (false !== mb_strpos('+-*/%^', $expression[$cursor])) {
                // Operators
                $tokens[] = new Token(Token::OPERATOR_TYPE, $expression[$cursor], $cursor + 1);
                ++$cursor;
            } elseif (',' === $expression[$cursor]) {
                // punctuation
                $tokens[] = new Token(Token::PUNCTUATION_TYPE, $expression[$cursor], $cursor + 1);
                ++$cursor;
            } elseif (preg_match('/[a-zA-Z_][a-zA-Z0-9_]*/A', $expression, $match, 0, $cursor)) {
                // variable names
                $tokens[] = new Token(Token::NAME_TYPE, $match[0], $cursor + 1);
                $cursor += \mb_strlen($match[0]);
            } else {
                throw SyntaxError::unexpectedCharacter($expression[$cursor], $cursor, $expression);
            }
        }

        $tokens[] = Token::eof($cursor + 1);
        if (!empty($brackets)) {
            [$expect, $bracketCursor] = array_pop($brackets);

            throw SyntaxError::unclosedParenthesis($expect, $bracketCursor, $expression);
        }

        return new TokenStream($tokens, $expression);
    }
}
