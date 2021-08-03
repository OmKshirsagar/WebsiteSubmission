<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

HelperHtmlUC::putAddonTypesBrowserDialogs();

$settings = new UniteCreatorSettings();

//$settings->addRangeSlider("range","10","Select Range",array("min"=>1,"max"=>50,"step"=>2,"unit"=>"px"));

$settings->addAddonPicker("addon_devider", "", "Select Divider",array("addontype" => GlobalsUC::ADDON_TYPE_SHAPE_DEVIDER));
$settings->addAddonPicker("addon", "", "Select Addon");


$output = new UniteSettingsOutputWideUC();

$output->init($settings);
$output->draw("settings_test");

?>
<script>

jQuery("document").ready(function(){
	var settings = new UniteSettingsUC();
	var objSettings = jQuery("#unite_settings_wide_output_1");
	settings.init(objSettings);
	
});

</script>
<?php 