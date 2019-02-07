<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\Number;

final class BinaryNode implements NodeInterface
{
    /**
     * @var NodeInterface[]
     */
    private $nodes;

    /**
     * @var array
     */
    private $attributes;

    public function __construct(string $operator, NodeInterface $left, NodeInterface $right)
    {
        $this->nodes = [
            'left' => $left,
            'right' => $right,
        ];
        $this->attributes = [
            'operator' => $operator,
        ];
    }

    public function nodes(): array
    {
        return $this->nodes;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function evaluate(): Number
    {
        $operator = $this->attributes['operator'];
        $left = $this->nodes['left']->evaluate();
        $right = $this->nodes['right']->evaluate();
        switch ($operator) {
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
        }

        // TODO: throw
    }
}
