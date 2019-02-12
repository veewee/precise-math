<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\Number;

interface NodeInterface
{
    /**
     * Evaluates the node and parses a number.
     */
    public function evaluate(): Number;
}
