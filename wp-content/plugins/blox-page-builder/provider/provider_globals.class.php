<?php

defined('BLOXBUILDER_INC') or die('Restricted access');

class GlobalsProviderUC{
	
	const LOAD_PAGE_BUILDER = true;
	
	public static $pluginName = "";
	
	const META_KEY_LAYOUT_DATA = "_blox_layout_data";
	const META_KEY_LAYOUT_PARAMS = "_blox_layout_params";
	
	const META_KEY_BLOX_PAGE = "_blox_page_enabled";
	const META_KEY_CATID = "_blox_catid";
	const META_KEY_LAYOUT_TYPE = "_blox_layout_type";

	
	const PAGE_TEMPLATE_LANDING_PAGE = "blox_landing_page";
	const POST_TYPE_LAYOUT = "blox_layout";
	
	const SHORTCODE_LAYOUT = "blox_layout";
	
	const ACTION_RUN_ADMIN = "unitecreator_run_admin";
	const ACTION_RUN_FRONT = "unitecreator_run_front";
	
}