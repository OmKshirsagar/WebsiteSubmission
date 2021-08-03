<?php

defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteProviderFrontUC{
	
	private $t;
	const ACTION_FOOTER_SCRIPTS = "wp_print_footer_scripts";
	const ACTION_AFTER_SETUP_THEME = "after_setup_theme";
	
	
	/**
	 *
	 * add some wordpress action
	 */
	protected function addAction($action,$eventFunction,$priority=10){
		
		add_action( $action, array($this, $eventFunction), $priority );
	}
		
	/**
	 * add filter
	 */
	protected function addFilter($tag, $func, $priority = 10, $accepted_args = 1){
		
		add_filter($tag, array($this, $func), $priority, $accepted_args);
	}
	
	
	
	/**
	 * check and disable wp filters
	 */
	private function disableWpFilters(){
		
		//disable filters
		$disableFilters = HelperUC::getGeneralSetting("disable_autop_filters");
		$disableFilters = UniteFunctionsUC::strToBool($disableFilters);
		
		if($disableFilters == true){
			remove_filter( 'the_content', 'wpautop' );
		}
		
	}
	
	
	
	/**
	 *
	 * the constructor
	 */
	public function __construct(){
		
		$this->t = $this;
		
		HelperProviderUC::globalInit();
	   
		$this->addAction(self::ACTION_FOOTER_SCRIPTS, "onPrintFooterStyles", 1);
		$this->addAction(self::ACTION_FOOTER_SCRIPTS, "onPrintFooterScripts");
		
		$this->addAction( 'wp_head', 'onPrintHeadStyles' );
		
		$this->disableWpFilters();
	}
	
	/**
	 * on print head styles
	 */
	public function onPrintHeadStyles(){
	    
	  HelperProviderUC::outputCustomStyles();	  
	}
	
	
	/**
	 * print footer scripts
	 */
	public function onPrintFooterStyles(){
		
		HelperProviderUC::onPrintFooterScripts(true, "css");
		
	}

	/**
	 * print footer scripts
	 */
	public function onPrintFooterScripts(){
		
		HelperProviderUC::onPrintFooterScripts(true, "js");
		
	}
	
		
}

?>