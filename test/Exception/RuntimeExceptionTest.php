<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\Exception;

use Phpro\PreciseMath\Exception\RuntimeException;

/**
 * @covers \Phpro\PreciseMath\Exception\RuntimeException
 */
class RuntimeExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testThrowable(): void
    {
        $this->expectException(RuntimeException::class);

        throw new RuntimeException('some exception');
    }

    public function testCanBeConstructedFromWarning(): void
    {
        $warning = new \Exception($message = 'bcdiv: cannot devide by zero', $code = 132);
        $exception = RuntimeException::fromException($warning);

        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($warning, $exception->getPrevious());
    }
}
