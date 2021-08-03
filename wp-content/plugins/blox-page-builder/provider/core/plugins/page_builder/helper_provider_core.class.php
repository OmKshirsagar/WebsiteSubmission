<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('BLOXBUILDER_INC') or die('Restricted access');

class HelperProviderCoreUC_Blox{
	
	public static $pathCore;
	public static $pathPlugins;
	public static $pathThemeLanding;
	public static $isSupportPages = true;
	public static $isBloxTheme = false;
	
	public static $urlCore;
	public static $urlAssets;
	public static $textSingle;
	
	
	/**
	 * add landing page post type
	 */
	public static function registerPostTypes(){
		
		$arrLabels = array(
						'name' => __( 'Blox Layout' ,BLOXBUILDER_TEXTDOMAIN),
						'singular_name' => __( 'Blox Layout' ,BLOXBUILDER_TEXTDOMAIN),
						'add_new_item' => __( 'Add New Layout' ,BLOXBUILDER_TEXTDOMAIN),
						'edit_item' => __( 'Edit Layout' ,BLOXBUILDER_TEXTDOMAIN),
						'new_item' => __( 'New Layout' ,BLOXBUILDER_TEXTDOMAIN),
						'view_item' => __( 'View Layout' ,BLOXBUILDER_TEXTDOMAIN),
						'view_items' => __( 'View Layouts' ,BLOXBUILDER_TEXTDOMAIN),
						'search_items' => __( 'Search Layouts' ,BLOXBUILDER_TEXTDOMAIN),
						'not_found' => __( 'No Layouts Found' ,BLOXBUILDER_TEXTDOMAIN),
						'not_found_in_trash' => __( 'No Layouts found in trash' ,BLOXBUILDER_TEXTDOMAIN),
						'all_items' => __( 'All Layouts' ,BLOXBUILDER_TEXTDOMAIN)
				);
		
		$arrSupports = array(
			"title",
		//	"editor",
			"author",
			"thumbnail",
			"revisions",
			"page-attributes",
		);
		
		$arrPostType =	array(
							'labels' => $arrLabels,
							'public' => true,
							'rewrite' => false,
							'show_ui' => true,
							'show_in_menu' => false,
							'show_in_nav_menus' => false,
							'exclude_from_search' => true,
							'capability_type' => 'post',
							'hierarchical' => false,
							'description' => __("Blox Page Builder Layout", BLOXBUILDER_TEXTDOMAIN),
							'supports' => $arrSupports,
							//'show_in_admin_bar' => true		
					);
		
		register_post_type( GlobalsProviderUC::POST_TYPE_LAYOUT, $arrPostType);
	}
	
	
	/**
	 * is page / post editable with blox
	 */
	public static function isPostBloxEditable($postType){
				
		if($postType == "uc_layout")
			return(true);
		
		if($postType == GlobalsProviderUC::POST_TYPE_LAYOUT)
			return(true);
		
		if(self::$isSupportPages == false)
			return(false);
		
		$arrTypes = array();
		$strAvailTypes = HelperUC::getGeneralSetting("post_types");
		if(is_string($strAvailTypes))
			$arrTypes = explode(",", $strAvailTypes);
		
		if(in_array($postType, $arrTypes) !== false)
			return(true);
		
		return(false);
	}
	
	
	/**
	 * add constant data to addon output
	 */
	public static function addOutputConstantData($data){
		
		$data["uc_platform_title"] = "Blox Page Builder";
		$data["uc_platform"] = "";
						
		return($data);
	}
	
	
	/**
	 * get html body of current post layout
	 */
	public static function getHtmlBodyCurrentPostLayout(){
		
		try{
			$layout = new UniteCreatorLayout();
			$layout->initByID("current_post");
			
			$outputLayout = new UniteCreatorLayoutOutput();
			$outputLayout->initByLayout($layout);
			
			$outputLayout->addCssStylePreview();
			$htmlBody = $outputLayout->getHtml();			
			
		}catch(Exception $e){
			$message = $e->getMessage();
			$htmlBody = HelperHtmlUC::getErrorMessageHtml($message);
		}
		
		return($htmlBody);
	}
	
	/**
	 * run blox theme related init actions
	 */
	public static function runThemeRelatedInit(){
		
		self::$isBloxTheme = true;
		
	}
	
	
	/**
	 * return if some post is blox page or blox landing page
	 */
	public static function isPostIsBloxPage($post){
		
		if(empty($post))
			return(false);
		
		if(is_array($post))
			return(false);
		
		$postID = $post->ID;
		if(empty($postID))
			return(false);
		
		//check post type
		$postType = $post->post_type;
		if(empty($postType))
			return(false);
		
		$isPostTypeEditable = self::isPostBloxEditable($postType);
		
		if($isPostTypeEditable == false)
			return(false);
		
		//old layout type
		if($postType == "uc_layout")
			return(true);
		
		$metaIsBlox = get_post_meta($postID, GlobalsProviderUC::META_KEY_BLOX_PAGE, true);
		$metaIsBlox = UniteFunctionsUC::strToBool($metaIsBlox);
		
		if($metaIsBlox == true)
			return(true);
		
		$pageTemplate = UniteFunctionsWPUC::getPostPageTemplate($post);
		$isBlox = self::isPageTemplateIsBlox($pageTemplate);
		
		return($isBlox);
	}
	
	
	/**
	 * returrn if some page template is blox
	 */
	public static function isPageTemplateIsBlox($pageTemplate){
				
		switch($pageTemplate){
			case "blox_page":		//fallout
			case GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE:
				return(true);
			break;
		}
		
		return(false);
	}
	
	
	/**
	 * Get blox page type title
	 */
	public static function getBloxPageTypeTitle($post, $textSingle){
			    
		$isBloxPage = self::isPostIsBloxPage($post);
		if($isBloxPage == false)
			return(null);
		
		$postType = $post->post_type;
		
		if($postType == "uc_layout"){
		    $title = $textSingle.__(" Layout", BLOXBUILDER_TEXTDOMAIN);
			return($title);
		}
		
		$title = $textSingle.__(" Page", BLOXBUILDER_TEXTDOMAIN);
		
		$pageTemplate = UniteFunctionsWPUC::getPostPageTemplate($post);
		if($pageTemplate == GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE)
		    $title = $textSingle.__(" Landing Page", BLOXBUILDER_TEXTDOMAIN);
		
		
		return($title);
	}
	
	
	
	/**
	 * check if landing page
	 */
	public static function isInsideLandingPagePost($post = null){
		
		$pageTemplate = self::getCurrentPageTemplate($post);
		
		if($pageTemplate == GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE)
			return(true);
		
		return(false);
	}
	
	
	/**
	 * get current page template, blox type or null
	 */
	public static function getCurrentPageTemplate($post = null){
		
		if(!$post)
			$post = get_post();
		
		if(empty($post))
			return(null);
		
		if(is_array($post))
			return(null);
				
		$pageTemplate = UniteFunctionsWPUC::getPostPageTemplate($post);
		
		if($pageTemplate != GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE)
			$pageTemplate = null;	
		
		return($pageTemplate);
	}
	
	
	/**
	 * global init
	 */
	public static function globalInit(){
		
		add_filter(UniteCreatorFilters::FILTER_ADD_ADDON_OUTPUT_CONSTANT_DATA ,array("HelperProviderCoreUC_Blox","addOutputConstantData"));
		
		//set path and url
		self::$pathCore = dirname(__FILE__)."/";
		
		self::$urlCore = HelperUC::pathToFullUrl(self::$pathCore);
		self::$urlAssets = self::$urlCore."assets/";
		
		$realPathCore = GlobalsUC::$pathProvider."core/";
		
		self::$pathPlugins = $realPathCore."plugins/";
		self::$pathThemeLanding = self::$pathCore."theme_landing/";
				
	}
	
	/**
	 * output blox layout shortcode
	 */
	public static function outputBloxLayoutShortcode($args){
		
		$layoutID = UniteFunctionsUC::getVal($args, "id");
		HelperUC::outputLayout($layoutID);
		
	}
	
	/**
	 * modify layout preview front
	 */
	public static function modifyUrlLayoutPreviewFront($url, $layoutID, $addParams){
		
		if(empty($layoutID))
			return($url);
		
		$url = UniteFunctionsWPUC::getPermalink($layoutID);
		if(!empty($addParams))
			$url = UniteFunctionsUC::addUrlParams($url, $addParams);
		
		
		return($url);
	}

	
	/**
	 * add blox templates to the list
	 */
	public static function onGetPageTemplates($arrTemplates, $objTheme, $post, $post_type){
		
		$isPostTypeSupported = self::isPostBloxEditable($post_type);
		if($isPostTypeSupported == false)
			return($arrTemplates);
		
		$textSingle = self::$textSingle;
			
		if($post_type == "page")
		    $templateTitle = $textSingle.__(" Landing Page", BLOXBUILDER_TEXTDOMAIN);
		else
		    $templateTitle = $textSingle.__(" Empty Canvas", BLOXBUILDER_TEXTDOMAIN);
		
			
		$arrTemplates[GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE] = $templateTitle;
		
		return($arrTemplates);
	}
	
	
	/**
	 * on init global handler
	 * using in admin and front
	 */
	public static function onInitGlobalHandler(){
				
		self::registerPostTypes();
		
		//register shortcodes
		UniteFunctionsWPUC::addShortcode(GlobalsProviderUC::SHORTCODE_LAYOUT, array("HelperProviderCoreUC_Blox", "outputBloxLayoutShortcode"));
	
		add_filter(UniteCreatorFilters::FILTER_MODIFY_URL_LAYOUT_PREVIEW_FRONT, array("HelperProviderCoreUC_Blox", "modifyUrlLayoutPreviewFront"), 10, 4);
		
		//set page templates
		add_filter("theme_templates", array("HelperProviderCoreUC_Blox", "onGetPageTemplates"), 10, 4);
		
		
		//dmp("init filter");exit();
	}
	
}