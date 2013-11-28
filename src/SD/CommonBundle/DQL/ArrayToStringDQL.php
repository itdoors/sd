<?php

namespace SD\CommonBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;

class ArrayToStringDQL extends FunctionNode
{
  public $array = null;
  public $delimiter = null;

  public function parse(\Doctrine\ORM\Query\Parser $parser)
  {
    $parser->match(Lexer::T_IDENTIFIER);
    $parser->match(Lexer::T_OPEN_PARENTHESIS);
    $this->array = $parser->ArithmeticPrimary();
    $parser->match(Lexer::T_COMMA);
    $this->delimiter = $parser->StringPrimary();

    $parser->match(Lexer::T_CLOSE_PARENTHESIS);
  }

  public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
  {
    return sprintf("array_to_string(%s, ',')",  $this->array->dispatch($sqlWalker));
  }
}