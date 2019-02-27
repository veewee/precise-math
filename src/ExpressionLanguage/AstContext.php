<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Collection\FunctionsCollection;
use Phpro\PreciseMath\ExpressionLanguage\Collection\VariablesCollection;

final class AstContext
{
    /**
     * @var FunctionsCollection
     */
    private $functions;

    /**
     * @var VariablesCollection
     */
    private $variables;

    public function __construct(FunctionsCollection $functions, VariablesCollection $variables)
    {
        $this->functions = $functions;
        $this->variables = $variables;
    }

    public function functions(): FunctionsCollection
    {
        return $this->functions;
    }

    public function variables(): VariablesCollection
    {
        return $this->variables;
    }
}
