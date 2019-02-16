<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Node\BinaryNode;
use Phpro\PreciseMath\ExpressionLanguage\Node\NumberNode;
use Phpro\PreciseMath\Model\PreciseNumber;

class BinaryNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                PreciseNumber::fromScalar('1.23')->add(PreciseNumber::fromScalar('2.34')),
                new BinaryNode(
                    '+',
                    new NumberNode(PreciseNumber::fromScalar('1.23')),
                    new NumberNode(PreciseNumber::fromScalar('2.34'))
                ),
            ],
            [
                PreciseNumber::fromScalar('1.23')->subtract(PreciseNumber::fromScalar('2.34')),
                new BinaryNode(
                    '-',
                    new NumberNode(PreciseNumber::fromScalar('1.23')),
                    new NumberNode(PreciseNumber::fromScalar('2.34'))
                ),
            ],
            [
                PreciseNumber::fromScalar('1.23')->multiply(PreciseNumber::fromScalar('2.34')),
                new BinaryNode(
                    '*',
                    new NumberNode(PreciseNumber::fromScalar('1.23')),
                    new NumberNode(PreciseNumber::fromScalar('2.34'))
                ),
            ],
            [
                PreciseNumber::fromScalar('1.23')->divide(PreciseNumber::fromScalar('2.34')),
                new BinaryNode(
                    '/',
                    new NumberNode(PreciseNumber::fromScalar('1.23')),
                    new NumberNode(PreciseNumber::fromScalar('2.34'))
                ),
            ],
            [
                PreciseNumber::fromScalar('1.23')->modulus(PreciseNumber::fromScalar('2.34')),
                new BinaryNode(
                    '%',
                    new NumberNode(PreciseNumber::fromScalar('1.23')),
                    new NumberNode(PreciseNumber::fromScalar('2.34'))
                ),
            ],
            [
                PreciseNumber::fromScalar('1.23')->pow(PreciseNumber::fromScalar('2.34')),
                new BinaryNode(
                    '^',
                    new NumberNode(PreciseNumber::fromScalar('1.23')),
                    new NumberNode(PreciseNumber::fromScalar('2.34'))
                ),
            ],
        ];
    }

    public function testEvaluateWithInvalidOperator(): void
    {
        $this->expectException(SyntaxError::class);
        $node = new BinaryNode(
            'unknownoperator',
            new NumberNode(PreciseNumber::fromScalar('1.23')),
            new NumberNode(PreciseNumber::fromScalar('2.34'))
        );
        $node->evaluate([]);
    }
}
