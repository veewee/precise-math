<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Node\NodeInterface;
use Phpro\PreciseMath\Model\Number;
use PHPUnit\Framework\TestCase;

abstract class AbstractNodeTest extends TestCase
{
    /**
     * @dataProvider provideEvaluateData
     */
    public function testEvaluate(Number $expected, NodeInterface $node): void
    {
        $this->assertSame($expected->value(), $node->evaluate()->value());
        $this->assertSame($expected->scale()->value(), $node->evaluate()->scale()->value());
    }

    /**
     * @return NodeInterface[]
     */
    abstract public function provideEvaluateData(): array;
}
