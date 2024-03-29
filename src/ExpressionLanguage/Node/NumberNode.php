<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\Model\PreciseNumber;

/**
 * @internal
 */
final class NumberNode implements NodeInterface
{
    /**
     * @var PreciseNumber
     */
    private $value;

    public function __construct(PreciseNumber $value)
    {
        $this->value = $value;
    }

    public function evaluate(AstContext $astContext): PreciseNumber
    {
        return $this->value;
    }
}
