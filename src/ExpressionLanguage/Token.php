<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage;

/**
 * @internal
 */
final class Token
{
    public const NUMBER_TYPE = 'number';
    public const PUNCTUATION_TYPE = 'punctuation';
    public const OPERATOR_TYPE = 'operator';
    public const EOF_TYPE = 'end of expression';
    public const NAME_TYPE = 'name';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $cursor;

    public function __construct(string $type, string $value, int $cursor)
    {
        $this->type = $type;
        $this->value = $value;
        $this->cursor = $cursor;
    }

    public static function eof(int $cursor): self
    {
        return new self(self::EOF_TYPE, '', $cursor);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function cursor(): int
    {
        return $this->cursor;
    }

    public function test(string $type, string $value = null): bool
    {
        return $this->type === $type && (null === $value || $this->value === $value);
    }

    public function toString(): string
    {
        return sprintf('%3d %-11s %s', $this->cursor, mb_strtoupper($this->type), $this->value);
    }
}
