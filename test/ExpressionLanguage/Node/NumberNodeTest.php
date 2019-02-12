<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Node\NumberNode;
use Phpro\PreciseMath\Model\PreciseNumber;

class NumberNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                PreciseNumber::fromScalar('1.23'),
                new NumberNode(PreciseNumber::fromScalar('1.23')),
            ],
            [
                PreciseNumber::fromScalar('-1.234'),
                new NumberNode(PreciseNumber::fromScalar('-1.234')),
            ],
        ];
    }
}
