<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Token;
use Phpro\PreciseMath\ExpressionLanguage\TokenStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Phpro\PreciseMath\ExpressionLanguage\TokenStream
 */
class TokenStreamTest extends TestCase
{
    public function testItKnowsTheExpression(): void
    {
        $stream = new TokenStream([
            $token1 = new Token(Token::NUMBER_TYPE, '1', 0),
            $token2 = Token::eof(1),
        ], $expression = 'expression');

        $this->assertSame($expression, $stream->expression());
    }

    public function testItCanListTokensAsString(): void
    {
        $stream = new TokenStream([
            $token1 = new Token(Token::NUMBER_TYPE, '1', 0),
            $token2 = Token::eof(1),
        ], 'expression');

        $this->assertSame($token1->toString()."\n".$token2->toString(), $stream->toString());
    }

    public function testItKnowsCurrentToken(): void
    {
        $stream = new TokenStream([
            $token1 = new Token(Token::NUMBER_TYPE, '1', 0),
            $token2 = Token::eof(1),
        ], 'expression');

        $this->assertSame($token1, $stream->current());
    }

    public function testItKnowsCurrentTokenWhenPassedArrayIsEmpty(): void
    {
        $stream = new TokenStream([], 'expression');

        $this->assertEquals(Token::eof(0), $stream->current());
    }

    public function testItCanLoadNextToken(): void
    {
        $stream = new TokenStream([
            $token1 = new Token(Token::NUMBER_TYPE, '1', 0),
            $token2 = Token::eof(1),
        ], 'expression');
        $stream->next();

        $this->assertSame($token2, $stream->current());
    }

    public function testItFailsWhenNoNextTokenIsAvailable(): void
    {
        $this->expectException(SyntaxError::class);
        $stream = new TokenStream([], 'expression');
        $stream->next();
    }

    public function testItKnowsIfItIsAtLastPosition(): void
    {
        $stream = new TokenStream([
            $token1 = new Token(Token::NUMBER_TYPE, '1', 0),
            $token2 = Token::eof(1),
        ], 'expression');

        $this->assertFalse($stream->isEOF());
        $stream->next();
        $this->assertTrue($stream->isEOF());
        $this->assertSame($token2, $stream->current());
    }

    /**
     * @dataProvider provideExpectations
     */
    public function testExpectations(array $tokens, string $type, ?string $value, bool $shouldSucceed): void
    {
        $stream = new TokenStream($tokens, 'expression');

        if (!$shouldSucceed) {
            $this->expectException(SyntaxError::class);
        }

        $stream->expect($type, $value);
        $this->assertEquals($stream->current(), Token::eof(2));
    }

    public function provideExpectations(): array
    {
        return [
            [
                [
                    new Token(Token::PUNCTUATION_TYPE, ')', 1),
                    Token::eof(2),
                ],
                Token::PUNCTUATION_TYPE,
                null,
                true,
            ],
            [
                [
                    new Token(Token::PUNCTUATION_TYPE, ')', 1),
                    Token::eof(2),
                ],
                Token::PUNCTUATION_TYPE,
                ')',
                true,
            ],
            [
                [
                    new Token(Token::PUNCTUATION_TYPE, ')', 1),
                    Token::eof(2),
                ],
                Token::PUNCTUATION_TYPE,
                '(',
                false,
            ],
            [
                [
                    new Token(Token::PUNCTUATION_TYPE, ')', 1),
                    Token::eof(2),
                ],
                Token::NUMBER_TYPE,
                null,
                false,
            ],
        ];
    }
}
