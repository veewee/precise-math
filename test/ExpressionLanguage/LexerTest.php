<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Lexer;
use Phpro\PreciseMath\ExpressionLanguage\Token;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Phpro\PreciseMath\ExpressionLanguage\Lexer
 */
class LexerTest extends TestCase
{
    /**
     * @dataProvider provideExpressions
     */
    public function testTokenValues(string $expression, array $tokens): void
    {
        $lexer = new Lexer();
        $stream = $lexer->tokenize($expression);

        $normalizedExpression = str_replace(["\r", "\n", "\t", "\v", "\f"], ' ', $expression);
        $this->assertSame($normalizedExpression, $stream->expression());

        foreach ($tokens as $token) {
            $this->assertEquals($token, $stream->current());
            $stream->next();
        }

        $this->assertTrue($stream->isEOF());
        $this->assertSame($stream->current()->cursor(), mb_strlen($normalizedExpression) + 1);
    }

    /**
     * @dataProvider provideInvalidExpressions
     */
    public function testSyntaxErrors(string $expression): void
    {
        $this->expectException(SyntaxError::class);

        $lexer = new Lexer();
        $lexer->tokenize($expression);
    }

    public function provideExpressions()
    {
        return [
            [
                '      ',
                [],
            ],
            [
                "\r\n\t\v\f",
                [],
            ],
            [
                '10',
                [
                    new Token(Token::NUMBER_TYPE, '10', 1),
                ],
            ],
            [
                '10.50',
                [
                    new Token(Token::NUMBER_TYPE, '10.50', 1),
                ],
            ],
            [
                '2.2E-5',
                [
                    new Token(Token::NUMBER_TYPE, '2.2E-5', 1),
                ],
            ],
            [
                '2.2E+5',
                [
                    new Token(Token::NUMBER_TYPE, '2.2E+5', 1),
                ],
            ],
            [
                '+',
                [
                    new Token(Token::OPERATOR_TYPE, '+', 1),
                ],
            ],
            [
                '-',
                [
                    new Token(Token::OPERATOR_TYPE, '-', 1),
                ],
            ],
            [
                '*',
                [
                    new Token(Token::OPERATOR_TYPE, '*', 1),
                ],
            ],
            [
                '/',
                [
                    new Token(Token::OPERATOR_TYPE, '/', 1),
                ],
            ],
            [
                '%',
                [
                    new Token(Token::OPERATOR_TYPE, '%', 1),
                ],
            ],
            [
                '^',
                [
                    new Token(Token::OPERATOR_TYPE, '^', 1),
                ],
            ],
            [
                '()',
                [
                    new Token(Token::PUNCTUATION_TYPE, '(', 1),
                    new Token(Token::PUNCTUATION_TYPE, ')', 2),
                ],
            ],
            [
                'myVariable',
                [
                    new Token(Token::NAME_TYPE, 'myVariable', 1),
                ],
            ],
            [
                '10.23 + 45',
                [
                    new Token(Token::NUMBER_TYPE, '10.23', 1),
                    new Token(Token::OPERATOR_TYPE, '+', 7),
                    new Token(Token::NUMBER_TYPE, '45', 9),
                ],
            ],
            [
                '((10.23 + 45) / 2.2E-5)',
                [
                    new Token(Token::PUNCTUATION_TYPE, '(', 1),
                    new Token(Token::PUNCTUATION_TYPE, '(', 2),
                    new Token(Token::NUMBER_TYPE, '10.23', 3),
                    new Token(Token::OPERATOR_TYPE, '+', 9),
                    new Token(Token::NUMBER_TYPE, '45', 11),
                    new Token(Token::PUNCTUATION_TYPE, ')', 13),
                    new Token(Token::OPERATOR_TYPE, '/', 15),
                    new Token(Token::NUMBER_TYPE, '2.2E-5', 17),
                    new Token(Token::PUNCTUATION_TYPE, ')', 23),
                ],
            ],
            [
                '(x - y)^2',
                [
                    new Token(Token::PUNCTUATION_TYPE, '(', 1),
                    new Token(Token::NAME_TYPE, 'x', 2),
                    new Token(Token::OPERATOR_TYPE, '-', 4),
                    new Token(Token::NAME_TYPE, 'y', 6),
                    new Token(Token::PUNCTUATION_TYPE, ')', 7),
                    new Token(Token::OPERATOR_TYPE, '^', 8),
                    new Token(Token::NUMBER_TYPE, '2', 9),
                ],
            ],
        ];
    }

    public function provideInvalidExpressions(): array
    {
        return [
            ['('],
            ['(()'],
            [')'],
            ['())'],
            ['$phpVariable'],
            ['ünknôwnSìmböl'],
        ];
    }
}
