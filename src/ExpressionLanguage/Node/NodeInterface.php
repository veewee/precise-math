<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\PreciseNumber;

interface NodeInterface
{
    /**
     * Evaluates the node and parses a number.
     */
    public function evaluate(): PreciseNumber;
}
