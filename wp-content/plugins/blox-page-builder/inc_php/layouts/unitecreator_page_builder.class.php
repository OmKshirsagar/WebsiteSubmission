<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorPageBuilderWork{

	protected $layoutID = null;
	protected $layoutType = null, $objLayoutType;
	
	protected $putFrame = true;		//disable it for testing
	protected $objLayout;
	protected $objActionsPanel;
	protected $isLiveView;
	protected $isEditMode = false;
	protected $objGridEditor;
	protected $objLayouts, $objLayoutsView;
	
	private $optionPanelHiddenAtStart = true;
	private $optionPanelInitWidth = 278;
	
	//outer related
	protected $urlViewLayoutEdit;
	protected $arrIconsOuter = null;
	protected $arrIconsInner = null;
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$isPutIframe = UniteFunctionsUC::getGetVar("dontputiframe", "", UniteFunctionsUC::SANITIZE_KEY);
		if($isPutIframe === "true")
			$this->putFrame = false;
			
		$this->objLayouts = new UniteCreatorLayouts();
		
	}
	
		
	
	/**
	 * set view mode
	 */
	protected function setViewMode(){
		
		$stateName = "layout_view_mode";
		
		//check live mode
		$viewMode = UniteFunctionsUC::getGetVar("viewmode", "",UniteFunctionsUC::SANITIZE_KEY);
		
		//save view mode
		if(!empty($viewMode)){
			HelperUC::setState($stateName, $viewMode);
		}else{
			$viewMode = HelperUC::getState($stateName);
		}
		
		if(empty($viewMode))
			$viewMode = "live";
		
		$this->isLiveView = true;
		if($viewMode == "box")
			$this->isLiveView = false;
				
	}
	
	/**
	 * init common objects
	 */
	protected function initCommon(UniteCreatorLayout $objLayout){
		
		$this->objLayout = $objLayout;
		
		$isInited = $objLayout->isInited();
		
		if($isInited)
			$this->layoutID = $objLayout->getID();
		
		$this->layoutType = $objLayout->getLayoutType();
		$this->objLayoutType = $objLayout->getObjLayoutType();
		
		$this->setViewMode();
		
		if(!empty($this->layoutID))
			$this->isEditMode = true;
		
	}
	
	protected function a____________INNER____________(){}
	
	
	/**
	 * init inner page builder
	 */
	public function initInner(UniteCreatorLayout $objLayout){
		
		$this->initCommon($objLayout);
		
		$arrIcons = $this->getArrInnerIcons();
		
		$this->objGridEditor = new UniteCreatorGridBuilderProvider();
		$this->objGridEditor->putJsInit();
		$this->objGridEditor->setGridID("uc_grid_builder");
		$this->objGridEditor->setArrIcons($arrIcons);
		
		if($this->isLiveView == true)
			$this->objGridEditor->setLiveView();
		
		//init the layout object if in edit mode
		if(!empty($this->layoutID))			
			$this->objGridEditor->initByLayout($this->objLayout);
		
	}
	
	
	/**
	 * put inner html
	 */
	protected function putInnerHtml(){
				
		?>
<div class="unite-content-wrapper unite-inputs uc-content-layout">

		<div id="uc_edit_layout_wrapper">
		
		<?php UniteProviderFunctionsUC::putInitHelperHtmlEditor()?>

			<div class="unite-clear"></div>
		
		<!-- right buttons end -->
		
			<?php 
				$this->objGridEditor->putGrid();
			?>
  		 
	</div>	<!-- layout edit wrapper --> 
</div>

<?php 
	
	}
	
	/**
	 * get display inner html
	 */
	public function getInnerHtml(){
		
		ob_start();
		
		$this->putInnerHtml();
		
		$content = ob_get_contents();
		
		ob_end_clean();
		
		return($content);
	}
	
	/**
	 * display inner side
	 */
	public function displayInner(){
				
		$this->putInnerHtml();
		
	}
	
	
	
	private function __________PANEL_________(){}
	
	/**
	 * put global settings dialog. stand alone function
	 */
	public function putLayoutsGlobalSettingsDialog(){
		
		$settingsGeneral = UniteCreatorLayout::getGlobalSettingsObject();
		
		$outputGeneralSettings = new UniteSettingsOutputWideUC();
		$outputGeneralSettings->setShowSaps(true);
		$outputGeneralSettings->init($settingsGeneral);
		
		?>
		
		<div id="uc_dialog_layout_global_settings" title="<?php HelperUC::putText("layouts_global_settings"); ?>" class="unite-inputs" style="display:none">
				
				<div class="unite-dialog-inner-constant">
		
				<?php 		
					$outputGeneralSettings->draw("uc_layout_general_settings", true);
					
				?>
				</div>
				
				<?php 
					$prefix = "uc_dialog_layout_global_settings";
					$buttonTitle = __("Update Global Settings", BLOXBUILDER_TEXTDOMAIN);
					$loaderTitle = __("Updating...", BLOXBUILDER_TEXTDOMAIN);
					$successTitle = __("Settings Updated", BLOXBUILDER_TEXTDOMAIN);
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
				
		</div>		
		
		
		<?php
	}
	
	
	/**
	 * get page font names
	 */
	public static function getPageFontNames($forAddons = false){
		
		$arrFontNames = array();
		
		if($forAddons == false)
			$arrFontNames["page"] = __("Page Font", BLOXBUILDER_TEXTDOMAIN);
		
		$arrFontNames["title"] = __("Title Font", BLOXBUILDER_TEXTDOMAIN);
		$arrFontNames["subtitle"] = __("Subtitle Font", BLOXBUILDER_TEXTDOMAIN);
		$arrFontNames["paragraph"] = __("Paragraph Font", BLOXBUILDER_TEXTDOMAIN);
		$arrFontNames["accent"] = __("Accent Text Font", BLOXBUILDER_TEXTDOMAIN);
		$arrFontNames["user"] = __("User Defined Font", BLOXBUILDER_TEXTDOMAIN);
		
		return($arrFontNames);
	}
	
	
	/**
	 * add default page fonts
	 */
	protected function modifyGridDialogSettings_addPageFonts($objGridSettings){
		
		//set default page fonts
		$settingFonts = $objGridSettings->getSettingByName("page_fonts");
		
		$arrFontNames = self::getPageFontNames();
		
		$settingFonts["font_param_names"] = $arrFontNames;
		
		$objGridSettings->updateArrSettingByName("page_fonts", $settingFonts);
		
		return($objGridSettings);
	}
	
	
	/**
	 * modify grid settings for dialog
	 */
	private function modifyGridDialogSettings($objGridSettings){
		
		$arrSettings = $objGridSettings->getArrSettings();
				
		$descPrefix = __(". If %s, it will be set to global value: ", BLOXBUILDER_TEXTDOMAIN);
		
		$optionsGlobal = UniteCreatorLayout::getGridGlobalOptions();
		
		$arrExceptToEmpty = array();
		
		foreach($arrSettings as $setting){
		
			$name = UniteFunctionsUC::getVal($setting, "name");
		
			//set replace sign
			switch($name){
				default:
					$replaceSign = "empty";
				break;
			}
		
			$descActualPrefix = sprintf($descPrefix, $replaceSign);
		
			//handle excepts
			$globalOptionExists = array_key_exists($name, $optionsGlobal);
			if($globalOptionExists == false)
				continue;
		
			$globalValue = UniteFunctionsUC::getVal($optionsGlobal, $name);
			$setting["description"] .=  $descActualPrefix.$globalValue;
			$setting["placeholder"] =  $globalValue;
		
			//handle to empty excerpts
			$isExceptEmpty = array_search($name, $arrExceptToEmpty);
			if($isExceptEmpty === false){
				$setting["value"] = "";
				$setting["default_value"] = "";
			}
		
			$objGridSettings->updateArrSettingByName($name, $setting);
			
		}
		
		
		//add default fonts
		$objGridSettings = $this->modifyGridDialogSettings_addPageFonts($objGridSettings);
				
		return($objGridSettings);
	}	
	
	
	
	/**
	 * put side panel
	 */
	private function putSidePanel(){
	    		
		$objSidePanel = new UniteCreatorGridBuilderPanel();

		//add main menu pane
	    $title = __("Main Menu", BLOXBUILDER_TEXTDOMAIN);
		
		$htmlMainMenu = $this->objActionsPanel->getMainMenuHtml();
	    
		$params = array(UniteCreatorGridBuilderPanel::PARAM_NO_HEAD=>true);
	    
	    $objSidePanel->addCustomHtmlPane("main-menu", $title, $htmlMainMenu, $params);
		
		//add grid settings pane
		$objGridSettings = UniteCreatorLayout::getGridSettingsObject();
	    $objGridSettings = $this->modifyGridDialogSettings($objGridSettings);
	    
	    $title = __("Page Settings", BLOXBUILDER_TEXTDOMAIN);
	    
	    $objSidePanel->addPane("grid-settings", $title, $objGridSettings, "uc_settings_grid");
		
	    //put page params pane, if avaliable
	    
	    if(!empty($this->objLayoutType->paramsSettingsType)){
	    	
			$objPageParamsSettings = $this->objLayout->getPageParamsSettingsObject($this->objLayoutType);
		    $title = $this->objLayoutType->paramSettingsTitle;
	    
		    $objSidePanel->addPane("page-params", $title, $objPageParamsSettings, "uc_settings_page_params");
	    }
		
	    
	    //add row settings
		
	    $objRowSettings = HelperUC::getSettingsObject("layout_row_settings");
	    $title = __("Section Settings", BLOXBUILDER_TEXTDOMAIN);
		
	    $objSidePanel->addPane("row-settings", $title, $objRowSettings, "uc_settings_row");
	    
	    //add save section settings
		
	    $objSaveSectionSettings = HelperUC::getSettingsObject("layout_save_section");
	    $title = __("Save Section", BLOXBUILDER_TEXTDOMAIN);
		
	    $objSidePanel->addPane("save-section", $title, $objSaveSectionSettings, "uc_settings_save_secton");
	    
	    
	    //add container settings
	    $objContainerSettings = HelperUC::getSettingsObject("layout_container_settings");
	    $title = __("Row Settings", BLOXBUILDER_TEXTDOMAIN);
	    $objSidePanel->addPane("container-settings", $title, $objContainerSettings, "uc_settings_container");
	    
	    
	    //add column settings
	    $objColumnSettings = HelperUC::getSettingsObject("layout_column_settings");
	    $title = __("Column Settings", BLOXBUILDER_TEXTDOMAIN);
	
	    $objSidePanel->addPane("col-settings", $title, $objColumnSettings, "uc_settings_col");

	    //add addon container settings
	    $objColumnSettings = HelperUC::getSettingsObject("layout_addon_container_settings");
	    $title = __("Addon Container Settings", BLOXBUILDER_TEXTDOMAIN);
	    
	    $objSidePanel->addPane("addon-container-settings", $title, $objColumnSettings, "uc_settings_addon_container");
	    
	    //add addon settings
	    $title = __("Addon Settings", BLOXBUILDER_TEXTDOMAIN);
	    $objSidePanel->addPane("addon-settings", $title, "get_addon_settings_html", "uc_settings_addon");
	    
	    
		//init
		$objSidePanel->init();
		
		if($this->optionPanelHiddenAtStart == true)
			$objSidePanel->setHiddenAtStart();
		
		$objSidePanel->setInitWidth($this->optionPanelInitWidth);
		
		//put html
		$objSidePanel->putHtml();
	}
	
	private function __________ICONS_________(){}
		
	
	/**
	 * get outer icons array
	 */
	protected function getArrOuterIcons(){
		
		if(!empty($this->arrIconsOuter))
			return($this->arrIconsOuter);
		
		$arrIcons = array();
		
		$arrIcons["desktop"] = "far fa-desktop";
		$arrIcons["tablet"] = "far fa-tablet";
		$arrIcons["mobile"] = "far fa-mobile";
		$arrIcons["menu_closed"] = "far fa-bars";
		$arrIcons["menu_opened"] = "far fa-times";
		$arrIcons["angle_down"] = "fal fa-angle-down";
		$arrIcons["cog"] = "far fa-cog";
		$arrIcons["eye"] = "far fa-eye";
		$arrIcons["to_box"] = "fal fa-th-large";
		$arrIcons["to_live"] = "fal fa-desktop";
		$arrIcons["import"] = "fal fa-download";
		$arrIcons["export"] = "fal fa-upload";
		$arrIcons["duplicate"] = "fal fa-clone";
		$arrIcons["settings"] = "fal fa-cog";
		$arrIcons["preview"] = "fal fa-eye";
		$arrIcons["to_view_mode"] = "fal fa-play-circle";
		$arrIcons["to_edit_mode"] = "fal fa-edit";
		$arrIcons["save"] = "fal fa-save";
		$arrIcons["save_exit"] = "fal fa-sign-out";
		$arrIcons["triangle"] = "fal fa-exclamation-triangle";
		$arrIcons["spinner"] = "fal fa-spinner";
		$arrIcons["screenshot"] = "fal fa-image";
		
		$arrIcons = UniteFontManagerUC::fa_maybeConvertArrIconsTo4($arrIcons);
		
		$this->arrIconsOuter = $arrIcons;
		
		return($arrIcons);
	}

	/**
	 * get outer icon
	 */
	private function getIconOuter($name){
		if(empty($this->arrIconsOuter))
			$this->getArrOuterIcons();
		
		$icon = UniteFontManagerUC::getIcon($name, $this->arrIconsOuter);
		
		return($icon);
	}
	
	
	/**
	 * get outer icons array
	 */
	protected function getArrInnerIcons(){
		
		if(!empty($this->arrIconsInner))
			return($this->arrIconsInner);
		
		$arrIcons = array();
				
		$arrIcons["duplicate"] = "fal fa-clone";
		$arrIcons["settings"] = "fal fa-cog";
		$arrIcons["move"] = "fal fa-arrows";
		$arrIcons["delete"] = "fal fa-trash";
		$arrIcons["caret_right"] = "fal fa-caret-right";
		$arrIcons["caret_left"] = "fal fa-caret-left";
		$arrIcons["spinner"] = "fal fa-spinner";
		$arrIcons["edit"] = "fal fa-edit";
		$arrIcons["menu_closed"] = "fal fa-bars";
		
		
		$arrIcons = UniteFontManagerUC::fa_maybeConvertArrIconsTo4($arrIcons);
		
		$this->arrIconsInner = $arrIcons;
		
		return($arrIcons);
	}
	
	
	private function __________OUTER_________(){}
	
	
	/**
	 * get page builder outer options
	 */
	protected function getOuterOptions(){
		
		$arrOptions = array();
		
		$arrOptions["url_screenshot_template"] = $this->objLayouts->getUrlTakeScreenshot();
				
		$arrOptions["screenshot_on_save"] = $this->objLayoutType->putScreenshotOnGridSave;
		
		
		return($arrOptions);
	}
	
	
	/**
	 * get layout edit inner url
	 */
	protected function getUrlInnerLayoutEdit(){
		
		//get layout iframe url
		$urlParams = "";
		
		if(!empty($this->layoutID)){
			$this->layoutID = (int)$this->layoutID;
			$urlParams = "id=".$this->layoutID;
		}
		
		$urlLayoutEdit = HelperUC::getViewUrl(GlobalsUC::VIEW_LAYOUT_IFRAME, $urlParams, true);
		$urlLayoutEdit .= "&superclear=true";
		
		return($urlLayoutEdit);
	}
	
	
	/**
	 * init outer by layout
	 */
	public function initOuter($objLayout){
		
		$this->initCommon($objLayout);
		
		$extraParams = $objLayout->getExtraParams();
		
		$arrIcons = $this->getArrOuterIcons();
		
		$this->objActionsPanel = new UniteCreatorGridBuilderActionsPanel();
		$this->objActionsPanel->setExtraParams($extraParams);
		$this->objActionsPanel->setArrIcons($arrIcons);
		
		$this->urlViewLayoutEdit = $this->getUrlInnerLayoutEdit();
			
		//init top actions panel
		$this->objActionsPanel->initByLayout($this->objLayout);
		
		if($this->isLiveView)
			$this->objActionsPanel->setLiveView();
		
		require HelperUC::getPathViewObject("layouts_view.class");
		require HelperUC::getPathViewProvider("provider_layouts_view.class");
		$this->objLayoutsView = new UniteCreatorLayoutsViewProvider();
		
	}
	
	
	/**
	 * put edit script
	 */
	protected function putOuterScript(){
		?>
		
		<script>

			var g_objPageBuilder = null;
			
			jQuery(document).ready(function(){
				
				g_objPageBuilder = new UniteCreatorPageBuilder();
				g_objPageBuilder.init();				
			});
			
		</script>
		
		<?php 
	}
	
	
	/**
	 * put html
	 */
	protected function putOuterHtml(){
		
		$addHtml = "";
		
		//add layout id
		if(!empty($this->layoutID))
			$addHtml = "data-pageid=\"{$this->layoutID}\"";
		
		//add layout type
		if(!empty($this->layoutType)){
			if(!empty($addHtml))
				$addHtml .= " ";
				
			$addHtml .= "data-layouttype=\"{$this->layoutType}\"";
		}

		//--- add options
		
		$outerOptions = $this->getOuterOptions();
		$strOptions = UniteFunctionsUC::jsonEncodeForHtmlData($outerOptions);
		
		if(!empty($addHtml))
			$addHtml .= " ";
		
		$addHtml .= "data-options='$strOptions'";
		
		
		$iframeWrapperAddHtml = "";
		
		if($this->optionPanelHiddenAtStart == false){
		    
			$paddingLeft = $this->optionPanelInitWidth;
			$iframeWrapperAddHtml .= "style='padding-left:{$paddingLeft}px'";
		}
		
		$arrAddClasses = array();
		if(!empty($this->layoutType)){
			$arrAddClasses[] = "uc-layout-template";
			$arrAddClasses[] = "uc-layout-type-".$this->layoutType;
		}
		
		if(GlobalsUC::$inDev == true)
			$arrAddClasses[] = "uc-in-development";
		
		$addClass = "";
		if(!empty($arrAddClasses))
			$addClass = " ".implode(" ", $arrAddClasses);
					
		?>	
			<div id="uc_page_builder" class="uc-page-builder uc-view-desktop uc-state-saved uc-state-loading uc-builder-wrapper<?php echo $addClass?>" <?php echo $addHtml?>>
				
				<?php 
					UniteProviderFunctionsUC::putInitHelperHtmlEditor();
					
					$this->objActionsPanel->putPanelHtml();
					$this->putSidePanel();
					
					if($this->putFrame == true):
				?>
				
				<div class="uc-iframe-wrapper" <?php echo $iframeWrapperAddHtml?>>
					<iframe src="<?php echo $this->urlViewLayoutEdit?>" frameborder="0" class="uc-layout-iframe"></iframe>
				</div>
			</div>
			
		<?php 
					endif;
				
		$this->putHtmlStatuses();
		
		$this->objLayoutsView->putDialogImportLayout();
		
		if($this->isEditMode)
		  	UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_LAYOUT_EDIT_HTML);
				
		HelperHtmlUC::putAddonTypesBrowserDialogs(null, $this->objLayoutType);
		
	}
	
	/**
	 * put statuses html
	 */
	protected function putHtmlStatuses(){
		
		$iconSpinner = $this->getIconOuter("spinner");
		$iconTriangle = $this->getIconOuter("triangle");
		
		?>
		
		<div class="uc-layout-statuses">
	        
	        <div id="uc_layout_status_loader" class="uc-save-status" style="display:none">
	        	<i class="<?php echo $iconSpinner?> fa-spin fa-3x fa-fw"></i>
	        	<span><?php _e("Saving...", BLOXBUILDER_TEXTDOMAIN)?></span>
	        </div>
	        
	        <div id="uc_layout_status_success" class="uc-save-status" style="display:none"></div>
	        
	        <div id="uc_layout_status_error" class="uc-save-status uc-status-error" style="display:none">
		        <i class="<?php echo $iconTriangle?>" aria-hidden="true" style="font-color:red; margin-left: 0;"></i>
		        <span class="uc-layout-error-message"></span>
		        <a href="javascript:void(0)" class="uc-save-status-close" >X</a>
	        </div>
  		 
  		 </div>
		
		<?php 
	}
	
	
	/**
	 * display outer part of the page builder
	 */
	public function displayOuter(){
		
		$this->putOuterHtml();
		$this->putOuterScript();
		
	}
	
}