<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Node\BinaryNode;
use Phpro\PreciseMath\ExpressionLanguage\Node\NumberNode;
use Phpro\PreciseMath\Model\Number;

class BinaryNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                Number::fromScalar('1.23')->add(Number::fromScalar('2.34')),
                new BinaryNode(
                    '+',
                    new NumberNode(Number::fromScalar('1.23')),
                    new NumberNode(Number::fromScalar('2.34'))
                ),
            ],
            [
                Number::fromScalar('1.23')->subtract(Number::fromScalar('2.34')),
                new BinaryNode(
                    '-',
                    new NumberNode(Number::fromScalar('1.23')),
                    new NumberNode(Number::fromScalar('2.34'))
                ),
            ],
            [
                Number::fromScalar('1.23')->multiply(Number::fromScalar('2.34')),
                new BinaryNode(
                    '*',
                    new NumberNode(Number::fromScalar('1.23')),
                    new NumberNode(Number::fromScalar('2.34'))
                ),
            ],
            [
                Number::fromScalar('1.23')->divide(Number::fromScalar('2.34')),
                new BinaryNode(
                    '/',
                    new NumberNode(Number::fromScalar('1.23')),
                    new NumberNode(Number::fromScalar('2.34'))
                ),
            ],
            [
                Number::fromScalar('1.23')->modulus(Number::fromScalar('2.34')),
                new BinaryNode(
                    '%',
                    new NumberNode(Number::fromScalar('1.23')),
                    new NumberNode(Number::fromScalar('2.34'))
                ),
            ],
            [
                Number::fromScalar('1.23')->pow(Number::fromScalar('2.34')),
                new BinaryNode(
                    '^',
                    new NumberNode(Number::fromScalar('1.23')),
                    new NumberNode(Number::fromScalar('2.34'))
                ),
            ],
        ];
    }

    public function testEvaluateWithInvalidOperator(): void
    {
        $this->expectException(SyntaxError::class);
        $node = new BinaryNode(
            'unknownoperator',
            new NumberNode(Number::fromScalar('1.23')),
            new NumberNode(Number::fromScalar('2.34'))
        );
        $node->evaluate();
    }
}
