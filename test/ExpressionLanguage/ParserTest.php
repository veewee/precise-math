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
    /**
     * @dataProvider provideInvalidTokenStreams
     */
    public function testInvalidTokenStreams(TokenStream $tokenStream, string $message): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage($message);

        $parser = new Parser();
        $parser->parse($tokenStream);
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
                new Node\NumberNode(PreciseNumber::fromScalar('.2')),
                '.2',
            ],
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
                new Node\VariableNode('x'),
                'x',
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
                new Node\BinaryNode(
                    '-',
                    new Node\NumberNode(PreciseNumber::fromScalar('3')),
                    new Node\VariableNode('x')
                ),
                '3 - x',
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
                            new Node\VariableNode('x')
                        ),
                        new Node\NumberNode(PreciseNumber::fromScalar('2'))
                    )
                ),
                '-((3 - x) * 2)',
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
            [
                new Node\FunctionNode(
                    'my_Function123',
                    [
                        new Node\UnaryNode('-', new Node\NumberNode(PreciseNumber::fromScalar('2'))),
                    ]
                ),
                'my_Function123(-2)',
            ],
            [
                new Node\FunctionNode(
                    'my_Function123',
                    [
                        new Node\BinaryNode(
                            '-',
                            new Node\VariableNode('x'),
                            new Node\VariableNode('y')
                        ),
                        new Node\NumberNode(PreciseNumber::fromScalar('2')),
                    ]
                ),
                'my_Function123(x - y, 2)',
            ],
        ];
    }

    public function provideInvalidTokenStreams(): array
    {
        return [
            [
                new TokenStream(
                    [
                        new Token('unknownType', '1234', 1),
                        Token::eof(2),
                    ],
                    '1234'
                ),
                'Unexpected token "unknownType" of value "1234" around position 1 for expression `1234`.',
            ],
            [
                new TokenStream(
                    [
                        new Token(Token::NUMBER_TYPE, '1234', 1),
                        new Token(Token::NUMBER_TYPE, '1234', 6),
                        Token::eof(10),
                    ],
                    '1234 1234'
                ),
                'Unexpected token "number" of value "1234" around position 6 for expression `1234 1234`.',
            ],
            [
                new TokenStream(
                    [
                        new Token(Token::NUMBER_TYPE, '1', 1),
                        new Token(Token::NUMBER_TYPE, '+', 3), // Invalid type ;-)
                        new Token(Token::NUMBER_TYPE, '1234', 5),
                        Token::eof(9),
                    ],
                    '1 + 1234'
                ),
                'Unexpected token "number" of value "+" around position 3 for expression `1 + 1234`.',
            ],
            [
                new TokenStream(
                    [
                        new Token(Token::NUMBER_TYPE, '+', 1), // Invalid type ;-)
                        new Token(Token::NUMBER_TYPE, '1234', 3),
                        Token::eof(9),
                    ],
                    '+ 1234'
                ),
                'Unexpected token "number" of value "1234" around position 3 for expression `+ 1234`.',
            ],
            [
                new TokenStream(
                    [
                        new Token(Token::NAME_TYPE, 'myFunction', 1),
                        new Token(Token::PUNCTUATION_TYPE, '(', 11),
                        new Token(Token::NAME_TYPE, 'x', 12),
                        Token::eof(13),
                    ],
                    'myFunction(x'
                ),
                'Arguments must be separated by a comma. Unexpected token "end of expression" of value "" ("punctuation" expected with value ",") around position 13 for expression `myFunction(x`.',
            ],
            [
                new TokenStream(
                    [
                        new Token(Token::NAME_TYPE, 'myFunction', 1),
                        new Token(Token::PUNCTUATION_TYPE, '(', 11),
                        new Token(Token::NAME_TYPE, 'x', 12),
                        new Token(Token::NAME_TYPE, 'y', 14),
                        Token::eof(15),
                    ],
                    'myFunction(x y'
                ),
                'Arguments must be separated by a comma. Unexpected token "name" of value "y" ("punctuation" expected with value ",") around position 14 for expression `myFunction(x y`.',
            ],
        ];
    }
}
