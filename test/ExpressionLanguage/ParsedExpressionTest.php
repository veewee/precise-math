<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Node\NodeInterface;
use Phpro\PreciseMath\ExpressionLanguage\ParsedExpression;
use PHPUnit\Framework\TestCase;

class ParsedExpressionTest extends TestCase
{
    public function testValueObject(): void
    {
        $node = $this->getMockBuilder(NodeInterface::class)->getMock();
        $expression = new ParsedExpression($rawExpression = 'expression', $node);

        $this->assertSame($rawExpression, $expression->expression());
        $this->assertSame($node, $expression->ast());
    }
}
