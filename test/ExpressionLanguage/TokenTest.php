<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    /**
     * @dataProvider provideTokens
     */
    public function testTokenValues(string $type, string $value, int $cursor): void
    {
        $token = new Token($type, $value, $cursor);

        $this->assertSame($type, $token->type());
        $this->assertSame($value, $token->value());
        $this->assertSame($cursor, $token->cursor());
        $this->assertSame(sprintf('%3d %-11s %s', $cursor, mb_strtoupper($type), $value), $token->toString());

        $this->assertTrue($token->test($type));
        $this->assertTrue($token->test($type, $value));
    }

    public function testTokenForSpecificTypeAndValue(): void
    {
        $token = new Token(Token::OPERATOR_TYPE, '+', 1);
        $this->assertTrue($token->test(Token::OPERATOR_TYPE));
        $this->assertFalse($token->test(Token::NUMBER_TYPE));
        $this->assertTrue($token->test(Token::OPERATOR_TYPE, '+'));
        $this->assertFalse($token->test(Token::OPERATOR_TYPE, '-'));
    }

    public function testEofNamedConstructor(): void
    {
        $token = Token::eof($cursor = 10);
        $this->assertSame(Token::EOF_TYPE, $token->type());
        $this->assertSame($cursor, $token->cursor());
        $this->assertSame('', $token->value());
    }

    public function provideTokens()
    {
        return [
            [Token::NUMBER_TYPE, '2', 1],
            [Token::NUMBER_TYPE, '2.2323', 2],
            [Token::PUNCTUATION_TYPE, '(', 4],
            [Token::PUNCTUATION_TYPE, ')', 5],
            [Token::OPERATOR_TYPE, '+', 6],
            [Token::NAME_TYPE, 'someVariable', 8],
            [Token::EOF_TYPE, '', 7],
            ['unknown', '', 10],
        ];
    }
}
