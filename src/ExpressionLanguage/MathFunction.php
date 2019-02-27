<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Model\PreciseNumber;

final class MathFunction
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $evaluator;

    public function __construct(string $name, callable $evaluator)
    {
        $this->name = $name;
        $this->evaluator = $evaluator;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function evaluate(PreciseNumber ...$arguments): PreciseNumber
    {
        return ($this->evaluator)(...$arguments);
    }
}
