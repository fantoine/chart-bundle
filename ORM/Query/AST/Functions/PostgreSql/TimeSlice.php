<?php

namespace Biliboo\ChartBundle\ORM\Query\AST\Functions\PostgreSql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\Lexer;

/**
 * TIMESLICE ::= "TIMESLICE" "(" DateTime ", " SECONDS ")"
 */
class TimeSlice extends FunctionNode
{
    /**
     * @var ArithmeticPrimary
     */
    private $dateExpression;

    /**
     * @var integer
     */
    private $arithmeticExpression;

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->arithmeticExpression = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            'ROUND(EXTRACT(epoch from %s)/%d)',
            $this->dateExpression->dispatch($sqlWalker),
            $sqlWalker->walkArithmeticPrimary($this->arithmeticExpression)
        );
    }
}

