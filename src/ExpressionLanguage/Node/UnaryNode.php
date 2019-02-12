<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\Number;

final class UnaryNode implements NodeInterface
{
    /**
     * @var string
     */
    private $operator;

    /**
     * @var NodeInterface
     */
    private $node;

    public function __construct(string $operator, NodeInterface $node)
    {
        $this->operator = $operator;
        $this->node = $node;
    }

    public function evaluate(): Number
    {
        $number = $this->node->evaluate();
        if ('-' === $this->operator) {
            return $number->multiply(Number::fromScalar('-1'));
        }

        return $number;
    }
}
