<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\ExpressionLanguage\Collection\FunctionsCollection;
use Phpro\PreciseMath\ExpressionLanguage\Collection\VariablesCollection;
use Phpro\PreciseMath\ExpressionLanguage\Node\VariableNode;
use Phpro\PreciseMath\Model\PreciseNumber;

class VariableNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                PreciseNumber::fromScalar('1.23'),
                new VariableNode('variable1'),
                new AstContext(
                    new FunctionsCollection(),
                    new VariablesCollection(
                        ['variable1' => PreciseNumber::fromScalar('1.23')]
                    )
                ),
            ],
            [
                PreciseNumber::fromScalar('1.23'),
                new VariableNode('variable1'),
                new AstContext(
                    new FunctionsCollection(),
                    new VariablesCollection(
                        ['variable1' => '1.23']
                    )
                ),
            ],
            [
                PreciseNumber::fromScalar('1.23'),
                new VariableNode('variable1'),
                new AstContext(
                    new FunctionsCollection(),
                    new VariablesCollection(
                        ['variable1' => 1.23]
                    )
                ),
            ],
        ];
    }

    public function testEvaluateWithInvalidVariable(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unknown variable "unknownvariable".');
        $node = new VariableNode('unknownvariable');
        $node->evaluate(new AstContext(
            new FunctionsCollection(),
            new VariablesCollection([])
        ));
    }
}
