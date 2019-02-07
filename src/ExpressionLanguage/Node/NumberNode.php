<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage\Node;

use Phpro\PreciseMath\Model\Number;

final class NumberNode implements NodeInterface
{
    /**
     * @var NodeInterface[]
     */
    private $nodes;

    /**
     * @var array
     */
    private $attributes;

    public function __construct(Number $number)
    {
        $this->nodes = [];
        $this->attributes = [
            'value' => $number,
        ];
    }

    public function nodes(): array
    {
        return $this->nodes;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function evaluate(): Number
    {
        return $this->attributes()['value'];
    }
}
