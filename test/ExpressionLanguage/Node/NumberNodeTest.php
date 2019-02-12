<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Node\NumberNode;
use Phpro\PreciseMath\Model\Number;

class NumberNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                Number::fromScalar('1.23'),
                new NumberNode(Number::fromScalar('1.23')),
            ],
            [
                Number::fromScalar('-1.234'),
                new NumberNode(Number::fromScalar('-1.234')),
            ],
        ];
    }
}
