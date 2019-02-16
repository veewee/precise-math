<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\Model\PreciseNumber;

/**
 * @internal
 */
class NameNode implements NodeInterface
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function evaluate(array $variables): PreciseNumber
    {
        if (!array_key_exists($this->name, $variables)) {
            throw SyntaxError::unknownVariable($this->name, $variables);
        }

        $data = $variables[$this->name];

        return $data instanceof PreciseNumber ? $data : PreciseNumber::fromScalar($data);
    }
}
