<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\PreciseNumber;

/**
 * @internal
 */
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

    public function evaluate(): PreciseNumber
    {
        $number = $this->node->evaluate();
        if ('-' === $this->operator) {
            return $number->multiply(PreciseNumber::fromScalar('-1'));
        }

        return $number;
    }
}
