<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\ExpressionLanguage\Collection\FunctionsCollection;
use Phpro\PreciseMath\ExpressionLanguage\Collection\VariablesCollection;
use Phpro\PreciseMath\ExpressionLanguage\Node\NodeInterface;
use Phpro\PreciseMath\Model\PreciseNumber;
use PHPUnit\Framework\TestCase;

abstract class AbstractNodeTest extends TestCase
{
    /**
     * @dataProvider provideEvaluateData
     */
    public function testEvaluate(
        PreciseNumber $expected,
        NodeInterface $node,
        AstContext $astContext = null
    ): void {
        $astContext = $astContext ?? new AstContext(new FunctionsCollection(), new VariablesCollection([]));

        $evaluated = $node->evaluate($astContext);
        $this->assertSame($expected->value(), $evaluated->value());
        $this->assertSame($expected->scale()->value(), $evaluated->scale()->value());
    }

    /**
     * @return NodeInterface[]
     */
    abstract public function provideEvaluateData(): array;
}
