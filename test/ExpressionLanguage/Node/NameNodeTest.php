<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Node\NameNode;
use Phpro\PreciseMath\Model\PreciseNumber;

class NameNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                PreciseNumber::fromScalar('1.23'),
                new NameNode('variable1'),
                ['variable1' => PreciseNumber::fromScalar('1.23')],
            ],
            [
                PreciseNumber::fromScalar('1.23'),
                new NameNode('variable1'),
                ['variable1' => '1.23'],
            ],
            [
                PreciseNumber::fromScalar('1.23'),
                new NameNode('variable1'),
                ['variable1' => 1.23],
            ],
        ];
    }

    public function testEvaluateWithInvalidVariable(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unknown variable "unknownvariable".');
        $node = new NameNode('unknownvariable');
        $node->evaluate([]);
    }
}
