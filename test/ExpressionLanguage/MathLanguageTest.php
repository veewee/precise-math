<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\MathFunction;
use Phpro\PreciseMath\ExpressionLanguage\MathLanguage;
use Phpro\PreciseMath\Model\PreciseNumber;
use PHPUnit\Framework\TestCase;

class MathLanguageTest extends TestCase
{
    /**
     * @dataProvider provideEvaluationExpressions
     */
    public function testExpressionEvaluations(
        PreciseNumber $expected,
        string $expression,
        array $functions,
        array $variables
    ): void {
        $mathLanguage = new MathLanguage();
        foreach ($functions as $function) {
            $mathLanguage = $mathLanguage->registerFunction($function);
        }

        $this->assertEquals($expected, $mathLanguage->evaluate($expression, $variables));
    }

    public function provideEvaluationExpressions(): array
    {
        return [
            [
                PreciseNumber::fromScalar('1.50'),
                'a * b',
                [],
                [
                    'a' => '0.75',
                    'b' => PreciseNumber::fromScalar('2.0'),
                ],
            ],
            [
                PreciseNumber::fromScalar('1.8'),
                'round(1.77, 1)',
                [],
                [],
            ],
            [
                PreciseNumber::fromScalar('1.8'),
                'roundUp(1.77, 1)',
                [],
                [],
            ],
            [
                PreciseNumber::fromScalar('1.7'),
                'roundDown(1.77, 1)',
                [],
                [],
            ],
            [
                PreciseNumber::fromScalar('1.77'),
                'myFunction(1.77)',
                [
                    new MathFunction('myFunction', function (PreciseNumber $x): PreciseNumber {
                        return $x;
                    }),
                ],
                [],
            ],
        ];
    }
}
