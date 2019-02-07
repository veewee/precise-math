<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\Number;

final class UnaryNode implements NodeInterface
{
    /**
     * @var NodeInterface[]
     */
    private $nodes;

    /**
     * @var array
     */
    private $attributes;

    public function __construct(string $operator, NodeInterface $node)
    {
        $this->nodes = [
            'node' => $node,
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
        $number = $this->nodes['node']->evaluate();
        switch ($this->attributes['operator']) {
            case '-':
                return Number::fromScalar('-'.$number->value(), $number->scale());
        }

        return $number;
    }
}
