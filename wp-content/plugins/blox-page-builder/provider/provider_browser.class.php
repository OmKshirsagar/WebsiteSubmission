<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorBrowser extends UniteCreatorBrowserWork{
	
	/**
	 * constructor
	 */
	public function __construct(){
		parent::__construct();

		$urlLicense = HelperUC::getViewUrl(GlobalsUC::VIEW_LICENSE);
		
		$this->textBuy = __("Activate Blox", BLOXBUILDER_TEXTDOMAIN);
		$this->textHoverProAddon = __("This addon is available<br>when blox is activated.", BLOXBUILDER_TEXTDOMAIN);
		$this->urlBuy = $urlLicense;
	}
	
}