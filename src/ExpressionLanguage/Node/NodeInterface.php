<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\Number;

interface NodeInterface
{
    /**
     * Contains a Map of linked nodes.
     *
     * @return NodeInterface[]
     */
    public function nodes(): array;

    /**
     * Contains a Map of metadata attributes.
     */
    public function attributes(): array;

    /**
     * Evaluates the node and parses a number.
     */
    public function evaluate(): Number;
}
