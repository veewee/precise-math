<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\Model;

use BCMathExtended\BC;
use Phpro\PreciseMath\Exception\RuntimeException;

final class Number
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var Scale
     */
    private $scale;

    private function __construct(string $value, Scale $scale = null)
    {
        $this->value = $value;
        $this->scale = $scale ?? Scale::fromNumberValue($this);
    }

    public static function fromScalar($value, Scale $scale = null): self
    {
        return new self(BC::convertScientificNotationToString($value), $scale);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function scale(): Scale
    {
        return $this->scale;
    }

    public function add(Number $number): self
    {
        return $this->runWithErrorHandling(function () use ($number): self {
            $largestScale = $this->scale()->largest($number->scale());

            return new self(BC::add($this->value, $number->value(), $largestScale->value()), $largestScale);
        });
    }

    public function compare(Number $number): int
    {
        return $this->runWithErrorHandling(function () use ($number): int {
            $largestScale = $this->scale()->largest($number->scale());

            return BC::comp($this->value, $number->value(), $largestScale->value());
        });
    }

    public function isGreaterThan(Number $number): bool
    {
        return BC::COMPARE_LEFT_GRATER === $this->compare($number);
    }

    public function isEqualOrGreaterThan(Number $number): bool
    {
        return $this->compare($number) >= BC::COMPARE_EQUAL;
    }

    public function isEqual(Number $number): bool
    {
        return BC::COMPARE_EQUAL === $this->compare($number);
    }

    public function isLowerThan(Number $number): bool
    {
        return BC::COMPARE_RIGHT_GRATER === $this->compare($number);
    }

    public function isEqualOrLowerThan(Number $number): bool
    {
        return $this->compare($number) <= BC::COMPARE_EQUAL;
    }

    public function divide(Number $divisor): self
    {
        return $this->runWithErrorHandling(function () use ($divisor): self {
            $largestScale = $this->scale()->largest($divisor->scale());

            return new self(BC::div($this->value, $divisor->value(), $largestScale->value()), $largestScale);
        });
    }

    public function modulus(Number $divisor): self
    {
        return $this->runWithErrorHandling(function () use ($divisor): self {
            $largestScale = $this->scale()->largest($divisor->scale());

            return new self(BC::mod($this->value, $divisor->value()), $largestScale);
        });
    }

    public function multiply(Number $number): self
    {
        return $this->runWithErrorHandling(function () use ($number): self {
            $largestScale = $this->scale()->largest($number->scale());

            return new self(BC::mul($this->value, $number->value(), $largestScale->value()), $largestScale);
        });
    }

    public function pow(Number $exponent): self
    {
        return $this->runWithErrorHandling(function () use ($exponent): self {
            $largestScale = $this->scale()->largest($exponent->scale());

            return new self(BC::pow($this->value, $exponent->value(), $largestScale->value()), $largestScale);
        });
    }

    public function powMod(Number $exponent, Number $modulus): self
    {
        return $this->runWithErrorHandling(function () use ($exponent, $modulus): self {
            $largestScale = $this->scale()->largest($exponent->scale(), $modulus->scale());

            return new self(
                BC::powMod($this->value, $exponent->value(), $modulus->value(), $largestScale->value()),
                $largestScale
            );
        });
    }

    public function sqrt(): self
    {
        return $this->runWithErrorHandling(
            function (): self {
                return new self(BC::sqrt($this->value, $this->scale()->value()), $this->scale());
            }
        );
    }

    public function subtract(Number $number): self
    {
        return $this->runWithErrorHandling(function () use ($number): self {
            $largestScale = $this->scale()->largest($number->scale());

            return new self(BC::sub($this->value, $number->value(), $largestScale->value()), $largestScale);
        });
    }

    public function abs(): self
    {
        return $this->runWithErrorHandling(function (): self {
            return new self(BC::abs($this->value), $this->scale);
        });
    }

    public function round(Scale $scale): self
    {
        return $this->runWithErrorHandling(function () use ($scale): self {
            return new self(BC::round($this->value, $scale->value()), $scale);
        });
    }

    public function roundUp(Scale $scale): self
    {
        return $this->runWithErrorHandling(function () use ($scale): self {
            return new self(BC::roundUp($this->value, $scale->value()), $scale);
        });
    }

    public function roundDown(Scale $scale): self
    {
        return $this->runWithErrorHandling(function () use ($scale): self {
            return new self(BC::roundDown($this->value, $scale->value()), $scale);
        });
    }

    private function runWithErrorHandling(callable $callback)
    {
        try {
            $result = $callback();
        } catch (\Throwable $error) {
            throw RuntimeException::fromException($error);
        }

        return $result;
    }
}
