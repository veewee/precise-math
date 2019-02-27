<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\MathFunctions;

use Phpro\PreciseMath\ExpressionLanguage\MathFunction;
use Phpro\PreciseMath\Model\PreciseNumber;
use Phpro\PreciseMath\Model\Scale;

class RoundDown
{
    public static function create(): MathFunction
    {
        return new MathFunction(
            'roundDown',
            function (PreciseNumber $number, PreciseNumber $scale): PreciseNumber {
                return $number->roundDown(new Scale((int) $scale->value()));
            }
        );
    }
}
