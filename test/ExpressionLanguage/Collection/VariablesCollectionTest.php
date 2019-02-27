<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\Collection;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Collection\VariablesCollection;
use Phpro\PreciseMath\Model\PreciseNumber;
use PHPUnit\Framework\TestCase;

class VariablesCollectionTest extends TestCase
{
    public function testCollectionConstructor(): void
    {
        $collection = new VariablesCollection([
            'var1' => 123.23,
            'var2' => PreciseNumber::fromScalar('1.23'),
        ]);

        $this->assertEquals(PreciseNumber::fromScalar(123.23), $collection->fetchByName('var1'));
        $this->assertEquals(PreciseNumber::fromScalar('1.23'), $collection->fetchByName('var2'));
    }

    public function testInvalidFetch(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unknown variable "doesNotExist".');

        $collection = new VariablesCollection([]);
        $collection->fetchByName('doesNotExist');
    }

    public function testInvalidFetchWithGuess(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unknown variable "doesNtExist". Did you mean "doesNotExist"?');

        $collection = new VariablesCollection(['doesNotExist' => '1']);
        $collection->fetchByName('doesNtExist');
    }
}
