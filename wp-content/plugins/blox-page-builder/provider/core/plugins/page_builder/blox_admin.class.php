<?php

defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteProviderCoreAdminUC_Blox extends UniteProviderAdminUC{
	
	private $isInited = false;
	private $postType = null;
	private $isBloxPage = false;
	private $isLandingPage = false;
	private $isBloxLayoutPage = false;		//can't be regular page with wp editor
	private $objLayout, $objLayoutType;
	private $textLayoutInfo, $isGutenbergActive = false;
	private $textSingle;
	
	
	/**
	 * the constructor
	 */
	public function __construct($mainFilepath){
		
		$this->textBuy = __("Activate Blox", BLOXBUILDER_TEXTDOMAIN);
		$this->linkBuy = "";
		$this->coreAddonType = null;
		$this->coreAddonsView = "addons";
		$this->textSingle = __("Blox", BLOXBUILDER_TEXTDOMAIN);
		
		HelperProviderCoreUC_Blox::$textSingle = $this->textSingle;
		
		//set plugin title
		$this->pluginTitle = __("Blox Page Builder", BLOXBUILDER_TEXTDOMAIN);
		
		HelperProviderCoreUC_Blox::globalInit();
		
		$this->loadAdminPlugins();
		
		parent::__construct($mainFilepath);
				
	}
	
	
	
	/**
	 * set plugin title
	 */
	protected function setPluginTitle(){
	    
	    $whiteLabelSettings = HelperProviderUC::getWhiteLabelSettings();
	   
	    if(empty($whiteLabelSettings))
	        return(false);
	   	        
	    /* function for override */
	    	    	    
	    if(empty($whiteLabelSettings))
	        return(false);
	    
	     $pluginText = $whiteLabelSettings["plugin_text"];
	        
	    $this->pluginTitle = $pluginText;
	    $this->textSingle = $whiteLabelSettings["single"];
	   	HelperProviderCoreUC_Blox::$textSingle = $this->textSingle;
	    
	   	
	    //set texts
	    $arrText = array();
	    $arrText["addon_library"] = $pluginText;
	    
	    HelperUC::setLocalText($arrText);
	    
	    
	}
	
	
		
	/**
	 * modify addons manager
	 */
	public function validateGeneralSettings($arrValues){
				
	}
	
	
	
	private function a_____________GETTERS______________(){}
	
	
	/**
	 * is screen editable
	 */
	private function isScreenBloxEditable($screenType){
		
		$screen = get_current_screen();
		if(empty($screen))
			return(false);
		
		$base = $screen->base;
		
		if($base != $screenType)
			return(false);
		
		$postType = $screen->post_type;
		
		$isEditable = HelperProviderCoreUC_Blox::isPostBloxEditable($postType);
			
		return($isEditable);
	}
	
	/**
	 * check if the page is layouts edit
	 */
	public function isLayoutsListPage(){
				
		$isEditable = $this->isScreenBloxEditable("edit");
				
		return($isEditable);
	}
	
	
	/**
	 * check if inside blox editable page
	 */
	private function isInsideBloxPostEditablePage(){
		
		$isEditable = $this->isScreenBloxEditable("post");
		
		return($isEditable);
	}
	
	
	/**
	 * get url layouts list
	 */
	public function getUrlLayoutList($url, $params){

		//if special type like section or template
		$layoutType = UniteFunctionsUC::getVal($params, "layout_type");
		if(!empty($layoutType)){
			$objLayoutType = UniteCreatorAddonType::getAddonTypeObject($layoutType, true);

			if($objLayoutType->isLayout == true && $objLayoutType->displayType == UniteCreatorAddonType_Layout::DISPLAYTYPE_MANAGER)
				return($url);
		}
		
		
		//if regular type like page
		$postType = UniteFunctionsUC::getVal($params, "post_type", "page");
		
		if(!empty($postType))
			$url = admin_url("edit.php?post_type=".$postType);
		
		return($url);
	}
	
	/**
	 * creator always exists
	 */
	protected function isCreatorPluginExists(){
		
		return(true);
	}
	
	
	private function a_____________LAYOUTS_LIST_PAGE______________(){}
	
	
	/**
	 * add import layout dialog
	 */
	private function putImportLayoutDialog(){
		
		//put general debug divs
		$debugDivs = HelperHtmlUC::getGlobalDebugDivs();
		echo $debugDivs;
		
		require_once HelperUC::getPathViewObject("layouts_view.class");
		$objView = new UniteCreatorLayoutsView();
		$objView->putDialogImportLayout();
		$objView->putDialogPageCatalog();
	}
	
	
	/**
	 * add footer html
	 */
	private function addLayoutsListFooterHtml(){
			
			$this->putImportLayoutDialog();
			
			$postType = $this->postType;
			
			$textSingle = $this->textSingle;
			$textSingle = htmlspecialchars($textSingle);
			
			?>
			
			<script type="text/javascript">
				jQuery(document).ready(function(){
					
					var objAdmin = new LayoutsPostsListPageAdmin("<?php echo $postType?>");
					
					objAdmin.init("<?php echo $textSingle?>");
					
				});
			</script>
			<?php 
		
	}
	
	/**
	 * define the extra column
	 */
	public function addExtraColumn_define($columns){
		
		$isLayoutsPage = $this->isLayoutsListPage();
		if($isLayoutsPage == false)
			return($columns);
		
		$arrColsNew = array();
		foreach($columns as $key=>$value){
							
			$arrColsNew[$key] = $value;
			
			if($key == "title")
				$arrColsNew["shortcode"] = __("Shortcode",BLOXBUILDER_TEXTDOMAIN);
		}
		
		return($arrColsNew);
	}
	
	
	/**
	 * output the custom column
	 */
	public function addExtraColumn_output($column, $postID){
				
		$isLayoutsPage = $this->isLayoutsListPage();
		if($isLayoutsPage == false)
			return(false);
		
		switch($column){
			case "shortcode":
				$objLayout = new UniteCreatorLayout();
				$objLayout->initByID($postID);
				$shortcode = $objLayout->getShortcode();
				$shortcode = esc_attr($shortcode);
				
				?>
				<input type="text" readonly onclick="this.select()" value="<?php echo $shortcode?>">
				<?php 
			break;
		}
		
	}
	
	/**
	 * add footer html
	 */
	public function onAdminFooter(){
		
		$isLayoutsList = $this->isLayoutsListPage();
		
		if($isLayoutsList){
			$this->addLayoutsListFooterHtml();
			return(false);
		}
		
		$isInsideEditPostPage = $this->isInsideBloxPostEditablePage();
				
		if($isInsideEditPostPage == true){
			$this->addEditPostFooterHtml();
			return(false);
		}
		
	}
	
		/**
		 * add layouts list actions
		 */
		public function addLayoutsListActions($actions, $post){
			
		    $textSingle = $this->textSingle;
		    
			$postType = get_post_type($post);
			
			if(HelperProviderCoreUC_Blox::isPostBloxEditable($postType) == false)
				return($actions);
		
			$isBloxPage = HelperProviderCoreUC_Blox::isPostIsBloxPage($post);
			
			if($isBloxPage == false)
				return($actions);	
			
			$pageTypeTitle = HelperProviderCoreUC_Blox::getBloxPageTypeTitle($post, $textSingle);
			
			$postID = $post->ID;
			
			//add edit
			$urlEdit = HelperUC::getViewUrl_Layout($postID);
						
			$textEdit = __("Edit", BLOXBUILDER_TEXTDOMAIN)." ".$pageTypeTitle;
			$htmlLinkEdit = "<a href='{$urlEdit}' class='uc-button-visualedit'>{$textEdit}</a>";
			$actions["blox_edit_page_visual"] = $htmlLinkEdit;
			
			//add export
			$textExport = __("Export", BLOXBUILDER_TEXTDOMAIN);
			$title = __("Export $textSingle Layout", BLOXBUILDER_TEXTDOMAIN);
			$htmlLinkExport = "<a href='javascript:void(0)' data-layoutid='{$postID}' class='uc_button_export' title='{$title}'>{$textExport}</a>";
			$actions["blox_export_layout"] = $htmlLinkExport;
			
			//add duplicate
			$textDuplicate = __("Duplicate", BLOXBUILDER_TEXTDOMAIN);
			$title = __("Duplicate $textSingle Page", BLOXBUILDER_TEXTDOMAIN);
			
			$htmlLinkDuplicate = "<a href='javascript:void(0)' data-layoutid='{$postID}' class='uc_button_duplicate' title='{$title}'>{$textDuplicate}</a>";
			$htmlLinkDuplicate .= "<span class='loader_text uc-loader-duplicate' style='display:none'>Duplicating...</span>";
			$actions["blox_duplicate_page"] = $htmlLinkDuplicate;
						
			
			return($actions);
		}
		
		
		/**
		 * add post title state in posts list ( - Blox )
		 */
		public function addPostTitleState($arrStates, $post){
			
			$postType = get_post_type($post);
			
			if(HelperProviderCoreUC_Blox::isPostBloxEditable($postType) == false)
				return($arrStates);
		
			$isBloxPage = HelperProviderCoreUC_Blox::isPostIsBloxPage($post);

			if($isBloxPage == false)
				return($arrStates);	
			
			$arrStates["blox_page"] = $this->textSingle;
			
			return($arrStates);
		}
		
		
	private function a_____________EDIT_POST_PAGE______________(){}
	
	
	/**
	 * set admin body class
	 */
	public function setAdminBodyClass($class){
		
		$this->initBloxVars();
		
		if($this->isBloxPage == true)
			$class .= " uc-blox-page";
		
		if($this->isLandingPage == true)
			$class .= " uc-blox-landing-page";
		
		return($class);
	}
	
	/**
	 * add edit post body html on landing page to gutenberg
	 */
	public function addEditPostBodyHtmlGutenberg($post = null){
		
		if($this->isGutenbergActive == false)
			return(true);
			
		$this->addEditPostBodyHtml($post);
	}
	
	
	/**
	 * add edit post body html on landing page
	 */
	public function addEditPostBodyHtml($post = null){
		
		if(empty($post))
			$post = get_post();
		
		if(empty($post))
			return(false);
			
		$postType = $post->post_type;
		$isBloxEnabled = HelperProviderCoreUC_Blox::isPostBloxEditable($postType);
		if($isBloxEnabled == false)
			return(false);
		
		$this->initBloxVars();
			
		$postID = $post->ID;
		
		$linkVisual = HelperUC::getViewUrl_Layout($postID);
		
		$postType = $post->post_type;
				
		$hasWpEditor = true;
		if($this->isBloxLayoutPage == true){
			
			$hasWpEditor = false;
			
			if(empty($this->objLayoutType))
				UniteFunctionsUC::throwError("Blox layout not inited!");
			
		}
		
		$wrapperAddClass = "";
		if($this->isGutenbergActive == true){
			$hasWpEditor = false;
			$wrapperAddClass = " uc-hidden";
		}
				
		$bloxPageTitle = $this->textSingle . __(" Page", BLOXBUILDER_TEXTDOMAIN);
		$bloxLandingPageTitle = $this->textSingle .  __(" Landing Page", BLOXBUILDER_TEXTDOMAIN);
		
		$isImportFromCatalog = true;
		
		
		$titleLayout = __("Page", BLOXBUILDER_TEXTDOMAIN);
		$titleBloxLayout = $this->textSingle.__(" Layout", BLOXBUILDER_TEXTDOMAIN);
		
		
		if($this->isBloxLayoutPage == true){
			
			$layoutTypeTitle = $this->objLayoutType->textShowType;
			$titlePrefix = $this->textSingle. __(" $layoutTypeTitle Layout - ", BLOXBUILDER_TEXTDOMAIN);
			
			$bloxLandingPageTitle = $titlePrefix.__("Empty Canvas Preview", BLOXBUILDER_TEXTDOMAIN);
			$bloxPageTitle = $titlePrefix.__("With Theme Html Preview", BLOXBUILDER_TEXTDOMAIN);
			
			$layoutType = $this->objLayoutType->typeNameDistinct;
			
			if($layoutType == GlobalsUC::ADDON_TYPE_LAYOUT_SECTION)
				$isImportFromCatalog = false;
			
			$titleLayout = $this->objLayoutType->textSingle;
			$titleBloxLayout = $this->objLayoutType->textSingle;
			
			$urlBack = HelperUC::getViewUrl_LayoutsList(null, $layoutType);
			
			$textBack = __("Back To All ", BLOXBUILDER_TEXTDOMAIN).$this->objLayoutType->textPlural;
			
		}
		
		$textEditWith = __("Edit With ",BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
		$textEditVisualEditor = $textEditWith.__(" Visual Editor",BLOXBUILDER_TEXTDOMAIN);
				
		
		?>
				
		<?php if($hasWpEditor == true):?>
			
			<br><br>
			
			<a id="uc_button_to_blox_page" href="javascript:void(0)" class="button-primary uc-edit-post-button-to-blox"><?php echo $textEditWith?></a>
			
		<?php endif?>
		
		
		<?php if($this->isGutenbergActive == true):?>
		
			<div id="uc_blox_buttons_template" style="display:none">
			
				<div class="blox-header-buttons-wrapper">
					<a id="uc_button_to_blox_page" href="javascript:void(0)" class="button-primary uc-edit-post-button-to-blox"><?php echo $textEditWith?></a>
					<a id="uc_button_return_to_wp" href="javascript:void(0)" class="button-secondary uc-edit-post-button-return-to-wp"><?php _e("Back to WordPress Editor", BLOXBUILDER_TEXTDOMAIN);?></a>
				</div>
				
			</div>
			
		<?php endif?>
		
		<input type="hidden" id="blox_page" name="blox_page" value="<?php echo $this->isBloxPage?>">
		
		
		<div id="uc_edit_blox_page_wrapper" data-posttype="<?php echo $postType?>" class="uc-edit-post-blox-page-wrapper<?php echo $wrapperAddClass?>">
		
			<?php if($hasWpEditor == true):?>
			<a id="uc_button_return_to_wp" href="javascript:void(0)" class="button-secondary uc-edit-post-button-return-to-wp"><?php _e("Back to WordPress Editor", BLOXBUILDER_TEXTDOMAIN);?></a>
			
			<br><br><br>
			<?php endif?>
			
			<?php if($this->isBloxLayoutPage):?>
			
			<a href="<?php echo $urlBack?>" class="button-secondary"><?php echo $textBack?></a>
			
			<br><br><br>
			
			<?php endif?>
			
			<div class="uc-blox-title uc-title-bloxpage">
				<?php echo $bloxPageTitle?>
				<div class="uc-blox-subtitle"><?php echo $this->textLayoutInfo?></div>
			</div>
			
			<div class="uc-blox-title uc-title-blox-landing-page">
				<?php echo $bloxLandingPageTitle?>
				
				<div class="uc-blox-subtitle"><?php echo $this->textLayoutInfo?></div>
				
			</div>
			
								
			<a id="uc_button_edit_with_blox" href="javascript:void(0)" data-link="<?php echo $linkVisual?>" class="button-primary"><?php echo $textEditVisualEditor?></a>
			
			<br><br><br>
			
			<a id='uc_button_import_layout' class='button-secondary uc-button-import' href='javascript:void(0)'><?php echo __("Import ", BLOXBUILDER_TEXTDOMAIN).$titleBloxLayout?></a>
			
			<?php if($isImportFromCatalog == true):?>
			&nbsp;&nbsp;
			
			<a id='uc_button_import_layout_from_catalog' class='button-secondary uc-button-import-catalog' href='javascript:void(0)'><?php _e("Import From Catalog", BLOXBUILDER_TEXTDOMAIN)?></a>
			
			<?php endif?>
			
			&nbsp;&nbsp;
			
			<a id='uc_button_export_layout' class='button-secondary uc-button-export' href='javascript:void(0)'><?php echo __("Export ", BLOXBUILDER_TEXTDOMAIN).$titleBloxLayout?></a>
			
			&nbsp;&nbsp;
			
			<a id='uc_button_duplicate' class='button-secondary uc-button-duplicate' href='javascript:void(0)'><?php echo __("Duplicate ", BLOXBUILDER_TEXTDOMAIN).$titleLayout?></a>
			<span class='loader_text uc-loader-duplicate' style='display:none'><?php _e("Duplicating", BLOXBUILDER_TEXTDOMAIN)?>...</span>
			
		</div>
		<?php 
	}
	
	
	
	
	/**
	 * add edit post footer html
	 */
	private function addEditPostFooterHtml(){

		$this->putImportLayoutDialog();
		
	}
	
	
	/**
	 * on save post - save or remove blox page meta
	 */
	public function onSavePost($postID, $post){
		
		$action = UniteFunctionsUC::getPostVariable("action", "",UniteFunctionsUC::SANITIZE_KEY);
		if($action != "editpost")
			return(false);
		
		if(empty($postID))
			return(false);
		
		if(empty($post))
			return(false);
		
		$postType = $post->post_type;
		$isBloxEnabled = HelperProviderCoreUC_Blox::isPostBloxEditable($postType);
		if($isBloxEnabled == false)
			return(false);
		
		
		$isBloxPage = UniteFunctionsUC::getPostVariable("blox_page", "",UniteFunctionsUC::SANITIZE_KEY);
		$isBloxPage = UniteFunctionsUC::strToBool($isBloxPage);
		
		//save if blox page
		if($isBloxPage == true)
			$this->setPostBloxPage($postID);
		else
			$this->setPostWPPage($postID, $post);
		
	}
	
	
	/**
	 * set post as blox page
	 */
	private function setPostBloxPage($postID){
			
		update_post_meta($postID, GlobalsProviderUC::META_KEY_BLOX_PAGE, "true");
		
	}
	
	
	/**
	 * set post wordpress page
	 */
	private function setPostWPPage($postID, $post){
		
		update_post_meta($postID, GlobalsProviderUC::META_KEY_BLOX_PAGE, "false");
		
		//remove blox page template if exists
		$pageTemplate = UniteFunctionsWPUC::getPostPageTemplate($post);
		if($pageTemplate == GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE)
			UniteFunctionsWPUC::updatePageTemplateAttribute($postID, "default");
		
	}
	
	
	private function a_____________SCRIPTS______________(){}
	
	/**
	 * on add scripts on edit post page
	 */
	protected function onAddScriptsPostPage(){
		
		$globalJsOutput = HelperHtmlUC::getGlobalJsOutput();
		UniteProviderFunctionsUC::printCustomScript($globalJsOutput);
		
		self::addMustScripts();
		
		self::onAddScriptsBrowser();
		HelperUC::addScript("unitecreator_admin_layouts", "unitecreator_admin_layouts");
		
		//add local file
		$urlViewAdmin = HelperProviderCoreUC_Blox::$urlAssets."edit_post_admin.js";
		HelperUC::addScriptAbsoluteUrl($urlViewAdmin, "uc_edit_post_admin");
	}
	
	
	/**
	 * add scripts on layouts list page
	 */
	protected function onAddScriptsLayoutsListPage(){
		
		$globalJsOutput = HelperHtmlUC::getGlobalJsOutput();
		UniteProviderFunctionsUC::printCustomScript($globalJsOutput);
		
		UniteCreatorAdmin::setView(GlobalsUC::VIEW_LAYOUTS_LIST);
		UniteCreatorAdmin::onAddScripts();
		
		//add local file
		$urlViewAdmin = HelperProviderCoreUC_Blox::$urlAssets."layouts_postlists_admin.js";
		HelperUC::addScriptAbsoluteUrl($urlViewAdmin, "layouts_postlists_admin");
	}
	
	
	
	/**
	 * add outside plugin scripts
	 */
	public function onAddOutsideScripts(){
		
		parent::onAddOutsideScripts();
		
		$isLayoutsListPage = $this->isLayoutsListPage();
				
		if($isLayoutsListPage == true){
			
			$this->initPostListVars();
			$this->onAddScriptsLayoutsListPage();
		}
		else
			if($this->isInsideBloxPostEditablePage()){
				
				$this->onAddScriptsPostPage();
			}
		
	}
	
	
	private function a_____________INIT______________(){}
	
	
	/**
	 * import blox plugin addons
	 */
	protected function importBloxPluginAddons(){
		
		$pathAddons = HelperProviderCoreUC_Blox::$pathCore."addons_install/";
		
		$isImported = false;
		if(is_dir($pathAddons)){
			$arrFiles = UniteFunctionsUC::getFileList($pathAddons);
			$isImported = !empty($arrFiles);
			$this->installAddonsFromPath($pathAddons);
		}
		
		return($isImported);
	}
	
	
	/**
	 * import package addons, from themes and from the plugin
	 */
	protected function importPackageAddons(){
		
		$objAddons = new UniteCreatorAddons();
		
		$isImported = false;
		
		$objAddonType = UniteCreatorAddonType::getAddonTypeObject("");
		
		$numAddons = $objAddons->getNumAddons(null, null, $objAddonType);
		
		//$numAddons = 0;		//remove me
		
		if($numAddons == 0)
			$isImported = $this->importBloxPluginAddons();
		
		$isImportedParent = parent::importPackageAddons();
		
		if($isImported == false)
			$isImported = $isImportedParent;
		
		return($isImported);
	}
	
	
	/**
	 * load plugins
	 */
	public function loadAdminPlugins(){
				
		$pathCreatorPlugin = HelperProviderCoreUC_Blox::$pathPlugins."create_addons/plugin.php";
		
		require $pathCreatorPlugin;
	}
	
	
	/**
	 * add admin menu links
	 */
	protected function addAdminMenuLinks(){
		
		parent::addAdminMenuLinks();
		
		$this->addSubMenuPage(GlobalsUC::VIEW_LICENSE, __('License',BLOXBUILDER_TEXTDOMAIN), "adminPages");
		
	}
	
	
	/**
	 * init post list vars
	 */
	private function initPostListVars(){
		
		$screen = get_current_screen();
				
		if(empty($screen))
			UniteFunctionsUC::throwError("The screen should be inited");
		
			
		$this->postType = $screen->post_type;
		
	}
	
	/**
	 * is gutenberg active
	 */
	private function isGutenbersActive(){
				
		//legacy
		$isActive = function_exists( 'the_gutenberg_project' );
		
		if($isActive == true)
			return(true);
	}
	
	
	/**
	 * init blox cars if it's blox page or not
	 */
	private function initBloxVars(){
		
		
		//avoid double init
		if($this->isInited == true)
			return(false);
		
		$this->isInited = true;
		
		$isInsideEditablePage = $this->isInsideBloxPostEditablePage();
		
		if($isInsideEditablePage == false)
			return(false);
		
		if($this->isGutenbergActive == false)
			$this->isGutenbergActive = $this->isGutenbersActive();
		
		$post = get_post();
		
		$this->isBloxPage = HelperProviderCoreUC_Blox::isPostIsBloxPage($post);
		
		
		if($this->isBloxPage == false)
			return(false);

					
		$postID = $post->ID;
		
		$this->isLandingPage = HelperProviderCoreUC_Blox::isInsideLandingPagePost($post);
		
		$this->postType = $post->post_type;
				
		$this->objLayout = new UniteCreatorLayout();
		
		if($this->postType == GlobalsProviderUC::POST_TYPE_LAYOUT || $this->postType == "uc_layout")
			$this->isBloxLayoutPage = true;
		
		try{
			
			$this->objLayout->initByID($postID);
			$this->textLayoutInfo = $this->objLayout->getInfoText();
			$this->objLayoutType = $this->objLayout->getObjLayoutType();
			
		}catch(Exception $e){
		}
		
	}
		
	
	/**
	 * run on wp init
	 */
	public function initAdminPagesEditor(){
						
		/*
		$this->addLocalFilter('manage_posts_columns', "addExtraColumn_define", 1);
		$this->addAction("manage_pages_custom_column", "addExtraColumn_output", false, 2);
		*/
		
		$this->addLocalFilter("page_row_actions", "addLayoutsListActions", 2);
		$this->addLocalFilter("post_row_actions", "addLayoutsListActions", 2);		
		$this->addLocalFilter("display_post_states", "addPostTitleState", 2);
		
		HelperProviderCoreUC_Blox::registerPostTypes();
		
	}
	
	
	/**
	 * test
	 */
	protected function test(){
		
		$templates = get_page_templates();
		
		dmp($templates);
		exit();
	}
		
	/**
	 * on init
	 */
	public function onInit(){
		
		HelperProviderCoreUC_Blox::onInitGlobalHandler();
		
	}
	
	/**
	 * update blox as post page from data
	 */
	private function updatePostAsBloxPageFromData($data){
		
		$postID = UniteFunctionsUC::getVal($data, "postid");
		UniteFunctionsUC::validateNotEmpty($postID, "post id");
		
		$this->setPostBloxPage($postID);
		
	}		
	
	
	/**
	 * update post as wp page from data
	 */
	private function updatePostAsWPPageFromData($data){
		
		$postID = UniteFunctionsUC::getVal($data, "postid");
		UniteFunctionsUC::validateNotEmpty($postID, "post id");
		
		$post = get_post($postID);
		
		$this->setPostWPPage($postID, $post);
		
	}
	
	
	/**
	 * do admin custom action
	 */
	public function doAdminAjaxAction($found, $action, $data){
		
		$found = true;
		switch($action){
			case "update_post_blox_page":
				$this->updatePostAsBloxPageFromData($data);
				HelperUC::ajaxResponseSuccess(__("Post Updated", BLOXBUILDER_TEXTDOMAIN));
			break;
			case "update_post_wp_page":
				$this->updatePostAsWPPageFromData($data);
				HelperUC::ajaxResponseSuccess(__("Post Updated", BLOXBUILDER_TEXTDOMAIN));
			break;
			default:
				$found = false;
			break;
		}
		
		return($found);
	}
	
	
	/**
	 * on add gutenberg assets
	 */
	public function onAddGutenbergAssets(){
		
		$this->isGutenbergActive = true;		
	}
	
	
	/**
	 * put dashobard widget
	 */
	public function putDashboardWidgetHtml(){
		
		$urlReleaseLog = GlobalsUC::$urlPlugin."release_log.txt";
		
		$isActivated = GlobalsUC::$isProductActive;
		
		$urlViewLicense = HelperUC::getViewUrl("license");
		
		
		?>
		
		<div class="blox-dashboard-widget-wrapper">
			
			<div class="uc-dashboard-section uc-dashboard-widget-version">
			
				<?php _e("Version", BLOXBUILDER_TEXTDOMAIN)?>: <?php echo BLOXBUILDER_VERSION?>
								
			</div>
			
			<div class="uc-dashboard-section uc-dashboard-activate">
				<?php if($isActivated == true):?>
				
					| <?php _e("pro version active", BLOXBUILDER_TEXTDOMAIN)?>
				
				<?php else:?>
					
					<a href="<?php echo $urlViewLicense?>" class="uc-dashboard-link-activate">Activate Pro</a>
					
				<?php endif?>
			
			</div>
			
			<div class="uc-dashboard-section uc-dashboard-widget-release-log">
			
				<?php _e("Release Log")?>: 
							
				<iframe class="uc-dashboard-widget-release-iframe" src="<?php echo $urlReleaseLog?>"></iframe>
			
			</div>
			
		</div>
				
		<?php 
	}
	
	/**
	 * register
	 */
	public function registerDashboardWidget(){
		
		$isEnableWidgets = HelperUC::getGeneralSetting("enable_dashboard_widgets");
		$isEnableWidgets = UniteFunctionsUC::strToBool($isEnableWidgets);
		
		if($isEnableWidgets == false)
			return(false);
		
		$title = __( 'Blox Page Builder Overview', BLOXBUILDER_TEXTDOMAIN );
		
		wp_add_dashboard_widget( 'blox-dashboard-overview', $title , array($this, 'putDashboardWidgetHtml') );
		
	}
	
	
	/**
	 * init
	 */
	protected function init(){
		
		parent::init();
		
		$this->isGutenbergActive = $this->isGutenbersActive();
				
		$this->addAction("init", "onInit");
		
		$this->addAction("admin_init", "initAdminPagesEditor");
		
		//gutenberg
		$this->addAction("enqueue_block_editor_assets", "onAddGutenbergAssets");
		
		$this->addLocalFilter("admin_body_class", "setAdminBodyClass");
		$this->addAction("edit_form_after_title","addEditPostBodyHtml");
		
		$this->addAction("wp_after_admin_bar_render","addEditPostBodyHtmlGutenberg");
		
		$this->addAction("admin_footer", "onAdminFooter");
				
		$this->addLocalFilter(UniteCreatorFilters::FILTER_URL_LAYOUTS_LIST, "getUrlLayoutList", 2);
		$this->addAction("save_post", "onSavePost",false, 2);

		$this->addLocalFilter(UniteCreatorFilters::FILTER_ADMIN_AJAX_ACTION, "doAdminAjaxAction", 3);
		
		$this->addAction("wp_dashboard_setup", "registerDashboardWidget");
				
		//$this->addAction(UniteCreatorFilters::ACTION_VALIDATE_GENERAL_SETTINGS, "validateGeneralSettings");
		
	}
	
}