<?php

namespace SD\CommonBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * SelectNextHandlingMessageDateDQL
 */
class SelectNextHandlingMessageDateDQL extends FunctionNode
{
    public $valueFirst = null;
    public $valueSecond = null;

    /**
     * {@inheritDoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->valueFirst = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->valueSecond = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * {@inheritDoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            'select_next_handling_message_date(%d, %d)',
            $this->valueFirst->dispatch($sqlWalker),
            $this->valueSecond->dispatch($sqlWalker)
        );
    }
}
