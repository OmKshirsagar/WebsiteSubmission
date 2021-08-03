<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorActivationViewProvider extends UniteCreatorActivationView{
	
	const ENABLE_STAND_ALONE = true;
	
	/**
	 * init by envato
	 */
	private function initByEnvato(){
		
		//$this->textAndTemplates = __("In the addons catalog", BLOXBUILDER_TEXTDOMAIN);;
		
		$this->textGoPro = __("Activate Blox Pro", BLOXBUILDER_TEXTDOMAIN);
		
		if(self::ENABLE_STAND_ALONE == true)
			$this->textGoPro = __("Activate Blox Pro - Envato", BLOXBUILDER_TEXTDOMAIN);
		
		$this->textPasteActivationKey = __("Paste your envato purchase code here <br> from the pro version item", BLOXBUILDER_TEXTDOMAIN);
		$this->textPlaceholder = __("xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",BLOXBUILDER_TEXTDOMAIN);
		
		$this->textLinkToBuy = null; 
		$this->urlPricing = null;
		
		$this->textDontHave = __("We used to sell this product in codecanyon.net <br> Activate from this screen only if you bought it there.",BLOXBUILDER_TEXTDOMAIN);
		
		$this->textActivationFailed = __("You probably got your purchase code wrong", BLOXBUILDER_TEXTDOMAIN);
		$this->codeType = self::CODE_TYPE_ENVATO;
		$this->isExpireEnabled = false;
		
		if(self::ENABLE_STAND_ALONE == true){
			
			$urlRegular = HelperUC::getViewUrl("license");
			$htmlLink = HelperHtmlUC::getHtmlLink($urlRegular, __("Activate With Blox Key", BLOXBUILDER_TEXTDOMAIN),"","blue-text");
			
			$this->textSwitchTo = __("Don't have Envato Activation Key? ",BLOXBUILDER_TEXTDOMAIN).$htmlLink;
		}
		
		$this->textDontHaveLogin = null;
		
	}
	
	
	/**
	 * init by blox wp
	 */
	private function initByBloxWP(){
		
		$urlEnvato = HelperUC::getViewUrl("license","envato=true");
		$htmlLink = HelperHtmlUC::getHtmlLink($urlEnvato, __("Activate With Envato Key", BLOXBUILDER_TEXTDOMAIN),"","blue-text");
		
		$this->urlPricing = "http://blox-builder.com/go-pro/";
		$this->textSwitchTo = __("Have Envato Market Activation Key? ",BLOXBUILDER_TEXTDOMAIN).$htmlLink;
		
	}
	
	
	/**
	 * init the variables
	 */
	public function __construct(){
				
		parent::__construct();
		
		$this->textGoPro = __("Activate Blox Pro", BLOXBUILDER_TEXTDOMAIN);
		$this->writeRefreshPageMessage = false;
		
		$isEnvato = UniteFunctionsUC::getGetVar("envato", "", UniteFunctionsUC::SANITIZE_KEY);
		$isEnvato = UniteFunctionsUC::strToBool($isEnvato);
		
		if(self::ENABLE_STAND_ALONE == false)
			$isEnvato = true;
		
		if($isEnvato == true)
			$this->initByEnvato();
		else
			$this->initByBloxWP();
			
	}
	
		
}