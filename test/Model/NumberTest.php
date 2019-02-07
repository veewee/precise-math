<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\Model;

use BCMathExtended\BC;
use Phpro\PreciseMath\Exception\RuntimeException;
use Phpro\PreciseMath\Model\Number;
use Phpro\PreciseMath\Model\Scale;

/**
 * @covers \Phpro\PreciseMath\Model\Number
 */
class NumberTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructorWithScale(): void
    {
        $number = Number::fromScalar($value = '12.3456', new Scale($scale = 5));
        $this->assertSame($value, $number->value());
        $this->assertSame($scale, $number->scale()->value());
    }

    public function testConstructorWithoutScale(): void
    {
        $number = Number::fromScalar($value = '12.3456', $scale = null);
        $this->assertSame($value, $number->value());
        $this->assertEquals(Scale::fromNumberValue($number), $number->scale());
    }

    public function testAddNumber(): void
    {
        $number1 = Number::fromScalar($value1 = '12.345', $scale1 = new Scale(2));
        $number2 = Number::fromScalar($value2 = '23.456', $scale2 = new Scale(3));

        $result = $number1->add($number2);
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertNotSame($number2, $result);
        $this->assertSame(BC::add($value1, $value2, $scale2->value()), $result->value());
        $this->assertEquals($scale2, $result->scale());
    }

    public function testCompareNumber(): void
    {
        $number1 = Number::fromScalar($value1 = '1.234', $scale1 = new Scale(2));

        $this->assertSame(-1, $number1->compare(Number::fromScalar('1.24', new Scale(2))));
        $this->assertSame(-1, $number1->compare(Number::fromScalar('1.235', new Scale(3))));
        $this->assertSame(0, $number1->compare(Number::fromScalar('1.233', new Scale(2))));
        $this->assertSame(0, $number1->compare(Number::fromScalar('1.23', new Scale(2))));
        $this->assertSame(0, $number1->compare(Number::fromScalar('1.234', new Scale(3))));
        $this->assertSame(1, $number1->compare(Number::fromScalar('1.233', new Scale(3))));
        $this->assertSame(1, $number1->compare(Number::fromScalar('1.22', new Scale(2))));

        $this->assertTrue($number1->isEqual(Number::fromScalar($value1 = '1.234')));
        $this->assertFalse($number1->isEqual(Number::fromScalar($value1 = '1.233')));

        $this->assertTrue($number1->isGreaterThan(Number::fromScalar($value1 = '1.233')));
        $this->assertFalse($number1->isGreaterThan(Number::fromScalar($value1 = '1.235')));

        $this->assertTrue($number1->isEqualOrGreaterThan(Number::fromScalar($value1 = '1.233')));
        $this->assertTrue($number1->isEqualOrGreaterThan(Number::fromScalar($value1 = '1.234')));
        $this->assertFalse($number1->isEqualOrGreaterThan(Number::fromScalar($value1 = '1.235')));

        $this->assertTrue($number1->isEqualOrLowerThan(Number::fromScalar($value1 = '1.235')));
        $this->assertTrue($number1->isEqualOrLowerThan(Number::fromScalar($value1 = '1.234')));
        $this->assertFalse($number1->isEqualOrLowerThan(Number::fromScalar($value1 = '1.233')));

        $this->assertFalse($number1->isLowerThan(Number::fromScalar($value1 = '1.233')));
        $this->assertTrue($number1->isLowerThan(Number::fromScalar($value1 = '1.235')));
    }

    public function testDivide(): void
    {
        $number1 = Number::fromScalar($value1 = '12.345', $scale1 = new Scale(2));
        $number2 = Number::fromScalar($value2 = '23.456', $scale2 = new Scale(3));

        $result = $number1->divide($number2);
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertNotSame($number2, $result);
        $this->assertSame(BC::div($value1, $value2, $scale2->value()), $result->value());
        $this->assertEquals($scale2, $result->scale());
    }

    public function testDivideByZero(): void
    {
        $this->expectException(RuntimeException::class);
        $number1 = Number::fromScalar($value1 = '1.234', $scale1 = new Scale(2));
        $number1->divide(Number::fromScalar('0.000'));
    }

    public function testModulus(): void
    {
        $number1 = Number::fromScalar($value1 = '12.345', $scale1 = new Scale(2));
        $number2 = Number::fromScalar($value2 = '2.456', $scale2 = new Scale(3));

        $result = $number1->modulus($number2);
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertNotSame($number2, $result);
        $this->assertSame(BC::mod($value1, $value2), $result->value());
        $this->assertEquals($scale2, $result->scale());
    }

    public function testModulusWithZeroDivider(): void
    {
        $this->expectException(RuntimeException::class);
        $number1 = Number::fromScalar($value1 = '1.234', $scale1 = new Scale(2));
        $number1->modulus(Number::fromScalar('0.000'));
    }

    public function testMultiplyNumber(): void
    {
        $number1 = Number::fromScalar($value1 = '12.345', $scale1 = new Scale(2));
        $number2 = Number::fromScalar($value2 = '23.456', $scale2 = new Scale(3));

        $result = $number1->multiply($number2);
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertNotSame($number2, $result);
        $this->assertSame(BC::mul($value1, $value2, $scale2->value()), $result->value());
        $this->assertEquals($scale2, $result->scale());
    }

    public function testPow(): void
    {
        $number1 = Number::fromScalar($value1 = '12.345', $scale1 = new Scale(2));
        $number2 = Number::fromScalar($value2 = '2', $scale2 = new Scale(3));

        $result = $number1->pow($number2);
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertNotSame($number2, $result);
        $this->assertSame(BC::pow($value1, $value2, $scale2->value()), $result->value());
        $this->assertEquals($scale2, $result->scale());
    }

    public function testPowMod(): void
    {
        $number1 = Number::fromScalar($value = '12.345', $scale2 = new Scale(2));
        $exponent = Number::fromScalar($exponentValue = '2', $scale3 = new Scale(3));
        $modulus = Number::fromScalar($modulusValue = '2', $scale3);

        $result = $number1->powMod($exponent, $modulus);
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertNotSame($exponent, $result);
        $this->assertNotSame($modulus, $result);
        $this->assertSame(BC::powMod($value, $exponentValue, $modulusValue, $scale3->value()), $result->value());
        $this->assertEquals($scale3, $result->scale());
    }

    public function testSqrtNumber(): void
    {
        $number1 = Number::fromScalar($value1 = '12.345', $scale1 = new Scale(2));

        $result = $number1->sqrt();
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertSame(BC::sqrt($value1, $scale1->value()), $result->value());
        $this->assertSame($scale1, $result->scale());
    }

    public function testSqrtNumberOfNegativeNumber(): void
    {
        $this->expectException(RuntimeException::class);
        $number1 = Number::fromScalar($value1 = '-12.345', $scale1 = new Scale(2));
        $number1->sqrt();
    }

    public function testSubtractNumber(): void
    {
        $number1 = Number::fromScalar($value1 = '12.345', $scale1 = new Scale(2));
        $number2 = Number::fromScalar($value2 = '23.456', $scale2 = new Scale(3));

        $result = $number1->subtract($number2);
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertNotSame($number2, $result);
        $this->assertSame(BC::sub($value1, $value2, $scale2->value()), $result->value());
        $this->assertEquals($scale2, $result->scale());
    }

    public function testAbsNumber(): void
    {
        $number1 = Number::fromScalar($value1 = '-12.346', $scale1 = new Scale(3));
        $number2 = Number::fromScalar($value1 = '12.346', $scale1 = new Scale(3));

        $result = $number1->abs();
        $this->assertInstanceOf(Number::class, $result);
        $this->assertNotSame($number1, $result);
        $this->assertSame('12.346', $result->value());
        $this->assertSame($number1->scale(), $result->scale());

        $this->assertSame($number2->value(), $number2->abs()->value());
    }

    public function testRoundNumber(): void
    {
        for ($i = 0; $i <= 9; ++$i) {
            $currentNumber = '12.34'.$i;
            $scale = new Scale(2);
            $result = ($number = Number::fromScalar($currentNumber))->round($scale);

            $this->assertInstanceOf(Number::class, $result);
            $this->assertNotSame($number, $result);
            $this->assertSame(
                BC::round($currentNumber, $scale->value()),
                $result->value()
            );
            $this->assertSame($scale, $result->scale());
        }
    }

    public function testRoundUpNumber(): void
    {
        for ($i = 1; $i <= 9; ++$i) {
            $currentNumber = '12.34'.$i;
            $scale = new Scale(2);
            $result = ($number = Number::fromScalar($currentNumber))->roundUp($scale);

            $this->assertInstanceOf(Number::class, $result);
            $this->assertNotSame($number, $result);
            $this->assertSame(
                BC::roundUp($currentNumber, $scale->value()),
                $result->value()
            );
            $this->assertSame($scale, $result->scale());
        }
    }

    public function testRoundDownNumber(): void
    {
        for ($i = 0; $i <= 9; ++$i) {
            $currentNumber = '12.34'.$i;
            $scale = new Scale(2);
            $result = ($number = Number::fromScalar($currentNumber))->roundDown($scale);

            $this->assertInstanceOf(Number::class, $result);
            $this->assertNotSame($number, $result);
            $this->assertSame(
                BC::roundDown($currentNumber, $scale->value()),
                $result->value()
            );
            $this->assertSame($scale, $result->scale());
        }
    }
}
