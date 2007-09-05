<?php
/*
 * Limb PHP Framework
 *
 * @link http://limb-project.com
 * @copyright  Copyright &copy; 2004-2007 BIT(http://bit-creative.com)
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 */

/**
 * @property FirstRow
 * @tag_class WactListItemTag
 * @package wact
 * @version $Id$
 */
class WactListRowFirstRowProperty extends WactCompilerProperty
{
  protected $temp_var;
  protected $has_increment = FALSE;

  function generateScopeEntry($code)
  {
    $this->temp_var = $code->getTempVarRef();
    $ListList = $this->context->findParentByClass('WactListListTag');
    $code->writePHP($this->temp_var . ' = ' . $ListList->getComponentRefCode() . '->getOffset();' . "\n");
  }

   function generatePreStatement($code_writer)
   {
    if (!$this->has_increment)
    {
      $this->hasIncrement = TRUE;
      $code_writer->writePHP($this->temp_var . '++;');
    }
   }

  function generateExpression($code)
  {
    if(!$this->has_increment)
      $this->has_increment = TRUE;

    $code->writePHP('(('.$this->temp_var .' == 1)?1:0)');
  }
}

