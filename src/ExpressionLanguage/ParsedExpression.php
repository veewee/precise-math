<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\Node\NodeInterface;

final class ParsedExpression
{
    /**
     * @var string
     */
    private $expression;

    /**
     * @var NodeInterface
     */
    private $ast;

    public function __construct(string $expression, NodeInterface $ast)
    {
        $this->expression = $expression;
        $this->ast = $ast;
    }

    public function expression(): string
    {
        return $this->expression;
    }

    public function ast(): NodeInterface
    {
        return $this->ast;
    }
}
