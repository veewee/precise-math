<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\Number;

final class NumberNode implements NodeInterface
{
    /**
     * @var Number
     */
    private $value;

    public function __construct(Number $value)
    {
        $this->value = $value;
    }

    public function evaluate(): Number
    {
        return $this->value;
    }
}
