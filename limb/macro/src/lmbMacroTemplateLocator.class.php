<?php
/*
 * Limb PHP Framework
 *
 * @link http://limb-project.com 
 * @copyright  Copyright &copy; 2004-2007 BIT(http://bit-creative.com)
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html 
 */

lmb_require('limb/fs/toolkit.inc.php');
lmb_require('limb/fs/src/lmbFs.class.php');
lmb_require('limb/macro/src/lmbMacroTemplateLocatorInterface.interface.php');

/**
 * class lmbMacroTemplateLocator.
 *
 * @package macro
 * @version $Id$
 */
class lmbMacroTemplateLocator implements lmbMacroTemplateLocatorInterface 
{
  protected $config;
  protected $cache_dir;
  protected $scan_dirs;
  protected $toolkit;

  function __construct($config)
  {
    $this->config = $config;
    $this->toolkit = lmbToolkit :: instance();
  }

  function locateSourceTemplate($file_name)
  {    
    if(!lmbFs :: isPathAbsolute($file_name))
      return $this->toolkit->tryFindFileByAlias($file_name, $this->config['tpl_scan_dirs'], 'macro');
    elseif(file_exists($file_name))
      return $file_name;
  }

  function locateCompiledTemplate($file_name)
  {
    return $this->config['cache_dir'] . '/' . md5($file_name) . '.php';
  }
}

