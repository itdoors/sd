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
    public $value1 = null;
    public $value2 = null;

    /**
     * parse
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->value1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->value2 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * getSql
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('select_next_handling_message_date(%d, %d)',
                $this->value1->dispatch($sqlWalker),
                $this->value2->dispatch($sqlWalker)
        );
    }
}