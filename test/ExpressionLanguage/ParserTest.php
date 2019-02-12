<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Lexer;
use Phpro\PreciseMath\ExpressionLanguage\Node;
use Phpro\PreciseMath\ExpressionLanguage\Parser;
use Phpro\PreciseMath\ExpressionLanguage\Token;
use Phpro\PreciseMath\ExpressionLanguage\TokenStream;
use Phpro\PreciseMath\Model\PreciseNumber;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testInvalidTokens(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unexpected token "unknownType" of value "1234" around position 1 for expression `1234`.');

        $parser = new Parser();
        $parser->parse(new TokenStream([
            new Token('unknownType', '1234', 1),
            Token::eof(2),
        ], '1234'));
    }

    public function testExpectEofAtEnd(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unexpected token "number" of value "1234" around position 6 for expression `1234 1234`.');

        $parser = new Parser();
        $parser->parse(new TokenStream([
            new Token(Token::NUMBER_TYPE, '1234', 1),
            new Token(Token::NUMBER_TYPE, '1234', 6),
            Token::eof(10),
        ], '1234 1234'));
    }

    public function testPrecedences(): void
    {
        $reflected = new \ReflectionClass(Parser::class);
        $unwind = function (array $info): int {
            return $info['precedence'];
        };

        $reflectedBinaryOperators = $reflected->getProperty('binaryOperators');
        $reflectedBinaryOperators->setAccessible(true);

        $reflectedUnaryOperators = $reflected->getProperty('unaryOperators');
        $reflectedUnaryOperators->setAccessible(true);

        $binaryOperators = array_map($unwind, $reflectedBinaryOperators->getValue());
        $unaryOperators = array_map($unwind, $reflectedUnaryOperators->getValue());

        $this->assertEquals($binaryOperators['+'], $binaryOperators['-']);

        $this->assertGreaterThan($binaryOperators['+'], $binaryOperators['*']);
        $this->assertEquals($binaryOperators['*'], $binaryOperators['/']);
        $this->assertEquals($binaryOperators['*'], $binaryOperators['^']);
        $this->assertEquals($binaryOperators['/'], $binaryOperators['^']);

        $this->assertGreaterThan($binaryOperators['*'], $binaryOperators['%']);

        $this->assertGreaterThan($binaryOperators['%'], $unaryOperators['+']);
        $this->assertEquals($unaryOperators['+'], $unaryOperators['-']);
    }

    /**
     * @dataProvider provideParseData
     */
    public function testParse(Node\NodeInterface $expectedNode, string $expression): void
    {
        $lexer = new Lexer();
        $parser = new Parser();
        $this->assertEquals($expectedNode, $parser->parse($lexer->tokenize($expression)));
    }

    public function provideParseData(): array
    {
        return [
            [
                new Node\NumberNode(PreciseNumber::fromScalar('12.345')),
                '12.345',
            ],
            [
                new Node\UnaryNode('-', new Node\NumberNode(PreciseNumber::fromScalar('12.345'))),
                '-12.345',
            ],
            [
                new Node\UnaryNode('+', new Node\NumberNode(PreciseNumber::fromScalar('12.345'))),
                '+12.345',
            ],
            [
                new Node\BinaryNode(
                    '-',
                    new Node\NumberNode(PreciseNumber::fromScalar('3')),
                    new Node\NumberNode(PreciseNumber::fromScalar('3'))
                ),
                '3 - 3',
            ],
            [
                new Node\BinaryNode('-',
                    new Node\NumberNode(PreciseNumber::fromScalar('3')),
                    new Node\BinaryNode(
                        '*',
                        new Node\NumberNode(PreciseNumber::fromScalar('3')),
                        new Node\NumberNode(PreciseNumber::fromScalar('2'))
                    )
                ),
                '3 - 3 * 2',
            ],
            [
                new Node\BinaryNode('*',
                    new Node\BinaryNode(
                        '-',
                        new Node\NumberNode(PreciseNumber::fromScalar('3')),
                        new Node\NumberNode(PreciseNumber::fromScalar('3'))
                    ),
                    new Node\NumberNode(PreciseNumber::fromScalar('2'))
                ),
                '((3 - 3) * 2)',
            ],
            [
                new Node\UnaryNode(
                    '-',
                    new Node\BinaryNode('*',
                        new Node\BinaryNode(
                            '-',
                            new Node\NumberNode(PreciseNumber::fromScalar('3')),
                            new Node\NumberNode(PreciseNumber::fromScalar('3'))
                        ),
                        new Node\NumberNode(PreciseNumber::fromScalar('2'))
                    )
                ),
                '-((3 - 3) * 2)',
            ],
            [
                new Node\BinaryNode('+',
                    new Node\NumberNode(PreciseNumber::fromScalar('1')),
                    new Node\BinaryNode(
                        '+',
                        new Node\NumberNode(PreciseNumber::fromScalar('2')),
                        new Node\NumberNode(PreciseNumber::fromScalar('3'))
                    )
                ),
                '1 + 2 + 3',
            ],
            [
                new Node\BinaryNode('+',
                    new Node\NumberNode(PreciseNumber::fromScalar('1')),
                    new Node\BinaryNode(
                        '-',
                        new Node\NumberNode(PreciseNumber::fromScalar('2')),
                        new Node\BinaryNode(
                            '+',
                            new Node\NumberNode(PreciseNumber::fromScalar('3')),
                            new Node\NumberNode(PreciseNumber::fromScalar('4'))
                        )
                    )
                ),
                '1 + 2 - 3 + 4',
            ],
            [
                new Node\BinaryNode('+',
                    new Node\UnaryNode('-', new Node\NumberNode(PreciseNumber::fromScalar('1'))),
                    new Node\BinaryNode(
                        '-',
                        new Node\NumberNode(PreciseNumber::fromScalar('2')),
                        new Node\BinaryNode(
                            '*',
                            new Node\NumberNode(PreciseNumber::fromScalar('3')),
                            new Node\BinaryNode(
                                '/',
                                new Node\NumberNode(PreciseNumber::fromScalar('4')),
                                new Node\BinaryNode(
                                    '^',
                                    new Node\BinaryNode(
                                        '%',
                                        new Node\NumberNode(PreciseNumber::fromScalar('5')),
                                        new Node\NumberNode(PreciseNumber::fromScalar('6'))
                                    ),
                                    new Node\NumberNode(PreciseNumber::fromScalar('7'))
                                )
                            )
                        )
                    )
                ),
                '-1 + 2 - 3 * 4 / 5%6 ^ 7',
            ],
        ];
    }
}
