<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Node\NumberNode;
use Phpro\PreciseMath\ExpressionLanguage\Node\UnaryNode;
use Phpro\PreciseMath\Model\Number;

class UnaryNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                Number::fromScalar('1.23'),
                new UnaryNode('+', new NumberNode(Number::fromScalar('1.23'))),
            ],
            [
                Number::fromScalar('-1.234'),
                new UnaryNode('-', new NumberNode(Number::fromScalar('1.234'))),
            ],
            [
                Number::fromScalar('1.234'),
                new UnaryNode('-', new NumberNode(Number::fromScalar('-1.234'))),
            ],
            [
                Number::fromScalar('1.234'),
                new UnaryNode('', new NumberNode(Number::fromScalar('1.234'))),
            ],
        ];
    }
}
