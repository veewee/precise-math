<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Collection;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\MathFunction;
use Phpro\PreciseMath\ExpressionLanguage\MathFunctions;

final class FunctionsCollection
{
    /**
     * @var MathFunction[]
     */
    private $functions;

    public function __construct(MathFunction ...$functions)
    {
        $this->functions = array_reduce(
            $functions,
            function (array $named, MathFunction $function) {
                $named[$function->name()] = $function;

                return $named;
            },
            []
        );
    }

    public static function default(): self
    {
        return new self(
            MathFunctions\Round::create(),
            MathFunctions\RoundDown::create(),
            MathFunctions\RoundUp::create()
        );
    }

    public function withFunction(MathFunction $function): self
    {
        $new = clone $this;
        $new->functions[$function->name()] = $function;

        return $new;
    }

    public function fetchByName(string $name): MathFunction
    {
        if (!array_key_exists($name, $this->functions)) {
            throw SyntaxError::unknownFunction($name, $this->functions);
        }

        return $this->functions[$name];
    }
}
