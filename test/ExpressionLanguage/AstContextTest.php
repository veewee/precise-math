<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\ExpressionLanguage\AstContext;
use Phpro\PreciseMath\ExpressionLanguage\Collection\FunctionsCollection;
use Phpro\PreciseMath\ExpressionLanguage\Collection\VariablesCollection;
use PHPUnit\Framework\TestCase;

class AstContextTest extends TestCase
{
    public function testValueObject(): void
    {
        $context = new AstContext(
            $functions = new FunctionsCollection(),
            $variables = new VariablesCollection([])
        );

        $this->assertSame($functions, $context->functions());
        $this->assertSame($variables, $context->variables());
    }
}
