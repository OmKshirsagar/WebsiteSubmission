<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');


if($this->showHeader == true){
	
	$headerTitle = $this->getHeaderText();
	
	require HelperUC::getPathTemplate("header");
}else
	require HelperUC::getPathTemplate("header_missing");


?>
	
	<?php 
		if($this->showButtons == true)
			UniteProviderFunctionsUC::putAddonViewAddHtml()
	?>
	
	<div class="content_wrapper unite-content-wrapper">
		<?php $objManager->outputHtml() ?>
	</div>

	<?php 
		
		if(method_exists("UniteProviderFunctionsUC", "putUpdatePluginHtml"))
			UniteProviderFunctionsUC::putUpdatePluginHtml();
	
	?>