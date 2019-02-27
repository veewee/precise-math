<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Collection\FunctionsCollection;
use Phpro\PreciseMath\ExpressionLanguage\Collection\VariablesCollection;
use Phpro\PreciseMath\Model\PreciseNumber;

final class MathLanguage
{
    /**
     * @var FunctionsCollection
     */
    private $functions;

    public function __construct()
    {
        $this->functions = FunctionsCollection::default();
    }

    public function registerFunction(MathFunction $function): self
    {
        $this->functions = $this->functions->withFunction($function);

        return $this;
    }

    public function evaluate(string $expression, array $variables): PreciseNumber
    {
        $parsed = $this->parse($expression);

        return $parsed->ast()->evaluate(new AstContext(
            $this->functions,
            new VariablesCollection($variables)
        ));
    }

    private function parse(string $expression): ParsedExpression
    {
        $lexer = new Lexer();
        $parser = new Parser();

        $tokens = $lexer->tokenize($expression);

        return new ParsedExpression($expression, $parser->parse($tokens));
    }
}
