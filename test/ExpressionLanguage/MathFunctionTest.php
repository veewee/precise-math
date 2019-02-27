<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\MathFunction;
use Phpro\PreciseMath\Model\PreciseNumber;
use PHPUnit\Framework\TestCase;

class MathFunctionTest extends TestCase
{
    public function testItHasAName(): void
    {
        $function = new MathFunction('name', function (): void {});
        $this->assertSame('name', $function->name());
    }

    public function testItCanEvaluateFunction(): void
    {
        $function = new MathFunction('name', function (PreciseNumber $a, PreciseNumber $b): PreciseNumber {
            return $a->add($b);
        });

        $result = $function->evaluate(PreciseNumber::fromScalar('1.23'), PreciseNumber::fromScalar('2.34'));
        $this->assertSame('3.57', $result->value());
    }
}
