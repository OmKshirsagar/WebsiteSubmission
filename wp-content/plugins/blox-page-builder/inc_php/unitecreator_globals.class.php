<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

	class GlobalsUC{
		
		public static $inDev = false;
		
		const SHOW_TRACE = false;
		const SHOW_TRACE_FRONT = false;
		
		const ENABLE_TRANSLATIONS = false;
		
		const PLUGIN_TITLE = "Blox Page Builder";
		const PLUGIN_NAME = "bloxbuilder";
		
		const TABLE_ADDONS_NAME = "addonlibrary_addons";
		const TABLE_LAYOUTS_NAME = "addonlibrary_layouts";
		const TABLE_CATEGORIES_NAME = "addonlibrary_categories";
		
		
		const VIEW_ADDONS_LIST = "addons";
		const VIEW_DEVIDERS_LIST = "deviders";
		const VIEW_SHAPES_LIST = "shapes";
		
		const VIEW_EDIT_ADDON = "addon";
		const VIEW_ASSETS = "assets";
		const VIEW_SETTINGS = "settings";
		const VIEW_TEST_ADDON = "testaddon";
		const VIEW_ADDON_DEFAULTS = "addondefaults";
		const VIEW_MEDIA_SELECT = "mediaselect";
		const VIEW_LAYOUTS_LIST = "layouts";
		const VIEW_LAYOUT = "layout_outer";
		const VIEW_LAYOUT_IFRAME = "layout";
		const VIEW_LAYOUT_PREVIEW = "layout_preview";
		const VIEW_TEMPLATES_LIST = "templates";
		const VIEW_LIBRARY = "library";
		
		const VIEW_LICENSE = "license";
		
		const VIEW_LAYOUTS_SETTINGS = "layouts_settings";
		
		const DEFAULT_JPG_QUALITY = 81;
		const THUMB_WIDTH = 300;
		const THUMB_WIDTH_LARGE = 700;
		
		const THUMB_SIZE_NORMAL = "size_normal";
		const THUMB_SIZE_LARGE = "size_large";
		
		const DIR_THUMBS = "blox_thumbs";
		const DIR_SCREENSHOTS = "blox_screenshots";
		const DIR_THUMBS_ELFINDER = "elfinder_tmb";
		
		const DIR_THEME_ADDONS = "blox_addons";
		
		const URL_API = "http://api.bloxbuilder.me/index.php";
		//const URL_API = "http://localhost/dev/blox_API/";
		
		const URL_BUY = "http://blox-builder.com/go-pro/";
		const URL_SUPPORT = "http://unitecms.ticksy.com";
		
		const ADDON_TYPE_REGULAR_ADDON = "regular_addon";
		const ADDON_TYPE_SHAPE_DEVIDER = "shape_devider";
		const ADDON_TYPE_SHAPES = "shapes";
		const ADDON_TYPE_REGULAR_LAYOUT = "layout";
		const ADDON_TYPE_LAYOUT_SECTION = "layout_section";
		const ADDON_TYPE_LAYOUT_PAGE_TEMPLATE = "page_template";
		const ADDON_TYPE_LAYOUT_GENERAL = "layout_general";
		const ADDON_TYPE_BGADDON = "bg_addon";
		
		const LAYOUT_TYPE_HEADER = "header";
		const LAYOUT_TYPE_FOOTER = "footer";
		
		const VALUE_EMPTY_ARRAY = "[[uc_empty_array]]";
		
		
		public static $permisison_add = false;
		public static $blankWindowMode = false;
		
		public static $view_default;
		
		public static $table_addons;
		public static $table_categories;
		public static $table_layouts;
		
		public static $pathSettings;
		public static $filepathItemSettings;
		public static $pathPlugin;
		public static $pathTemplates;
		public static $pathViews;
		public static $pathViewsObjects;
		public static $pathLibrary;
		public static $pathAssets;	
		public static $pathProvider;
		public static $pathProviderViews;
		public static $pathProviderTemplates;
		
		public static $current_host;
		public static $current_page_url;
		
		public static $url_base;
		public static $url_images;
		public static $url_images_screenshots;
		public static $url_component_client;
		public static $url_component_admin;
		public static $url_component_admin_nowindow;
		public static $url_ajax;
		public static $url_ajax_front;
		public static $url_default_addon_icon;
		
		public static $urlPlugin;
		public static $url_provider;
		public static $url_assets;
		public static $url_assets_libraries;
		public static $url_assets_internal;
		
		public static $is_admin;
		public static $isLocal;		//if website located in localhost
		
		public static $is_ssl;
		public static $path_base;
		public static $path_cache;
		public static $path_images;
		public static $path_images_screenshots;
		
		public static $layoutShortcodeName = "blox_page";
		
		public static $arrClientSideText = array();
		public static $arrServerSideText = array();
		
		public static $isProductActive = false;
		public static $defaultAddonType = "";
		public static $enableWebCatalog = true;
		public static $arrSizes = array("tablet","mobile");
		
		public static $arrAdminViewPaths = array();
		public static $alterViewHeaderPrefix = null;
		public static $arrViewAliases = array();
		public static $arrDatasetTypes = array();
		
		
		/**
		 * init globals
		 */
		public static function initGlobals(){
			
			UniteProviderFunctionsUC::initGlobalsBase();
			
			self::$current_host = UniteFunctionsUC::getVal($_SERVER, "HTTP_HOST");
			self::$current_page_url = self::$current_host.UniteFunctionsUC::getVal($_SERVER, "REQUEST_URI");
			
			self::$pathProvider = self::$pathPlugin."provider/";
			self::$pathTemplates = self::$pathPlugin."views/templates/";
			self::$pathViews = self::$pathPlugin."views/";
			self::$pathViewsObjects = self::$pathPlugin."views/objects/";
			self::$pathSettings = self::$pathPlugin."settings/";
			
			self::$pathProviderViews = self::$pathProvider."views/";
			self::$pathProviderTemplates = self::$pathProvider."views/templates/";
			
			self::$filepathItemSettings = self::$pathSettings."item_settings.php";
			
			self::$path_images_screenshots = self::$path_images.self::DIR_SCREENSHOTS."/";
			self::$url_images_screenshots = self::$url_images.self::DIR_SCREENSHOTS."/";
			
			//check for wp version
			UniteFunctionsUC::validateNotEmpty(GlobalsUC::$url_assets_internal, "assets internal");
			
			self::$isLocal = UniteFunctionsUC::isLocal();
			
			self::initDBTableTitles();
			
			/*
			$action = UniteFunctionsUC::getGetVar("maxaction", "", UniteFunctionsUC::SANITIZE_KEY);
			if($action == "showvars")
				GlobalsUC::printVars();
			*/
			
			//GlobalsUC::printVars();
		}
		
		/**
		 * init table titles
		 */
		private static function initDBTableTitles(){
			
			$arrTitles = array();
			$arrTitles[GlobalsUC::$table_addons] = __("Addon", BLOXBUILDER_TEXTDOMAIN);
			$arrTitles[GlobalsUC::$table_categories] = __("Category", BLOXBUILDER_TEXTDOMAIN);
			$arrTitles[GlobalsUC::$table_layouts] = __("Page", BLOXBUILDER_TEXTDOMAIN);
			
			UniteCreatorDB::$arrTableTitles = $arrTitles;
			
		}
		
		
		/**
		 * init after the includes done
		 * //check if active only if in admin side
		 */
		public static function initAfterIncludes(){
					    
			$webAPI = new UniteCreatorWebAPI();			
			self::$isProductActive = $webAPI->isProductActive();
			
		}
		
		
		/**
		 * print all globals variables
		 */
		public static function printVars(){
			
			$methods = get_class_vars( "GlobalsUC" );
			dmp($methods);
			exit();
		}
		
	}

	//init the globals
	GlobalsUC::initGlobals();
	
?>
