<?php

namespace Application\Util\DoctrineExtension;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer;

/**
 * Class IfElse
 *
 * @package Application\Util\DoctrineExtension
 */
class IfElse extends FunctionNode
{
    private $expr = [];

    /**
     * @param \Doctrine\ORM\Query\Parser $parser
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr[] = $parser->ConditionalExpression();
        for ($i = 0; $i < 2; $i++) {
            $parser->match(Lexer::T_COMMA);
            $this->expr[] = $parser->ArithmeticExpression();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return sprintf(
            'IF(%s, %s, %s)',
            $sqlWalker->walkConditionalExpression($this->expr[0]),
            $sqlWalker->walkArithmeticPrimary($this->expr[1]),
            $sqlWalker->walkArithmeticPrimary($this->expr[2])
        );
    }
}