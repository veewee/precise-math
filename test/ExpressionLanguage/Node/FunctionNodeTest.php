<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\ExpressionLanguage\Collection\FunctionsCollection;
use Phpro\PreciseMath\ExpressionLanguage\Collection\VariablesCollection;
use Phpro\PreciseMath\ExpressionLanguage\MathFunction;
use Phpro\PreciseMath\ExpressionLanguage\Node\FunctionNode;
use Phpro\PreciseMath\ExpressionLanguage\Node\NumberNode;
use Phpro\PreciseMath\Model\PreciseNumber;
use Phpro\PreciseMath\Model\Scale;

class FunctionNodeTest extends AbstractNodeTest
{
    public function provideEvaluateData(): array
    {
        return [
            [
                PreciseNumber::fromScalar('1.23'),
                new FunctionNode('generateNumber', []),
                new AstContext(
                    new FunctionsCollection(
                        new MathFunction('generateNumber', function () {
                            return PreciseNumber::fromScalar('1.23');
                        })
                    ),
                    new VariablesCollection([])
                ),
            ],
            [
                PreciseNumber::fromScalar('3.0', new Scale(1)),
                new FunctionNode('multiply', [
                    new NumberNode(PreciseNumber::fromScalar('1.5')),
                    new NumberNode(PreciseNumber::fromScalar('2')),
                ]),
                new AstContext(
                    new FunctionsCollection(
                        new MathFunction('multiply', function (PreciseNumber $a, PreciseNumber $b) {
                            return $a->multiply($b);
                        })
                    ),
                    new VariablesCollection([])
                ),
            ],
        ];
    }

    public function testEvaluateWithInvalidFunction(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unknown function "unknownFunction".');
        $node = new FunctionNode('unknownFunction', []);
        $node->evaluate(new AstContext(
            new FunctionsCollection(),
            new VariablesCollection([])
        ));
    }
}
