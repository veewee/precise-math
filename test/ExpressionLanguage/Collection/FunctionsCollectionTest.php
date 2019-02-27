<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\Collection;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Collection\FunctionsCollection;
use Phpro\PreciseMath\ExpressionLanguage\MathFunction;
use PHPUnit\Framework\TestCase;

class FunctionsCollectionTest extends TestCase
{
    public function testCollectionConstructor(): void
    {
        $function1 = new MathFunction('function', function (): void {});
        $function2 = new MathFunction('function', function (): void {});

        $collection = new FunctionsCollection($function1, $function2);
        $this->assertEquals($function2, $collection->fetchByName('function'));
    }

    public function testCollectionWithFunction(): void
    {
        $function1 = new MathFunction('function', function (): void {});
        $function2 = new MathFunction('function', function (): void {});

        $collection = new FunctionsCollection();
        $newCollection = $collection
            ->withFunction($function1)
            ->withFunction($function2);

        $this->assertNotSame($newCollection, $collection);
        $this->assertEquals($function2, $newCollection->fetchByName('function'));

        $this->expectException(SyntaxError::class);
        $collection->fetchByName('function');
    }

    public function testInvalidFetch(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unknown function "doesNotExist".');

        $collection = new FunctionsCollection();
        $collection->fetchByName('doesNotExist');
    }

    public function testInvalidFetchWithGuess(): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Unknown function "doesNtExist". Did you mean "doesNotExist"?');

        $function = new MathFunction('doesNotExist', function (): void {});
        $collection = new FunctionsCollection($function);
        $collection->fetchByName('doesNtExist');
    }

    public function testDefaultFunctionSet(): void
    {
        $collection = FunctionsCollection::default();
        $this->assertInstanceOf(MathFunction::class, $collection->fetchByName('round'));
        $this->assertInstanceOf(MathFunction::class, $collection->fetchByName('roundUp'));
        $this->assertInstanceOf(MathFunction::class, $collection->fetchByName('roundDown'));
    }
}
