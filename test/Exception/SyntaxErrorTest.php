<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\Exception;

use Phpro\PreciseMath\Exception\RuntimeException;
use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Token;

class SyntaxErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testThrowable(): void
    {
        $this->expectException(SyntaxError::class);

        throw new SyntaxError('message');
    }

    public function testItIsARuntimeException(): void
    {
        $this->assertInstanceOf(RuntimeException::class, new SyntaxError('message'));
    }

    public function testCanBeConstructedFromExpression(): void
    {
        $error = SyntaxError::fromExpressionCursor(
            $message = 'Invalid character X',
            $cursor = 10,
            $expression = 'someexpression'
        );

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf('%s around position %d for expression `%s`.', $message, $cursor, $expression),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnexpectedCharacter(): void
    {
        $error = SyntaxError::unexpectedCharacter(
            $character = 'X',
            $cursor = 1,
            $expression = 'expression'
        );

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unexpected character "%s" around position %d for expression `%s`.',
                $character,
                $cursor,
                $expression
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnexpectedToken(): void
    {
        $error = SyntaxError::unexpectedToken(
            new Token($tokenType = Token::NUMBER_TYPE, $value = '1', $cursor = 1),
            $expression = 'expression'
        );

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unexpected token "%s" of value "%s" around position %d for expression `%s`.',
                $tokenType,
                $value,
                $cursor,
                $expression
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnexpectedTokenFromExpectation(): void
    {
        $error = SyntaxError::unexpectedTokenFromExpectation(
            new Token($tokenType = Token::NUMBER_TYPE, $value = '1', $cursor = 1),
            $expression = 'expression',
            $expectedType = Token::OPERATOR_TYPE,
            $expectedValue = '+',
            $additionalInfo = 'Expected + sign'
        );

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                '%s. Unexpected token "%s" of value "%s" ("%s" expected with value "%s") around position %d for expression `%s`.',
                $additionalInfo,
                $tokenType,
                $value,
                $expectedType,
                $expectedValue,
                $cursor,
                $expression
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnclosedParenthesis(): void
    {
        $error = SyntaxError::unclosedParenthesis(
            $character = '(',
            $cursor = 1,
            $expression = '( expression'
        );

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unclosed "%s" around position %d for expression `%s`.',
                $character,
                $cursor,
                $expression
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnknownOperator(): void
    {
        $error = SyntaxError::unknownOperator($operator = '*^%');

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'The parser does not know how to handle the operator "%s".',
                $operator
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnknownVariable(): void
    {
        $error = SyntaxError::unknownVariable($variable = 'variable', []);

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unknown variable "%s".',
                $variable
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnknownVariableAndGuessTypos(): void
    {
        $error = SyntaxError::unknownVariable($variable = 'someVr', ['someVar' => 1, 'smeVr' => 2, 'SomeVer' => 3]);

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unknown variable "%s". Did you mean "someVar"?',
                $variable
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnknownVariableAndCannotGuessTypos(): void
    {
        $error = SyntaxError::unknownVariable($variable = 'someVar', ['some123Var' => 1]);

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unknown variable "%s".',
                $variable
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnknownFunction(): void
    {
        $error = SyntaxError::unknownFunction($function = 'function', []);

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unknown function "%s".',
                $function
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnknownFunctionAndGuessTypos(): void
    {
        $error = SyntaxError::unknownFunction($function = 'SomeFn', ['SomeFun' => 1, 'SmeFn' => 2, 'SomeFan' => 3]);

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unknown function "%s". Did you mean "SomeFun"?',
                $function
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }

    public function testCanBeConstructedFromUnknownFunctionAndCannotGuessTypos(): void
    {
        $error = SyntaxError::unknownFunction($function = 'someFunc', ['some123Func' => 1]);

        $this->assertInstanceOf(SyntaxError::class, $error);
        $this->assertSame(
            sprintf(
                'Unknown function "%s".',
                $function
            ),
            $error->getMessage()
        );
        $this->assertSame(0, $error->getCode());
        $this->assertNull($error->getPrevious());
    }
}
