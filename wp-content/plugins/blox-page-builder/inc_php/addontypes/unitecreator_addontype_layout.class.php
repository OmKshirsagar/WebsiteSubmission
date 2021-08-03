<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorAddonType_Layout extends UniteCreatorAddonType{
	
	const DISPLAYTYPE_TABLE = "table";
	const DISPLAYTYPE_MANAGER = "manager";
	const DISPLAYTYPE_BOTH = "both";
	
	const LAYOUT_PARAMS_TYPE_SCREENSHOT = "screenshot";
	
	public $isTemplate = false, $displayType = self::DISPLAYTYPE_TABLE;
	public $layoutTypeForCategory = "layout", $allowImportFromCatalog = true;
	public $showPageSettings = true, $defaultBlankTemplate = false;
	public $paramsSettingsType = null, $paramSettingsTitle = null, $showParamsTopBarButton = false;
	public $putScreenshotOnGridSave = false;
	public $arrLayoutBrowserAddonTypes = null;
	
	
	/**
	 * init the addon type
	 */
	protected function initChild(){
		
		$this->isLayout = true;
		
		$this->typeName = GlobalsUC::ADDON_TYPE_REGULAR_LAYOUT;
		$this->textSingle = __("Page", BLOXBUILDER_TEXTDOMAIN);
		$this->textPlural = __("Pages", BLOXBUILDER_TEXTDOMAIN);
		
		$this->textShowType = $this->textSingle;
		
		$this->paramsSettingsType = "screenshot";
		$this->paramSettingsTitle = __("Preview Image Settings", BLOXBUILDER_TEXTDOMAIN);
		
		$this->requireCatalogPreview = true;
		$this->allowWebCatalog = false;
		$this->catalogKey = "pages";
		$this->allowManagerWebCatalog = false;
	}
	
	
}
