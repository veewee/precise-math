<?php

declare(strict_types=1);

namespace Phpro\PreciseMath\ExpressionLanguage;

use Phpro\PreciseMath\Exception\SyntaxError;
use Phpro\PreciseMath\ExpressionLanguage\Node\NodeInterface;
use Phpro\PreciseMath\Model\PreciseNumber;

/**
 * This parser implements a "Precedence climbing" algorithm.
 *
 * @internal
 *
 * @see http://www.engr.mun.ca/~theo/Misc/exp_parsing.htm
 * @see http://en.wikipedia.org/wiki/Operator-precedence_parser
 */
final class Parser
{
    /**
     * @var array
     */
    private static $binaryOperators = [
        '+' => ['precedence' => 3],
        '-' => ['precedence' => 3],
        '*' => ['precedence' => 6],
        '/' => ['precedence' => 6],
        '^' => ['precedence' => 6],
        '%' => ['precedence' => 15],
    ];

    /**
     * @var array
     */
    private static $unaryOperators = [
        '-' => ['precedence' => 20],
        '+' => ['precedence' => 20],
    ];

    public function parse(TokenStream $stream): NodeInterface
    {
        $node = $this->parseExpression($stream);

        if (!$stream->isEOF()) {
            throw SyntaxError::unexpectedToken($stream->current(), $stream->expression());
        }

        return $node;
    }

    private function parseExpression(TokenStream $stream, int $precedence = 0): NodeInterface
    {
        $node = $this->getPrimary($stream);
        $token = $stream->current();
        while ($token->test(Token::OPERATOR_TYPE)
               && array_key_exists($token->value(), self::$binaryOperators)
               && self::$binaryOperators[$token->value()]['precedence'] >= $precedence
        ) {
            $operatorInfo = self::$binaryOperators[$token->value()];
            $stream->next();
            $right = $this->parseExpression($stream, $operatorInfo['precedence']);
            $node = new Node\BinaryNode($token->value(), $node, $right);
            $token = $stream->current();
        }

        return $node;
    }

    private function getPrimary(TokenStream $stream): NodeInterface
    {
        $token = $stream->current();
        if ($token->test(Token::OPERATOR_TYPE) && array_key_exists($token->value(), self::$unaryOperators)) {
            $operatorInfo = self::$unaryOperators[$token->value()];
            $stream->next();
            $expression = $this->parseExpression($stream, $operatorInfo['precedence']);

            return new Node\UnaryNode($token->value(), $expression);
        }

        if ($token->test(Token::PUNCTUATION_TYPE, '(')) {
            $stream->next();
            $expression = $this->parseExpression($stream);
            $stream->expect(Token::PUNCTUATION_TYPE, ')', 'An opened parenthesis is not properly closed');

            return $expression;
        }

        return $this->parsePrimaryExpression($stream);
    }

    private function parsePrimaryExpression(TokenStream $stream): NodeInterface
    {
        $token = $stream->current();

        if ($token->test(Token::NUMBER_TYPE)) {
            $stream->next();

            return new Node\NumberNode(PreciseNumber::fromScalar($token->value()));
        }

        if ($token->test(Token::NAME_TYPE)) {
            $stream->next();

            if ($stream->current()->test(Token::PUNCTUATION_TYPE, '(')) {
                return new Node\FunctionNode($token->value(), $this->parseArguments($stream));
            }

            return new Node\VariableNode($token->value());
        }

        throw SyntaxError::unexpectedToken($token, $stream->expression());
    }

    private function parseArguments(TokenStream $stream): array
    {
        $arguments = [];
        $stream->expect(Token::PUNCTUATION_TYPE, '(', 'A list of arguments must begin with an opening parenthesis');
        while (!$stream->current()->test(Token::PUNCTUATION_TYPE, ')')) {
            if (!empty($arguments)) {
                $stream->expect(Token::PUNCTUATION_TYPE, ',', 'Arguments must be separated by a comma');
            }
            $arguments[] = $this->parseExpression($stream);
        }
        $stream->expect(Token::PUNCTUATION_TYPE, ')', 'A list of arguments must be closed by a parenthesis');

        return $arguments;
    }
}
