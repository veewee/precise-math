<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\Model\PreciseNumber;

/**
 * @internal
 */
final class VariableNode implements NodeInterface
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function evaluate(AstContext $astContext): PreciseNumber
    {
        return $astContext->variables()->fetchByName($this->name);
    }
}
