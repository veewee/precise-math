<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\Exception;

use Phpro\PreciseMath\Exception\RuntimeException;
use Phpro\PreciseMath\Exception\SyntaxError;

/**
 * @covers \Phpro\PreciseMath\Exception\SyntaxError
 */
class SyntaxErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testThrowable(): void
    {
        $this->expectException(SyntaxError::class);

        throw new SyntaxError('message');
    }

    public function testItIsARuntimeException(): void
    {
        $this->assertInstanceOf(RuntimeException::class, new SyntaxError('message'));
    }

    public function testCanBeConstructedFromExpression(): void
    {
        $error = SyntaxError::fromExpressionCursor(
            $message = 'Invalid character X',
            $cursor = 10,
            $expression = 'someexpression'
        );

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf('%s around position %d for expression `%s`.', $message, $cursor, $expression),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }
}
