<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorGridBuilderActionsPanelWork extends HtmlOutputBaseUC{
	
	private $isLiveView = false;
	protected $isEditMode = false;
	protected $title, $name;
	protected $layoutID = null, $layoutType = null, $objLayoutType;
	protected $isTemplate = false, $layoutTypeTitle;	
	protected $shortcodeWrappers = "{}";
	protected $extraParams = array();
	protected $arrIcons, $isShowGridSettings = true;
	protected $putShortcodeSection = true;
	protected $showParamsSettings = false, $showParamsSettingsTopBarButton = false;
	protected $putPageName = false;
	protected $putPageUrl = false;
	
	
	private function a_______SETTERS_______(){}
	
	
	
	/**
	 * set post type
	 */
	public function setExtraParams($extraParams){
		
		$this->extraParams = $extraParams;
		
	}
	
	/**
	 * set if put shortcode or not
	 */
	public function setPutShorcodeSection($toPut){
		
		$this->putShortcodeSection = $toPut;
		
	}
	
	/**
	 * get icons array
	 */
	public function setArrIcons($arrIcons){
		
		$this->arrIcons = $arrIcons;
	}
	
	/**
	 * set live view
	 */
	public function setLiveView(){
		
		$this->isLiveView = true;
	}
	
	
	private function a_______GETTERS_______(){}
	
	
	/**
	 * get exit url
	 */
	protected function getUrlBack(){
		
		if($this->isTemplate == false){
			
			$urlLayoutsList = HelperUC::getViewUrl_LayoutsList($this->extraParams, $this->layoutType);
			
		}else{
			
			$urlLayoutsList = HelperUC::getViewUrl_TemplatesList($this->extraParams, $this->layoutType);
			
		}
			
		return($urlLayoutsList);
	}
	
	
	/**
	 * get main menu items
	 */
	protected function getArrMainMenuItems(){
		
		$arrMenu = array();

		$urlBack = $this->getUrlBack();
		$urlPreview = $this->getUrlPreview();
		
		
		//box and live view
		if($this->isLiveView == true){	
			
			$urlBoxView = HelperUC::getViewUrl_Layout($this->layoutID, "viewmode=box");
			//$urlBoxView = htmlspecialchars($urlBoxView);
			
			$arrMenu[] = array(
				   "text"=>__("To Box View", BLOXBUILDER_TEXTDOMAIN),
				   "action"=>"tobox",
				   "data"=> array(
								"message"=>__("Redirecting to Box View", BLOXBUILDER_TEXTDOMAIN),
								"url_redirect"=>$urlBoxView
							),		
				   "icon"=>$this->getIcon("to_box"));
		}else{
				
			$urlLiveView = HelperUC::getViewUrl_Layout($this->layoutID,"viewmode=live");
			
			$arrMenu[] = array(
							
				   "text"=>__("To Live View", BLOXBUILDER_TEXTDOMAIN),
				   "action"=>"tolive",
				   "data"=> array(
								"message"=>__("Redirecting to Live View", BLOXBUILDER_TEXTDOMAIN),
								"url_redirect"=>$urlLiveView
							),		
				   "icon"=>$this->getIcon("to_live"));
			
		}
		
		$arrMenu[] = array(
			   "text"=>__("Import Page", BLOXBUILDER_TEXTDOMAIN),
			   "action"=>"import",
			   "icon"=>$this->getIcon("import"));
		
		$arrMenu[] = array(
			   "text"=>__("Export Page", BLOXBUILDER_TEXTDOMAIN),
			   "action"=>"export",
			   "icon"=>$this->getIcon("export"));
		
		$arrMenu[] = array(
			   "text"=>__("Duplicate Page", BLOXBUILDER_TEXTDOMAIN),
			   "action"=>"duplicate",
			   "icon"=>$this->getIcon("duplicate"));
		
		//add grid settings
		if($this->isShowGridSettings == true){
			
			$arrMenu[] = array(
				   "text"=>__("Settings", BLOXBUILDER_TEXTDOMAIN),
				   "action"=>"page_settings",
				   "icon"=>$this->getIcon("settings"));			
		}
		
		if($this->showParamsSettings == true){
						
			$arrMenu[] = array(
				   "text"=> $this->objLayoutType->paramSettingsTitle,
				   "action"=>"page_params",
				   "icon"=>$this->getIcon("screenshot"));
		}
		
				
		$arrMenu[] = array(
			   "text"=>__("Preview Page", BLOXBUILDER_TEXTDOMAIN),
			   "href"=>$urlPreview,
			   "isblank"=>true,
			   "icon"=>$this->getIcon("preview"));
		
		$arrMenu[] = array(
			   "text"=>__("To Test Design Mode", BLOXBUILDER_TEXTDOMAIN),
			   "action"=>"to_view_mode",
			   "class"=>"uc-when-regular-mode",
			   "icon"=>$this->getIcon("to_view_mode"));
		
		$arrMenu[] = array(
			   "text"=>__("Exit Test Design Mode", BLOXBUILDER_TEXTDOMAIN),
			   "action"=>"to_regular_mode",
			   "class"=>"uc-when-view-mode",
			   "icon"=>$this->getIcon("to_edit_mode"));
								
		$arrMenu[] = array(
			   "text"=>__("Exit Without Save", BLOXBUILDER_TEXTDOMAIN),
			   "action"=>"exit",
			   "data"=>array(
			   					"url_back"=>$urlBack,
								"message"=>__("Going Back...", BLOXBUILDER_TEXTDOMAIN)
							),
			   "icon"=>$this->getIcon("save_exit"));
		
		$arrMenu[] = array(
			   "text"=>__("Save And Exit", BLOXBUILDER_TEXTDOMAIN),
			   "action"=>"save_exit",
			   "data"=>array(
			   					"url_back"=>$urlBack,
								"message"=>__("Going Back...", BLOXBUILDER_TEXTDOMAIN)
							),
			   "icon"=>$this->getIcon("save"));
							
		
		return($arrMenu);
	}
	
	
	
	/**
	 * get preview page url
	 */
	protected function getUrlPreview(){
		
		$urlPreview = HelperUC::getViewUrl_LayoutPreview(0, true);
		
		if($this->isEditMode)
			$urlPreview = HelperUC::getViewUrl_LayoutPreview($this->layoutID, true);
		
		return($urlPreview);
	}
	
	/**
	 * get icon by name
	 */
	protected function getIcon($name){
		
		$icon = UniteFunctionsUC::getVal($this->arrIcons, $name);
		
		if(empty($icon)){
			$strIcons = print_r($this->arrIcons, true);
			UniteFunctionsUC::throwError("Icon $name not found. there are the icons: $strIcons");
		}
		
		return($icon);
	}
	
	
	private function a_______HTML_OUTPUT_______(){}
	
	
	/**
	 * get main menu html
	 */
	public function getMainMenuHtml(){
		
		$arrItems = $this->getArrMainMenuItems();
		
		$html = "";
		$html .= "<ul class='uc-grid-panel-menu'>".self::BR;
		
		foreach($arrItems as $item){
			
			$href = UniteFunctionsUC::getVal($item, "href");
			if(empty($href)){
				$href = "javascript:void(0)";
			}
						
			$action = UniteFunctionsUC::getVal($item, "action");
			$text = UniteFunctionsUC::getVal($item, "text");
			$icon = UniteFunctionsUC::getVal($item, "icon");
			$isblank = UniteFunctionsUC::getVal($item, "isblank");
			$data = UniteFunctionsUC::getVal($item, "data");
			$itemClass = UniteFunctionsUC::getVal($item, "class");
			
			$htmlBlank = "";
			if($isblank === true)
				$htmlBlank = " target='_blank'";
			
			$htmlData = "";
			if(!empty($data))
				$htmlData = UniteFunctionsUC::jsonEncodeForHtmlData($data,"params");
			
			$dataAction = "";
			$arrClasses = array();
			if(!empty($action)){
				$dataAction = " data-action='$action'";
				$arrClasses[] = "uc-panel-action-button";
			}
			
			if(!empty($itemClass))
				$arrClasses[] = $itemClass;
			
			if(!empty($arrClasses))
				$class =' class="'. implode(" ", $arrClasses).'"';
			
			$html .= self::TAB."<li>".self::BR;
			
			$addHtml = $dataAction.$class.$htmlBlank.$htmlData;
			
			$html .= "	<a href=\"$href\" {$addHtml}>".self::BR;
			$html .= "		<i class='{$icon}' aria-hidden='true'></i>".self::BR;
			$html .= "		<span>{$text}</span>".self::BR;
			$html .= "	</a>".self::BR;
			
			$html .= "</li>".self::BR;
			
		}
		
		$html .= "</ul>".self::BR;
		
		return($html);
	}
		
		
	
	/**
	 * put layout title edit window
	 */
	protected function putLayoutTitleWindow(){
	    
	    $isNew = empty($this->layoutID);
	    
	    $styleNew = "";
	    $styleExisting = " style='display:none'";
	    
	    if($isNew == false){
	        $styleNew = " style='display:none'";
	        $styleExisting = "";
	    }
	        
	    $iconDown = $this->getIcon("angle_down");
	    
	    $innerAddClass = "";
	    if($this->putShortcodeSection == false)
	    	$innerAddClass = " uc-no-shortcode";
	    
	    $textPageTitle = $this->layoutTypeTitle. __(" Title", BLOXBUILDER_TEXTDOMAIN);
	    $newPageTitle = $this->layoutTypeTitle.__(" Page", BLOXBUILDER_TEXTDOMAIN);

	    $newPageName = $this->layoutTypeTitle."_name";
	    
	    
	    $title = $this->title;
	    
	    if($this->putPageName == true){
	    	$textPageName = $this->layoutTypeTitle. __(" Name", BLOXBUILDER_TEXTDOMAIN);
	    	$name = $this->name;
	    }
	    	    
	    
	    $typeLower = strtolower($this->layoutTypeTitle);
	    
	    $textShortcode = __("The shortcode will be availble after save ", BLOXBUILDER_TEXTDOMAIN).$typeLower;
	   	
		?>
		        
    		<div class='uc-layout-title-panel'>
    			
                <div class="uc-visible-part">
                
    				<?php if($this->objLayoutType->isBasicType == false):?>
	    			
	    				<span class="uc-layout-title"><?php echo $this->layoutTypeTitle?> - </span>
    				
    				<?php endif?>
    				
	    			<span id="uc_page_title"><?php echo UniteFunctionsUC::sanitizeAttr($title)?></span>
	                	<i class="<?php echo $iconDown?>" aria-hidden="true"></i>
					</div>
					
					<div id="uc_layout_title_box" class="uc-layout-title-box">
					
	                	<div class="uc-layout-title-box-inner unite-ui <?php echo $innerAddClass?>">
	                		
	                		<!-- page title and name -->
	                		<div class='uc-page-name-wrapper'>
	                        	 <div class="uc-titlebox-label"><?php echo $textPageTitle?>:</div>
	                        	 <input type="text" class="uc-input-layout-title" value="<?php echo UniteFunctionsUC::sanitizeAttr($title)?>" id="uc_layout_title" placeholder="<?php echo $newPageTitle?>">
	                        	 
	                        	 <?php if($this->putPageName == true):?>
	                        	 
	                        	 <div class="uc-titlebox-label uc-label-name"><?php echo $textPageName?>:</div>
	                        	 <input type="text" class="uc-input-layout-name" value="<?php echo UniteFunctionsUC::sanitizeAttr($name)?>" id="uc_layout_name" placeholder="<?php echo $newPageName?>">
	                        	 
	                        	 <?php if($this->putPageUrl == true):?>
	                        	 
	                        	 <div class="uc-titlebox-page-url">
	                        	 	<?php _e("Page Url: ")?>
	                        	 	 <?php echo GlobalsUC::$url_base ?><span id="uc_page_url_alias"><?php echo UniteFunctionsUC::sanitizeAttr($name)?></span>
	                        	 </div>
	                        	 
	                        	 <?php endif?>
	                        	 
	                        	 <?php endif?>
	                        	 
	                        	 <a id="uc_button_rename_page" href="javascript:void(0)" class="unite-button-primary" ><?php echo _e("Save", BLOXBUILDER_TEXTDOMAIN)?></a>
	                        	 <span id="uc_button_rename_page_loader" class="loader_text" style="display:none"><?php _e("Saving", BLOXBUILDER_TEXTDOMAIN)?>...</span>
                        	 </div>
                        	 
                        	<?php if($this->putShortcodeSection == true): ?>
                        
                        	<!-- shortcode -->
                        	 <div class="uc-titlebox-label uc-label-shortcode"><?php _e("Shortcode:", BLOXBUILDER_TEXTDOMAIN)?></div> 
                        	 
                        	 <div class="uc-layout-newpage" <?php echo $styleNew?> >
                        	 
                            	 <div class="vert_sap10"></div>
                            	 	
                            	 	<div class="uc-titlebox-text">
                            		 	<?php echo $textShortcode?>
                            	  </div>
                            	  
                        	 </div>
                        	 
                        	 
	                        	 <div class="uc-layout-existingpage" <?php echo $styleExisting?> >
	                        	 
	                            	 <input type="text" id="uc_layout_shortcode" class="uc-input-shortcode unite-input-regular"  data-shortcode="<?php echo GlobalsUC::$layoutShortcodeName?>" data-wrappers="<?php echo $this->shortcodeWrappers?>" readonly onfocus="this.select()" value="" title="<?php echo UniteFunctionsUC::sanitizeAttr($this->title)?>">
	                        		 
	                        		 <div class="vert_sap10"></div>
	                        		 
	                        		 <a id="uc_link_copy_shortcode" class="uc-shortcode-text-copy"><?php _e("Copy shortcode to clipoard", BLOXBUILDER_TEXTDOMAIN)?></a>
	    	                	
	    	                	</div>	
                        	
                        	<?php endif?>
	                		
                		</div>
                	
				</div>
	                     	                
            </div>
		
		<?php 
	}
	
	
	/**
	 * put panel html
	 */
	public function putPanelHtml(){
		
		//validate icons
		UniteFunctionsUC::validateNotEmpty($this->arrIcons);
		
		$isNew = empty($this->layoutID);
		
		$styleNew = "";
		$styleExisting = " style='display:none'";
		
		if($isNew == false){
		    $styleNew = " style='display:none'";
		    $styleExisting = "";
		}
		
		if($this->isTemplate)
			$urlLayoutsList = HelperUC::getViewUrl_TemplatesList($this->extraParams, $this->layoutType);
		else
			$urlLayoutsList = HelperUC::getViewUrl_LayoutsList($this->extraParams);
		
		$urlPreview = $this->getUrlPreview();
		$urlPreviewTemplate = HelperUC::getViewUrl_LayoutPreviewTemplate();
		
		$urlPreviewAddHtml = "";
		if(!empty($urlPreviewTemplate)){
			$urlPreviewTemplate = htmlspecialchars($urlPreviewTemplate);
			$urlPreviewAddHtml = "data-template='$urlPreviewTemplate'";
		}
		
		$iconDesktop = $this->getIcon("desktop");
		$iconMobile = $this->getIcon("mobile");
		$iconTablet = $this->getIcon("tablet");
		
		$iconSettings = $this->getIcon("settings");
		$iconPreview = $this->getIcon("preview");
		$iconOpenMenu = $this->getIcon("menu_opened");
		$iconCloseMenu = $this->getIcon("menu_closed");
		$iconSpinner = $this->getIcon("spinner");
		$iconPageParams = $this->getIcon("screenshot");
		
		
		$textSavePage = __("Save Page", BLOXBUILDER_TEXTDOMAIN);
		if($this->isTemplate)
			$textSavePage = __("Save", BLOXBUILDER_TEXTDOMAIN)." ".$this->layoutTypeTitle;
		
		
		?>
			<div class="uc-edit-layout-panel">
				
				<!-- left buttons  -->
				
            	<a href="javascript:void(0)" data-action="open_main_menu" title="<?php _e("Open Menu",BLOXBUILDER_TEXTDOMAIN)?>" class="uc-toppanel-button unite-float-left uc-toppanel-button-menu">
	                <i class="<?php echo $iconCloseMenu?> uc-menu-closed" aria-hidden="true"></i>
	                <i class="<?php echo $iconOpenMenu?> uc-menu-opened" aria-hidden="true"></i>
            	</a>
	     		
            	
	     		<a id="uc_button_view_size_desktop" href="javascript:void(0)" data-action="view_desktop" title="<?php _e("To Desktop View", BLOXBUILDER_TEXTDOMAIN)?>" class="uc-toppanel-button unite-float-left uc-button-view-related uc-view-desktop">
                	<i class="<?php echo $iconDesktop?>" aria-hidden="true"></i>
            	</a>
            	
	     		<a id="uc_button_view_size_tablet" href="javascript:void(0)" data-action="view_tablet" title="<?php _e("To Tablet View", BLOXBUILDER_TEXTDOMAIN)?>" class="uc-toppanel-button unite-float-left uc-button-view-related uc-view-tablet">
                	<i class="<?php echo $iconTablet?>" aria-hidden="true"></i>
            	</a>
            	
	     		<a id="uc_button_view_size_mobile" href="javascript:void(0)" data-action="view_mobile" title="<?php _e("To Mobile View", BLOXBUILDER_TEXTDOMAIN)?>" class="uc-toppanel-button unite-float-left uc-button-view-related uc-view-mobile">
                	<i class="<?php echo $iconMobile?>" aria-hidden="true"></i>
            	</a>
            	            	
            	<div id="uc_buffer_indicator" class="uc-buffer-container unite-float-left" style='display:none;'>
            		<span class='uc-buffer-container-content'></span>
            		<div class='uc-buffer-container-icon-close'>X</div>
            	</div>
				
				<!-- left buttons end -->
	            
            	<!-- page title panel -->
            	<?php $this->putLayoutTitleWindow()?>


				<!-- right buttons -->
             	<a href="javascript:void(0)" id="uc_button_update_layout" title="<?php echo $textSavePage?>" class="uc-toppanel-button unite-float-right uc-button-save-layout"> 
                	<span class="uc-when-not-inited"><?php _e("Loading", BLOXBUILDER_TEXTDOMAIN)?></span>
                	<span id="uc_layout_save_button_text" class="uc-when-inited uc-text-save" ><?php _e("Save", BLOXBUILDER_TEXTDOMAIN)?></span>
	               	 <i id="uc_layout_save_button_loader" class="<?php echo $iconSpinner?> fa-spin" aria-hidden="true" style="display:none"></i>
            	</a>
            	
            	<a id="uc-button-preview-layout" href="<?php echo $urlPreview?>" <?php echo $urlPreviewAddHtml?> title="<?php _e("Open Preview", BLOXBUILDER_TEXTDOMAIN)?>" target="_blank" class="uc-toppanel-button unite-float-right uc-layout-existingpage uc-button-regular uc-toppanel-button-preview" <?php echo $styleExisting?>>
	                <i class="<?php echo $iconPreview?>" aria-hidden="true"></i>
	        	</a>
            	
            	<?php if($this->isShowGridSettings == true):?>
            	
            	<a id="uc_button_grid_settings" href="javascript:void(0)" title="<?php _e("Page Settings", BLOXBUILDER_TEXTDOMAIN)?>" class="uc-toppanel-button unite-float-right uc-button-regular uc-toppanel-button-settings">
	                <i class="<?php echo $iconSettings?>" aria-hidden="true"></i>
	        	</a>
            	
            	<?php endif?>
            	
            	<?php if($this->showParamsSettingsTopBarButton == true):?>
            	
            	<a id="uc_button_params_settings" href="javascript:void(0)" data-action="page_params" title="<?php echo $this->objLayoutType->paramSettingsTitle?>" class="uc-toppanel-button unite-float-right uc-button-regular uc-toppanel-button-settings">
	                <i class="<?php echo $iconPageParams?>" aria-hidden="true"></i>
	        	</a>
            	
            	<?php endif?>
            	
            	
			</div>
		
		
		<?php 
	}
	
	
	
	/**
	 * init by layout
	 */
	public function initByLayout(UniteCreatorLayout $objLayout){
		
		$objLayouts = new UniteCreatorLayouts();
		
		$isInited = $objLayout->isInited();
		
		if($isInited)
			$this->layoutID = $objLayout->getID();
		
		$this->layoutType = $objLayout->getLayoutType();
		$this->objLayoutType = $objLayout->getobjLayoutType();
		
		$this->isTemplate = $this->objLayoutType->isTemplate;
			
		$this->layoutTypeTitle = $this->objLayoutType->textShowType;
		
		if($this->objLayoutType->showPageSettings == false)	
			$this->isShowGridSettings = false;
		
		if(!empty($this->objLayoutType->paramsSettingsType)){
			$this->showParamsSettings = true;
			$this->showParamsSettingsTopBarButton = $this->objLayoutType->showParamsTopBarButton;
		}
			
		//init the layout object if in edit mode
		if(!empty($this->layoutID)){
			$this->isEditMode = true;
			
			$this->title = $objLayout->getTitle();
			$this->name = $objLayout->getName();
		}else{
			
			//if new mode - get new title
			$this->title = $objLayout->getNewLayoutTitle();
			$this->name = $objLayout->getNewLayoutName($this->title);
		}
		
		
	}
	
	
}