<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\ExpressionLanguage\Node\NumberNode;
use Phpro\PreciseMath\ExpressionLanguage\Node\UnaryNode;
use Phpro\PreciseMath\Model\PreciseNumber;

class UnaryNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                PreciseNumber::fromScalar('1.23'),
                new UnaryNode('+', new NumberNode(PreciseNumber::fromScalar('1.23'))),
            ],
            [
                PreciseNumber::fromScalar('-1.234'),
                new UnaryNode('-', new NumberNode(PreciseNumber::fromScalar('1.234'))),
            ],
            [
                PreciseNumber::fromScalar('1.234'),
                new UnaryNode('-', new NumberNode(PreciseNumber::fromScalar('-1.234'))),
            ],
            [
                PreciseNumber::fromScalar('1.234'),
                new UnaryNode('', new NumberNode(PreciseNumber::fromScalar('1.234'))),
            ],
        ];
    }
}
