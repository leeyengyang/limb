<?php
/**
 * Limb Web Application Framework
 *
 * @link http://limb-project.com
 *
 * @copyright  Copyright &copy; 2004-2007 BIT
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 * @version    $Id: lmbTreeItemsNestingMaker.class.php 5631 2007-04-11 13:03:43Z pachanga $
 * @package    tree
 */
lmb_require('limb/datasource/src/lmbIteratorDecorator.class.php');

class lmbTreeItemsNestingMaker extends lmbIteratorDecorator
{
  protected $node_field = 'id';
  protected $parent_field = 'parent_id';

  function setNodeField($name)
  {
    $this->node_field = $name;
  }

  function setParentField($name)
  {
    $this->parent_field = $name;
  }

  function getArray()//quick fix
  {
    $this->rewind();
    return parent :: getArray();
  }

  function rewind()
  {
    parent :: rewind();

    if($this->iterator->valid())
    {
      $nested_array = array();
      self :: _doMakeNested($this->iterator, $nested_array);
      $iterator = new lmbIterator($nested_array);
    }
    else
      $iterator = new lmbIterator();

    $this->iterator = $iterator;

    return $this->iterator->rewind();
  }

  function _doMakeNested($rs, &$nested_array, $parent_id=null, $level=0)
  {
    $prev_item_id = null;

    while($rs->valid())
    {
      $item = $rs->current();

      if($level == 0 && $item->get($this->parent_field) !== $prev_item_id)
        $parent_id = $item->get($this->parent_field);

      if($item->get($this->parent_field) == $parent_id)
      {
        $nested_array[] = $item->export();
        $rs->next();
      }
      elseif($item->get($this->parent_field) === $prev_item_id)
      {
        $new_nested =& $nested_array[sizeof($nested_array) - 1]['children'];
        self :: _doMakeNested($rs, $new_nested, $prev_item_id, $level + 1);
      }
      else
        return;

      $prev_item_id = $item->get($this->node_field);
    }
  }
}

?>