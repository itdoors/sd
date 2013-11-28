<?php

namespace SD\CommonBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;

class ArrayDQL extends FunctionNode
{
  public $select = null;

  public function parse(\Doctrine\ORM\Query\Parser $parser)
  {
    $parser->match(Lexer::T_IDENTIFIER);
    $parser->match(Lexer::T_OPEN_PARENTHESIS);
    $this->select = $parser->Subselect();
    $parser->match(Lexer::T_CLOSE_PARENTHESIS);
  }

  public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
  {
    return sprintf('ARRAY(%s)',  $this->select->dispatch($sqlWalker));
  }
}