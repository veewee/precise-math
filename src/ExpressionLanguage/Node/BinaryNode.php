<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\Model\PreciseNumber;

/**
 * @internal
 */
final class BinaryNode implements NodeInterface
{
    /**
     * @var string
     */
    private $operator;

    /**
     * @var NodeInterface
     */
    private $left;

    /**
     * @var NodeInterface
     */
    private $right;

    public function __construct(string $operator, NodeInterface $left, NodeInterface $right)
    {
        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }

    public function evaluate(AstContext $astContext): PreciseNumber
    {
        $left = $this->left->evaluate($astContext);
        $right = $this->right->evaluate($astContext);
        switch ($this->operator) {
            case '+':
                return $left->add($right);
            case '-':
                return $left->subtract($right);
            case '*':
                return $left->multiply($right);
            case '/':
                return $left->divide($right);
            case '%':
                return $left->modulus($right);
            case '^':
                return $left->pow($right);
        }

        throw SyntaxError::unknownOperator($this->operator);
    }
}
