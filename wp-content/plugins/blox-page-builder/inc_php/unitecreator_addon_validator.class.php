<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorAddonValidator{
	
	
	protected $objAddon;
	protected $arrMessages = array();
	
	
	/**
	 * validate inited
	 */
	protected function validateInited(){
		
		if(empty($this->objAddon))
			UniteFunctionsUC::throwError("Addon is not inited");
	}
	
	
	/**
	 * init addon
	 */
	protected function init($objAddon){
		$this->objAddon = $objAddon;
		$this->arrMessages = array();
	}
	
	/**
	 * get message array
	 */
	public function getArrMessages(){

		return($this->arrMessages);
	}
	
	/**
	 * return if addon missing defaults
	 */
	protected function isAddonMissingDefaults(){
		
		$hasItems = $this->objAddon->isHasItems();
		if($hasItems == false)
			return(false);
		
		$arrDefaults = $this->objAddon->getDefaultData();
		$items = UniteFunctionsUC::getVal($arrDefaults, "items");
		
		if(empty($items))
			return(true);
		else
			return(false);
	}
	
	
	/**
	 * check if some of the param missing default value
	 */
	protected function isParamsMissingDefaultValue($arrParams){
		
		if(empty($arrParams))
			return(false);
		
		foreach($arrParams as $param){
			$defaultValue = UniteFunctionsUC::getVal($param, "default_value");
			if(empty($defaultValue))
				return(true);
		}
		
		return(false);
	}
	
	
	/**
	 * return if missing image url
	 */
	protected function isAddonMissingImageUrl(){
		
		$arrImagesParams = $this->objAddon->getParams(UniteCreatorDialogParam::PARAM_IMAGE);
		$arrImagesParamsItems = $this->objAddon->getParamsItems(UniteCreatorDialogParam::PARAM_IMAGE);
		
		$isMissing = $this->isParamsMissingDefaultValue($arrImagesParams);
		if($isMissing == true)
			return(true);
			
		$isMissing = $this->isParamsMissingDefaultValue($arrImagesParamsItems);
		if($isMissing == true)
			return(true);
		
		return(false);
	}
	
	/**
	 * check if addon has non existing includes
	 */
	protected function isHasNonExistingIncludes(){
		
		$arrIncludes = $this->objAddon->getAllRegularIncludesUrls();
		
		$name = $this->objAddon->getName();
		
		
		foreach($arrIncludes as $urlInclude){
			
			$isUnderAssets = HelperUC::isUrlUnderAssets($urlInclude);
			if($isUnderAssets == false)
				continue;
			
			$pathInclude = HelperUC::urlToPath($urlInclude);

			if(empty($pathInclude))
				return(true);
		}
		
		return(false);
	}
	
	/**
	 * validate regular addon
	 */
	protected function validateRegularAddon(){
		
		//check if missing default image
		$isMissingDefaluts = $this->isAddonMissingDefaults();
		if($isMissingDefaluts == true)
			$this->arrMessages[] = __("Missing Default Items!");
		
		//check if missing default url
		$isMissingDefalutImage = $this->isAddonMissingImageUrl();
		if($isMissingDefalutImage == true)
			$this->arrMessages[] = __("Missing Default Image!");
		
		$hasNonExistingIncludes = $this->isHasNonExistingIncludes();
		if($hasNonExistingIncludes == true)
			$this->arrMessages[] = __("Not existing include file!");
		
	}
	
	/**
	 * validate devider
	 */
	protected function validateShapeDevider(){
		
		$html = $this->objAddon->getHtml();
		$pos = strpos($html, 'g fill="#ffffff"');
		if($pos === false)
			$this->arrMessages[] = __("Not valid fill color!");
	}
	
	
	/**
	 * run the validation
	 */
	protected function runValidation(){
		
		$addonType = $this->objAddon->getObjAddonType();
		
		$type = $addonType->typeNameDistinct;
		
		switch($type){
			case GlobalsUC::ADDON_TYPE_BGADDON:
			case GlobalsUC::ADDON_TYPE_REGULAR_ADDON:
				$this->validateRegularAddon();
			break;
			case GlobalsUC::ADDON_TYPE_SHAPE_DEVIDER:
				$this->validateShapeDevider();
			break;
		}
		
		
	}
	
	
	
	/**
	 * get messages html
	 */
	public function getHtmlMessages(){
		
		if(empty($this->arrMessages))
			return("");
		
		$html = "";
		foreach($this->arrMessages as $index => $message){
			
			$class = "";
			if($index > 0)
				$class = " uc-master-second";
			
			if($index > 1)
				$class = " uc-master-third";
			
			$html .= "<div class='uc-manager-addon-validation-message{$class}'>{$message}</div>";
		}
		
		return($html);
	}
	
	
	/**
	 * validate addon, throw error with message if found
	 */
	public function isValid(UniteCreatorAddon $objAddon){
		
		$this->init($objAddon);
		
		$this->runValidation();
		
		if(!empty($this->arrMessages))
			return(false);
		else
			return(true);
	}
	
}
