<?php
/**
 * Phergie
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://phergie.org/license
 *
 * @category  Phergie
 * @package   Phergie_Tests
 * @author    Phergie Development Team <team@phergie.org>
 * @copyright 2008-2012 Phergie Development Team (http://phergie.org)
 * @license   http://phergie.org/license New BSD License
 * @link      http://pear.phergie.org/package/Phergie_Tests
 */

/**
 * Unit test suite for Phergie_Plugin_Php.
 *
 * @category Phergie
 * @package  Phergie_Tests
 * @author   Phergie Development Team <team@phergie.org>
 * @license  http://phergie.org/license New BSD License
 * @link     http://pear.phergie.org/package/Phergie_Tests
 */
class Phergie_Plugin_PhpTest extends Phergie_Plugin_TestCase
{
    /**
     * Initializes a Php event.
     *
     * @return void
     */
     private function initializePhpEvent($function) {
     	$args = array(
        	'receiver' => $this->source,
            'text' => 'php ' . $function
        );

        $event = $this->getMockEvent('privmsg', $args);
        $this->plugin->setEvent($event);
    }

	/**
     *	Tests changing the configuration to add a custom
     *	source class for Phergie_Plugin_Php_Source_
     *
     * 	@return void
     */
  	public function testOnLoad()
    {
	   	// set configuration to a custom source class defined below
	   	$this->setConfig('plugin.php.source', 'test');
    	
        $this->plugin->onLoad();        
    }
   
   /**
    *	Checks for an expected output from a custom function description (defined below).
    *
    *	@return void
    */
    public function testOnCommmandPhp()
    {
  		$this->setConfig('plugin.php.source', 'test');
        $this->plugin->onLoad();   
        
		$this->initializePhpEvent('array_map');
        $this->assertEmitsEvent('privmsg', array($this->source, $this->nick. ': hello'));
        $this->plugin->onCommandPhp('array_map');
    	
    	$this->initializePhpEvent('xxxx');
        $this->assertEmitsEvent('notice', array($this->nick, 'Search for function xxxx returned no results.'));
        $this->plugin->onCommandPhp('xxxx');
    }
}


/** 
 * 	Dummy source Class used to test custom configuration in testOnLoad() 
 *	and testOnCommandPhp() tests above.
 *
 */
class Phergie_Plugin_Php_Source_Test implements Phergie_Plugin_Php_Source
{
	 public function __construct($path){}
	 
	 public function findFunction($function)
	 {	
	 	$testArray = array('array_map' => array('description'=> 'hello'));
	 	if(isset($testArray[$function])) {
	 		return $testArray[$function];
	 	} else return false;
	 }
	 
	 protected function buildDatabase($rebuild = false){}
}
