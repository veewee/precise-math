<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\Model\PreciseNumber;

/**
 * @internal
 */
interface NodeInterface
{
    /**
     * Evaluates the node and parses a number.
     */
    public function evaluate(AstContext $astContext): PreciseNumber;
}
