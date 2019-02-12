<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\Model;

use BCMathExtended\BC;

final class Scale
{
    /**
     * @var int
     */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function default(): self
    {
        return new self(BC::getScale());
    }

    public static function fromNumberValue(PreciseNumber $number): self
    {
        return new self(BC::getDecimalsLengthFromNumber($number->value()));
    }

    public function value(): int
    {
        return $this->value;
    }

    public function largest(Scale ...$scales): self
    {
        return new self(
            max(
                $this->value(),
                ...array_map(
                    function (Scale $item): int {
                        return $item->value();
                    },
                    $scales
                )
            )
        );
    }

    public function smallest(Scale ...$scales): self
    {
        return new self(
            min(
                $this->value(),
                ...array_map(
                    function (Scale $item): int {
                        return $item->value();
                    },
                    $scales
                )
            )
        );
    }
}
