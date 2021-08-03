<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');


class UniteCreatorAddonsView{
	
	protected $showButtons = true;
	protected $showHeader = true;
	protected $addonType, $objAddonType;
	
	
	/**
	 * constructor
	 */
	public function __construct(){

		$this->initAddonType();
		
		$this->init();
		$this->putHtml();
	}
	
	/**
	 * init addon types
	 */
	protected function initAddonType(){
		
		if(!empty($this->addonType))
			return(false);

		$this->addonType = UniteFunctionsUC::getGetVar("addontype", null, UniteFunctionsUC::SANITIZE_KEY);
		$this->objAddonType = UniteCreatorAddonType::getAddonTypeObject($this->addonType);
		
	}
	
	
	/**
	 * get header text
	 * @return unknown
	 */
	protected function getHeaderText(){
		
		if(!empty($this->objAddonType->managerHeaderPrefix))
			GlobalsUC::$alterViewHeaderPrefix = $this->objAddonType->managerHeaderPrefix;
		
		$headerTitle = __("Manage", BLOXBUILDER_TEXTDOMAIN)." ".$this->objAddonType->textPlural;
		
		return($headerTitle);
	}
	
	
	/**
	 * init the view
	 */
	protected function init(){
				
	}
	
	
	/**
	 * constructor
	 */
	protected function putHtml(){
		
		$view = UniteCreatorAdmin::getView();
		
		if($view == GlobalsUC::VIEW_ADDONS_LIST)
			UniteProviderAdminUC::validateSingleView($view);
		
		$objManager = new UniteCreatorManagerAddons();
		$objManager->init($this->addonType);
		
		require HelperUC::getPathTemplate("addons");		
	}

}

$pathProviderAddons = GlobalsUC::$pathProvider."views/addons.php";

if(file_exists($pathProviderAddons) == true){
	require_once $pathProviderAddons;
	new UniteCreatorAddonsViewProvider();
}
else{
	new UniteCreatorAddonsView();
}

