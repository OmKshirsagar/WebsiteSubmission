<?php

defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteProviderCoreFrontUC_Blox extends UniteProviderFrontUC{
	
	private $arrBeforeThemeWPHeadTags;
	private $arrBeforeThemeScriptTags;
	
	public static $arrThemeWPHeadTags;
	public static $arrThemeWPScriptTags;
	public static $isBloxEditPage = false;
	protected $htmlBody = "";
	protected $isBloxPage = false;
	private $textSingle;
	
	
	
	
	/**
	 * check custom template output
	 */
	public function checkCustomTemplate($template){
		
		$post = get_post();
		
		if(empty($post))
			return($template);	
		
		$postType = $post->post_type;
		
		$isBloxPage = HelperProviderCoreUC_Blox::isPostIsBloxPage($post);
				
		if($isBloxPage == false)
			return($template);
		
		$this->isBloxPage = true;
		
		//replace the template if needed
		$currentPageTemplate = HelperProviderCoreUC_Blox::getCurrentPageTemplate($post);
		
		if($postType == GlobalsProviderUC::POST_TYPE_LAYOUT || 
		   $postType == "uc_layout" || 
		   $currentPageTemplate == GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE){
				
			$template = HelperProviderCoreUC_Blox::$pathThemeLanding."theme_landing.php";
			return($template);
		}
		
		//save the html body for drawing css first
		$this->htmlBody = $this->outputCurrentPostLayout();
						
		return($template);
	}
	
		
	/**
	 * register before theme action tags
	 */
	public function registerBeforeThemeActionTags(){
		
		$this->arrBeforeThemeWPHeadTags = UniteFunctionsWPUC::getActionFunctionsKeys("wp_head");
		$this->arrBeforeThemeScriptTags = UniteFunctionsWPUC::getActionFunctionsKeys("wp_enqueue_scripts");
		
	}
	
	
	/**
	 * register before theme action tags
	 */
	public function registerThemeActionTags(){
		
		$arrWPHeadTags = UniteFunctionsWPUC::getActionFunctionsKeys("wp_head");
		$arrWPScriptTags = UniteFunctionsWPUC::getActionFunctionsKeys("wp_enqueue_scripts");
		
		self::$arrThemeWPHeadTags = array_diff_assoc($arrWPHeadTags, $this->arrBeforeThemeWPHeadTags);
		self::$arrThemeWPScriptTags = array_diff_assoc($arrWPScriptTags, $this->arrBeforeThemeScriptTags);
	}
	
	/**
	 * output current post layout
	 */
	public function outputCurrentPostLayout(){
		
		try{
			$layout = new UniteCreatorLayout();
			$layout->initByID("current_post");
						
			$outputLayout = new UniteCreatorLayoutOutput();
			$outputLayout->initByLayout($layout);
			
			$htmlBody = $outputLayout->getHtml();
		
		}catch(Exception $e){
			
			$message = $e->getMessage();
			$htmlError = HelperHtmlUC::getErrorMessageHtml($message);
			
			return($htmlError);
		}
		
		
		return($htmlBody);
	}
	
		
	
	/**
	 * check if curren page is blox editable
	 */
	protected function isBloxEditPage() {
		
		$bloxEditPostID = UniteFunctionsUC::getGetVar("bloxedit", "", UniteFunctionsUC::SANITIZE_KEY);
		
		if(empty($bloxEditPostID))
			return(false);
		
		if(is_numeric($bloxEditPostID) == false)
			return(false);
		
		$canEdit = UniteFunctionsWPUC::isUserCanEditPost($bloxEditPostID);

		if($canEdit == false)
			return(false);
		
		return true;
	}
	
	
	private function a______FRONT_EDIT_MODE______(){}
	
	
	/**
	 * add scripts
	 */
	public function frontEditMode_addScripts(){
		
		$globalJsOutput = HelperHtmlUC::getGlobalJsOutput();
		UniteProviderFunctionsUC::printCustomScript($globalJsOutput);
		
		$pathAdmin = GlobalsUC::$pathPlugin."unitecreator_admin.php";
		require_once $pathAdmin;
		UniteCreatorAdmin::onAddScriptsGridEditor();
				
	}
	
	
	/**
	 * output grid builder
	 */
	public function frontEditMode_outputGridBuilder($content){
		
		$currentPostID = get_the_ID();
		
		if(empty($currentPostID))
			return($content);
		
		$objLayout = new UniteCreatorLayout();
		$objLayout->initByID($currentPostID);
		
		$objLayout->checkNewPostLayoutContent();
		
		$this->objPageBuilder = new UniteCreatorPageBuilder();
		$this->objPageBuilder->initInner($objLayout);
		
		$content = $this->objPageBuilder->getInnerHtml();	
		
		return($content);
	}
	
	
	/**
	 * init front edit mode for internale frontend output
	 */
	protected function initFrontEditMode(){
		
		add_filter( 'show_admin_bar', '__return_false' );
		
		$this->addAction("wp_enqueue_scripts", "frontEditMode_addScripts");
				
	}
	
	private function a______OTHERS______(){}
	
	/**
	 * check and init edit mode
	 */
	public function onTemplateRedirect(){
		
		self::$isBloxEditPage = $this->isBloxEditPage();
		
		if(self::$isBloxEditPage == true)
			$this->initFrontEditMode();
	}
	
	/**
	 * on content filter
	 */
	public function onContentFilter($content){
		
		if(self::$isBloxEditPage == true){
			$content = $this->frontEditMode_outputGridBuilder($content);
			return($content);
		}
		
		if($this->isBloxPage == true){
			
			return($this->htmlBody);
		}
		
		
		return($content);
	}
	
	
	/**
	 * add edit with blox to admin bar
	 */
	public function addAdminBarItem(WP_Admin_Bar $wp_admin_bar){
		
		if($this->isBloxPage == false)
			return(false);
		
		$post = get_post();
		if(empty($post))
			return(false);
		
		$postID = $post->ID;
		
		//add another menu item
		$linkVisualEdit = HelperUC::getViewUrl_Layout($postID);
		
		
		$params = array();
		$params["id"] = "blox_edit_page";
		$params["title"] = __("Edit With ", BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
		$params["href"] = $linkVisualEdit;
		
		$wp_admin_bar->add_node( $params );
				
	}
	
	
	/**
	 * init white label texts
	 */
	private function initWhiteLabel(){
	    
	    $whiteLabelSettings = HelperProviderUC::getWhiteLabelSettings();
	    
	    if(empty($whiteLabelSettings))
	        return(false);
	        	            
        $pluginText = $whiteLabelSettings["plugin_text"];
            
        $this->textSingle = $whiteLabelSettings["single"];
	    HelperProviderCoreUC_Blox::$textSingle = $this->textSingle;
	    	
	}
	
	
	/**
	 * on init handler
	 */
	public function onInit(){
		
		$this->initWhiteLabel();
		
		HelperProviderCoreUC_Blox::onInitGlobalHandler();
		
		//UniteFunctionsUC::showTrace();exit();
	}
	
	
	/**
	 *
	 * the constructor
	 */
	public function __construct(){
		
	    $this->textSingle = __("Blox",BLOXBUILDER_TEXTDOMAIN);
		HelperProviderCoreUC_Blox::$textSingle = $this->textSingle;
	    
		
		HelperProviderCoreUC_Blox::globalInit();
		
		do_action("blox_page_builder_front_init");
		
		parent::__construct();
		
		$this->addAction("init", "onInit");
		
		$this->addAction("template_redirect", "onTemplateRedirect");
		
		//check if blox page in this function
		$this->addFilter("template_include", "checkCustomTemplate");
		
		$this->addFilter("setup_theme", "registerBeforeThemeActionTags");
		$this->addFilter("after_setup_theme", "registerThemeActionTags");
		
		$this->addFilter("the_content", "onContentFilter", 999999);
		
		$this->addAction("admin_bar_menu", "addAdminBarItem", 200);
		
		//$this->addFilter("template_directory", "getTemplateDirectory");
	}
	
	
}
