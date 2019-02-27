<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Collection;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\Model\PreciseNumber;

final class VariablesCollection
{
    /**
     * @var PreciseNumber[]
     */
    private $variables;

    public function __construct(array $variables)
    {
        $this->variables = array_map(
            function ($variable): PreciseNumber {
                return $variable instanceof PreciseNumber ? $variable : PreciseNumber::fromScalar($variable);
            },
            $variables
        );
    }

    public function fetchByName(string $name): PreciseNumber
    {
        if (!array_key_exists($name, $this->variables)) {
            throw SyntaxError::unknownVariable($name, $this->variables);
        }

        return $this->variables[$name];
    }
}
