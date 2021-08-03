<?php

defined('BLOXBUILDER_INC') or die('Restricted access');
class BloxLandingPageThemeOutput{
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$this->outputHtml();
	}
	
	
	
	/**
	 * clear filters from theme output
	 */
	protected function clearFilters(){
		
		$arrThemeFitlersHead = UniteProviderCoreFrontUC_Blox::$arrThemeWPHeadTags;
		$arrThemeScriptTags = UniteProviderCoreFrontUC_Blox::$arrThemeWPScriptTags;
		
		UniteFunctionsWPUC::clearFiltersFromFunctions("wp_head", $arrThemeFitlersHead);
		UniteFunctionsWPUC::clearFiltersFromFunctions("wp_enqueue_scripts", $arrThemeScriptTags);
		
	}
	
	
	/**
	 * output html
	 */
	protected function outputHtml(){
				
		$this->clearFilters();
		
		$htmlBody = HelperProviderCoreUC_Blox::getHtmlBodyCurrentPostLayout();
		
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php 
	wp_head();
	do_action("blox_wp_head");
?>
</head>
<body>

<?php echo $htmlBody?>

<?php wp_footer()?>	
</body>
</html>
<?php 
		
	}
	
}

new BloxLandingPageThemeOutput();

