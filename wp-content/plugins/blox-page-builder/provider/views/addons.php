<?php

// no direct access
defined('BLOXBUILDER_INC') or die;

class UniteCreatorAddonsViewProvider extends UniteCreatorAddonsView{
	
	protected $showButtons = true;
	protected $showHeader = true;
	
	
	/**
	 * addons view provider
	 */
	public function __construct(){
		$headerTitle = __("Manage My Addons", BLOXBUILDER_TEXTDOMAIN);
		
		$this->initAddonType();
		
		$objManager = new UniteCreatorManagerAddons();
		$objManager->init();
		$objManager->setAddonType($this->addonType);
		
		
		require HelperUC::getPathTemplate("addons");
		
	}
	
}
