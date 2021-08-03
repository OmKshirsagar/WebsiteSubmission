<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS http://unitecms.net
 * @copyright Copyright (c) 2017 UniteCMS
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined ('BLOXBUILDER_INC') or die ('restricted aceess');

class UniteCreatorPageBuilderUC extends UniteCreatorPluginBase{
	
	protected $extraInitParams = array();
	
	private $version = "1.0";
	private $pluginName = "blox_page_builder";
	private $title;
	private $description;

	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$pathPlugin = dirname(__FILE__)."/";
		
		parent::__construct($pathPlugin);
		
		$this->title = __("Blox Page Builder", BLOXBUILDER_TEXTDOMAIN);
		$this->description = "The Best Page Builder for WordPress";
		
		//$this->extraInitParams["silent_mode"] = true;
				
		$this->init();
	}
	
	/**
	 * run admin
	 */
	public function runAdmin(){
		
		$this->includeCommonFiles();
		
		require_once GlobalsUC::$pathPlugin . "unitecreator_admin.php";
		require_once GlobalsUC::$pathProvider . "provider_admin.class.php";
		require_once $this->pathPlugin."blox_admin.class.php";
		
		$mainFilepath = GlobalsUC::$pathPlugin."blox_builder.php";
		
		new UniteProviderCoreAdminUC_Blox($mainFilepath);
	}

	
	/**
	 * run front 
	 */
	public function runFront(){
		
		$this->includeCommonFiles();
		require_once GlobalsUC::$pathProvider . "provider_front.class.php";
		require_once $this->pathPlugin . "blox_front.class.php";
		
		$mainFilepath = GlobalsUC::$pathPlugin."blox_builder.php";
		
		new UniteProviderCoreFrontUC_Blox($mainFilepath);
		
	}
	
	
	/**
	 * include files
	 */
	protected function includeCommonFiles(){
		
		require_once $this->pathPlugin . 'helper_provider_core.class.php';
		
	}
	
	/**
	 * init the plugin
	 */
	protected function init(){
		
		$this->register($this->pluginName, $this->title, $this->version, $this->description, $this->extraInitParams);
						
	}
	
}


//run the plugin
new UniteCreatorPageBuilderUC();
		
