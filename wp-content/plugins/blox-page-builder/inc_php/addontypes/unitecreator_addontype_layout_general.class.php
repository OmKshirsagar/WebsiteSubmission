<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorAddonType_Layout_General extends UniteCreatorAddonType_Layout{
	
	
	/**
	 * init the addon type
	 */
	protected function initChild(){
		
		parent::initChild();
		
		$this->typeName = GlobalsUC::ADDON_TYPE_LAYOUT_GENERAL;
		
		$this->isBasicType = false;
		$this->textSingle = __("Layout", BLOXBUILDER_TEXTDOMAIN);
		$this->textPlural = __("General Layouts", BLOXBUILDER_TEXTDOMAIN);
		$this->layoutTypeForCategory = $this->typeName;
		
		$this->textShowType = $this->textSingle;
		$this->displayType = self::DISPLAYTYPE_MANAGER;
		$this->allowImportFromCatalog = true;
		$this->allowDuplicateTitle = false;
		$this->isAutoScreenshot = true;
		$this->allowNoCategory = false;
		$this->allowWebCatalog = false;
		$this->showPageSettings = true;
		$this->defaultBlankTemplate = true;
		$this->enableShortcodes = true;
		
		$this->paramsSettingsType = "screenshot";
		$this->paramSettingsTitle = __("Preview Image Settings", BLOXBUILDER_TEXTDOMAIN);
		$this->putScreenshotOnGridSave = true;
		
	}
	
	
}
