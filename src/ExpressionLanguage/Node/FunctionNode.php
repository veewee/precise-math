<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\Model\PreciseNumber;

/**
 * @internal
 */
final class FunctionNode implements NodeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var NodeInterface[]
     */
    private $arguments;

    public function __construct(string $name, array $arguments)
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    public function evaluate(AstContext $astContext): PreciseNumber
    {
        $mathFunction = $astContext->functions()->fetchByName($this->name);
        $arguments = array_map(
            function (NodeInterface $argument) use ($astContext): PreciseNumber {
                return $argument->evaluate($astContext);
            },
            $this->arguments
        );

        return $mathFunction->evaluate(...$arguments);
    }
}
