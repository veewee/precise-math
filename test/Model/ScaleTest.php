<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\Model;

use BCMathExtended\BC;
use Phpro\PreciseMath\Model\PreciseNumber;
use Phpro\PreciseMath\Model\Scale;
use PHPUnit\Framework\TestCase;

class ScaleTest extends TestCase
{
    public function testConstructor(): void
    {
        $scale = new Scale($scaleValue = 5);
        $this->assertSame($scaleValue, $scale->value());
    }

    public function testDefaultFactory(): void
    {
        $scale = Scale::default();
        $this->assertSame(BC::getScale(), $scale->value());
    }

    public function testCreateFromNumberValue(): void
    {
        $number = PreciseNumber::fromScalar('12.12345');
        $scale = Scale::fromNumberValue($number);
        $this->assertSame(5, $scale->value());
    }

    public function testLargest(): void
    {
        $result = (new Scale(1))->largest(new Scale(2), new Scale(4), $largest = new Scale(5));

        $this->assertInstanceOf(Scale::class, $result);
        $this->assertNotSame($largest, $result);
        $this->assertSame($largest->value(), $result->value());
    }

    public function testSmallest(): void
    {
        $result = ($smallest = new Scale(1))->smallest(new Scale(2), new Scale(4), new Scale(5));

        $this->assertInstanceOf(Scale::class, $result);
        $this->assertNotSame($smallest, $result);
        $this->assertSame($smallest->value(), $result->value());
    }
}
