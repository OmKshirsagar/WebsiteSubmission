<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');


class UniteCreatorAddonView{
	
	protected $objAddon;
	protected $settingsItemOutput,$objAddonType, $addonType;
	protected $showToolbar = true, $showHeader = true;
	
	//show defenitions
	protected $putAllTabs = true, $arrTabsToPut = array();
	protected $isSVG = false, $showContstantVars = true, $showPreviewSettings = true;
	protected $showAddonDefaluts = true, $showTestAddon = true;
	protected $textSingle, $textPlural, $tabHtmlTitle = null;
	protected $htmlEditorMode = null, $arrCustomConstants = null;
	
	
	private function _________INIT___________(){}
	
	/**
	 * constructor
	 */
	public function __construct($isPutHtml = true){
		
		$this->init();
		
		$this->putHtml();
		
		if($isPutHtml == false)
			return(false);
		
	}
	
	/**
	 * validate init settings
	 */
	private function validateInitSettings(){
		
		if($this->putAllTabs == false && empty($this->arrTabsToPut))
			UniteFunctionsUC::throwError("if all tabs setting turned off should be some tabs in arrTabsToPut array");
			
	}
	
	
	/**
	 * get settings item output
	 */
	private function initSettingsItem(){
	    
		$options = $this->objAddon->getOptions();
		$paramsItems = $this->objAddon->getParamsItems();
		
		//items editor - settings
		$settingsItem = new UniteCreatorSettings();
		$settingsItem->addRadioBoolean("enable_items", __("Enable Items", BLOXBUILDER_TEXTDOMAIN), false);
	
		$settingsItem->setStoredValues($options);
	
		$this->settingsItemOutput = new UniteSettingsOutputInlineUC();
		$this->settingsItemOutput->init($settingsItem);
		$this->settingsItemOutput->setAddCss("[wrapperid] .unite_table_settings_wide th{width:100px;}");
	
	}
	
	/**
	 * init svg addon type
	 */
	private function initByAddonType_svg(){
		
		$this->putAllTabs = false;
		$this->arrTabsToPut["html"] = true;
		$this->isSVG = true;
	}
	
	/**
	 * init by addon type generally
	 */
	private function initByAddonType_general(){
		
		if($this->objAddonType->addonView_htmlTabOnly == true){
			$this->putAllTabs = false;
			$this->arrTabsToPut["html"] = true;
		}
		
		if($this->objAddonType->addonView_showConstantVars == false)
			$this->showContstantVars = false;
				
		if($this->objAddonType->addonView_showPreviewSettings == false)
			$this->showPreviewSettings = false;
		
		if($this->objAddonType->addonView_showAddonDefaults == false)
			$this->showAddonDefaluts = false;
		
		if($this->objAddonType->addonView_showTestAddon == false)
			$this->showTestAddon = false;
			
		if(!empty($this->objAddonType->addonView_tabHtmlTitle))
			$this->tabHtmlTitle = $this->objAddonType->addonView_tabHtmlTitle;
			
		if(!empty($this->objAddonType->addonView_htmlEditorMode))
			$this->htmlEditorMode = $this->objAddonType->addonView_htmlEditorMode;
		
		if(!empty($this->objAddonType->addonView_arrCustomConstants))
			$this->arrCustomConstants = $this->objAddonType->addonView_arrCustomConstants;
			
	}
	
	
	/**
	 * init by addon type
	 */
	private function initByAddonType(){
		
		$this->textSingle = $this->objAddonType->textSingle;
		$this->textPlural = $this->objAddonType->textPlural;
		
		if($this->objAddonType->isSVG){
			$this->initByAddonType_svg();
			return(false);
		}
		
		$this->initByAddonType_general();
	}
	
	
	
	/**
	 * init the view
	 */
	private function init(){
		
		$addonID = UniteFunctionsUC::getGetVar("id","",UniteFunctionsUC::SANITIZE_ID);
				
		if(empty($addonID))
			UniteFunctionsUC::throwError("Addon ID not given");
		
		$this->objAddon = new UniteCreatorAddon();
		$this->objAddon->initByID($addonID);
		
		$this->addonType = $this->objAddon->getType();
		$this->objAddonType = $this->objAddon->getObjAddonType();
		
		$this->initByAddonType();
		
		$this->initSettingsItem();
		
		$this->validateInitSettings();
	}
	
	private function _________PUT_HTML___________(){}
	
	
	/**
	 * get header title
	 */
	protected function getHeaderTitle(){
		
		$title = $this->objAddon->getTitle(true);
		$addonID = $this->objAddon->getID();
		
		$headerTitle = __("Edit Addon",BLOXBUILDER_TEXTDOMAIN);
		$headerTitle .= " - " . $title;
		
		return($headerTitle);
	}
	
	
	/**
	 * put top html
	 */
	private function putHtml_top(){
		
		$headerTitle = $this->getHeaderTitle();
		
		require HelperUC::getPathTemplate("header");
	}
	
	/**
	 * modify general settings by svg type
	 */
	private function modifyGeneralSettings_SVG($generalSettings){
				
		$generalSettings->hideSetting("show_small_icon");
		$generalSettings->hideSetting("text_preview");
		
		return($generalSettings);
	}
	
	/**
	 * modify general settings by svg type
	 */
	private function modifyGeneralSettings_general($generalSettings){

		//hide preview settings
		if($this->showPreviewSettings == false){
			$generalSettings->hideSetting("show_small_icon");
			$generalSettings->hideSetting("text_preview");
			$generalSettings->hideSetting("preview_size");
			$generalSettings->hideSetting("preview_bgcol");
		}
		
		
		return($generalSettings);
	}
	
	
	/**
	 * init general settings from file
	 */
	private function initGeneralSettings(){

		$filepathAddonSettings = GlobalsUC::$pathSettings."addon_fields.php";
		
		require $filepathAddonSettings;
		
		if($this->isSVG)
			$generalSettings = $this->modifyGeneralSettings_SVG($generalSettings);
		else
			$generalSettings = $this->modifyGeneralSettings_general($generalSettings);
		
			
		return($generalSettings);
	}
	
	
	/**
	 * put general settings tab html
	 */
	private function putHtml_generalSettings(){
		
		$addonID = $this->objAddon->getID();
		$title = $this->objAddon->getTitle(true);
		
		$name = $this->objAddon->getNameByType();
		
		$generalSettings = $this->initGeneralSettings();
		
		//set options from addon
		$arrOptions = $this->objAddon->getOptions();
		$generalSettings->setStoredValues($arrOptions);
		
		$settingsOutput = new UniteSettingsOutputWideUC();
		$settingsOutput->init($generalSettings);
		
		$addonTypeTitle = $this->objAddonType->textShowType;
		
		
		?>
		
		<div class="uc-edit-addon-col uc-col-first">
		
			<span id="addon_id" data-addonid="<?php echo $addonID?>" style="display:none"></span>
			
			<?php _e("Addon Title", BLOXBUILDER_TEXTDOMAIN); ?>:
			
			<div class="vert_sap5"></div>
			
			<input type="text" id="text_addon_title" value="<?php echo $title?>" class="unite-input-regular">
			
			<!-- NAME -->
			
			<div class="vert_sap15"></div>
			
			<?php _e("Addon Name", BLOXBUILDER_TEXTDOMAIN); ?>:
			
			<div class="vert_sap5"></div>
			
			<input type="text" id="text_addon_name" value="<?php echo $name?>" class="unite-input-regular">
			
			
			<!-- TYPE -->
			<div class="vert_sap15"></div>
			
			<?php _e("Addon Type", BLOXBUILDER_TEXTDOMAIN);?>: <b> <?php echo $addonTypeTitle?> </b>
			
			
			
			<?php UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_EDIT_ADDON_ADDSETTINGS, $arrOptions)?>
			
		</div>
		
		<div class="uc-edit-addon-col uc-col-second">
				<?php 
					$settingsOutput->draw("uc_general_settings", true); 
				?>
		</div>
		
		
		<div class="unite-clear"></div>
		
		<div class="vert_sap15"></div>
		
		
		<?php
		
	}
	
	/**
	 * if put tab
	 */
	private function isPutTab($tabName){
		
		if($this->putAllTabs == true)
			return(true);
		
		if(isset($this->arrTabsToPut[$tabName]))
			return(true);
		
		return(false);
	}
	
	
	/**
	 * put tabs html
	 */
	private function putHtml_tabs(){
		
		$isPut_general = true;		//always put general tab
		
		$isPut_html = $this->isPutTab("html");
		$isPut_attr = $this->isPutTab("attr");
		$isPut_itemattr = $this->isPutTab("itemattr");
		$isPut_css = $this->isPutTab("css");
		$isPut_js = $this->isPutTab("js");
		$isPut_includes = $this->isPutTab("includes");
		$isPut_assets = $this->isPutTab("assets");
		
		$htmlTabTitle = __("HTML",BLOXBUILDER_TEXTDOMAIN);
		if($this->isSVG == true)
			$htmlTabTitle = __("SVG Content",BLOXBUILDER_TEXTDOMAIN);
		else{
			
			if(!empty($this->tabHtmlTitle))
				$htmlTabTitle = $this->tabHtmlTitle;
			
		}
		
		?>
		
		<div id="uc_tabs" class="uc-tabs" data-inittab="uc_tablink_general">
			
			<?php if($isPut_general):?>
			<a id="uc_tablink_general" href="javascript:void(0)" data-contentid="uc_tab_general">
				<?php _e("General", BLOXBUILDER_TEXTDOMAIN)?> 
			</a>
			<?php endif?>
			
			<?php if($isPut_attr):?>
			<a id="uc_tablink_attr" href="javascript:void(0)" data-contentid="uc_tab_attr">
				<?php _e("Attributes", BLOXBUILDER_TEXTDOMAIN)?> 
			</a>
			<?php endif?>
			
			<?php if($isPut_itemattr):?>
			<a id="uc_tablink_itemattr" href="javascript:void(0)" data-contentid="uc_tab_itemattr">
				<?php _e("Item Attributes", BLOXBUILDER_TEXTDOMAIN)?> 
			</a>
			<?php endif?>
			
			<?php if($isPut_html):?>
			<a id="uc_tablink_html" href="javascript:void(0)" data-contentid="uc_tab_html">
				<?php echo $htmlTabTitle?>
			</a>
			<?php endif?>
			
			<?php if($isPut_css):?>
			<a id="uc_tablink_css" href="javascript:void(0)" data-contentid="uc_tab_css">
				<?php _e("CSS", BLOXBUILDER_TEXTDOMAIN)?>
			</a>
			<?php endif?>
			
			<?php if($isPut_js):?>
			<a id="uc_tablink_js" href="javascript:void(0)" data-contentid="uc_tab_js">
				<?php _e("Javascript", BLOXBUILDER_TEXTDOMAIN)?>
			</a>
			<?php endif?>
			
			<?php if($isPut_includes):?>
			<a id="uc_tablink_includes" href="javascript:void(0)" data-contentid="uc_tab_includes">
				<?php _e("js/css Includes", BLOXBUILDER_TEXTDOMAIN)?>
			</a>
			<?php endif?>
			
			<?php if($isPut_assets):?>
			<a id="uc_tablink_assets" href="javascript:void(0)" data-contentid="uc_tab_assets">
				<?php _e("Assets", BLOXBUILDER_TEXTDOMAIN)?>
			</a>
			<?php endif?>
			
		</div>
		
		<div class="unite-clear"></div>
		
		<?php 
	}
	
	
	/**
	 * put item for library include
	 */
	private function putIncludeLibraryItem($title, $name, $arrIncludes){
	
		$htmlChecked = "";
		if(in_array($name, $arrIncludes) == true)
			$htmlChecked = "checked='checked'";
	
		?>
		
			<li>
				<label for="check_include_<?php echo $name?>">
					<?php echo $title?>
				</label>
				
				<input type="checkbox" id="check_include_<?php echo $name?>" data-include="<?php echo $name?>" <?php echo $htmlChecked?>>
				
			</li>
		
		<?php 
	}

	
	/**
	 * put library includes
	 */
	private function putHtml_LibraryIncludes($arrJsLibIncludes){
		
		$objLibrary = new UniteCreatorLibrary();
		$arrLibrary = $objLibrary->getArrLibrary();
				
		foreach($arrLibrary as $item){
			$name = $item["name"];
			$title = $item["title"];
			
			$this->putIncludeLibraryItem($title, $name, $arrJsLibIncludes);
		}
		
			
	}
	
	/**
	 * put includes assets browser
	 */
	private function putHtml_Includes_assetsBrowser(){
		
		$objAssets = new UniteCreatorAssetsWork();
		$objAssets->initByKey("includes", $this->objAddon);
		$pathAssets = $this->objAddon->getPathAssetsFull();
		$objAssets->putHTML($pathAssets);
		
	}
	
	
	/**
	 * put includes html
	 */
	private function putHtml_Includes(){
		
		$arrJsLibIncludes = $this->objAddon->getJSLibIncludes();
		$arrJsIncludes = $this->objAddon->getJSIncludes();
		$arrCssIncludes = $this->objAddon->getCSSIncludes();
		
		$dataJs = UniteFunctionsUC::jsonEncodeForHtmlData($arrJsIncludes, "init");
		$dataCss = UniteFunctionsUC::jsonEncodeForHtmlData($arrCssIncludes, "init");
		
		
		?>
			<table id="uc_table_includes" class="unite_table_items">
				<thead>
					<tr>
						<th class="uc-table-includes-left">
							<b>
							<?php _e("Choose From Browser", BLOXBUILDER_TEXTDOMAIN)?>
							</b>
						</th>
						<th class="uc-table-includes-right">
							<b>
							<?php _e("JS / Css Includes", BLOXBUILDER_TEXTDOMAIN)?>
							</b>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td valign="top">
							<?php $this->putHtml_Includes_assetsBrowser(); ?>
						</td>
						<td valign="top">
							
							<ul id="uc-js-libraries" class="unite-list-hor">
								<li class="pright_10">
									<span class="unite-title2"><?php _e("Libraries", BLOXBUILDER_TEXTDOMAIN)?>:</span> </b>
								</li>
								<?php $this->putHtml_LibraryIncludes($arrJsLibIncludes)?>
							</ul>
							
							<div class="unite-clear"></div>
							
							<div id="uc_includes_wrapper">
								
								<div class="unite-title2">Js Includes:</div>
								
								<ul id="uc-js-includes" class="uc-js-includes" data-type="js" <?php echo $dataJs?>></ul>
								
								<div class="unite-title2">Css Includes:</div>
								
								<ul id="uc-css-includes" class="uc-css-includes" data-type="css" <?php echo $dataCss?>></ul>
							
							</div>
							
						</td>
					</tr>
				</tbody>
			</table>
			
			<div id="uc_dialog_unclude_settings" title="<?php _e("Include Settings")?>" class="unite-inputs" style="display:none">
				<div class="unite-dialog-inside">
				
					<?php _e("Include When:", BLOXBUILDER_TEXTDOMAIN)?>
					
					<span class="hor_sap"></span>
					
					<select id="uc_dialog_include_attr"></select>
					
					<span id="uc_dialog_include_value_container" style="display:none">
					
						<span class="hor_sap5"></span>
						
						<?php _e("equals", BLOXBUILDER_TEXTDOMAIN)?>
						
						<span class="hor_sap5"></span>
						
						<select id="uc_dialog_include_values"></select>
						
					</span>
					
					<?php HelperHtmlUC::putDialogControlFieldsNotice() ?>
				</div>
			</div>
			
						
			<?php 
			
	}
	
	
	/**
	 * put assets tab html
	 */
	private function putHtml_assetsTab(){
		
		$path = $this->objAddon->getPathAssets();
		$pathAbsolute = $this->objAddon->getPathAssetsFull();
		
		$textNotSet = __("[not set]", BLOXBUILDER_TEXTDOMAIN);
		
		$unsetAddHtml = "style='display:none'";
		$htmlPath = $textNotSet;
		$dataPath = "";
		if(!empty($path)){
			$unsetAddHtml = "";
			$htmlPath = htmlspecialchars($path);
			$dataPath = $htmlPath;
		}
		
		?>
			<div class="uc-assets-folder-wrapper">
				<span class="uc-assets-folder-label"><?php _e("Addon Assets Path: ", BLOXBUILDER_TEXTDOMAIN)?></span>
				<span id="uc_assets_path" class="uc-assets-folder-folder" data-path="<?php echo $dataPath?>" data-textnotset="<?php echo $textNotSet?>"><?php echo $htmlPath?></span>
				<a id="uc_button_set_assets_folder" href="javascript:void(0)" class="unite-button-secondary"><?php _e("Set", BLOXBUILDER_TEXTDOMAIN)?></a>
				<a id="uc_button_set_assets_unset" href="javascript:void(0)" class="unite-button-secondary" <?php echo $unsetAddHtml?>><?php _e("Unset", BLOXBUILDER_TEXTDOMAIN)?></a>
			</div>
		<?php 
		
		$objAssets = new UniteCreatorAssetsWork();
		$objAssets->initByKey("assets_manager");
		
		$objAssets->putHTML($pathAbsolute);
	}
	
	/**
	 * put expand link
	 */
	private function putLinkExpand(){
		?>
			<a class="uc-tabcontent-link-expand" href="javascript:void(0)"><?php _e("expand", BLOXBUILDER_TEXTDOMAIN);?></a>
		<?php 
	}
	
	
	/**
	 * put html tab content
	 */
	private function putHtml_tabTableRow($textareaID, $title, $areaHtml, $paramsPanelID, $addVariableID = null, $isItemsRelated = false, $params = array()){
		
		$rowClass = "";
		$rowAddHtml = "";
				
		$paramsPanelClassAdd = " uc-params-panel-main";
		
		if($isItemsRelated == true){
			$rowClass = "uc-items-related";
			$hasItems = $this->objAddon->isHasItems();
			
			if($hasItems == false)
				$rowAddHtml = "style='display:none'";
			
			$paramsPanelClassAdd = "";
			
		}
		
		$isExpanded = UniteFunctionsUC::getVal($params, "expanded");
		$isExpanded = UniteFunctionsUC::strToBool($isExpanded);
		
		$mode = UniteFunctionsUC::getVal($params, "mode");
		
		$areaAddParams = "";
		if(!empty($mode))
			$areaAddParams = " data-mode='{$mode}'";
		
		
		if($isExpanded == true)
			$rowClass .= " uc-row-expanded";
		
		if(!empty($rowClass))
			$rowClass = "class='$rowClass'";
		
		$styleRight = "";
		if($this->isSVG == true)
			$styleRight = 'style="display:none;"';
		
		?>
					<tr <?php echo $rowClass?> <?php echo $rowAddHtml?>>
						<td class="uc-tabcontent-cell-left">
						
							<div class="uc-editor-title"><?php echo $title?></div>
							<textarea id="<?php echo $textareaID?>" class="area_addon <?php echo $textareaID?>" <?php echo $areaAddParams?>><?php echo $areaHtml?></textarea>
							<?php if($isExpanded == false)
									$this->putLinkExpand()?>
						</td>
						<td class="uc-tabcontent-cell-right" <?php echo $styleRight?>>

							<?php if($isItemsRelated == true):?>
								<div class="uc-params-panel-filters">
									<a href="javascript:void(0)" class="uc-filter-active" data-filter="item" onfocus="this.blur()"><?php _e("Item", BLOXBUILDER_TEXTDOMAIN)?></a>
									<a href="javascript:void(0)" data-filter="main" onfocus="this.blur()"><?php _e("Main", BLOXBUILDER_TEXTDOMAIN)?></a>
								</div>
							<?php endif?>
						
							<div id="<?php echo $paramsPanelID?>" class="uc-params-panel<?php echo $paramsPanelClassAdd?>"></div>
							
							<?php if(!empty($addVariableID)):?>
						    <a id="<?php echo $addVariableID?>" type="button" href="javascript:void(0)" class="unite-button-secondary mleft_20"><?php _e("Add Variable", BLOXBUILDER_TEXTDOMAIN)?></a>
							<?php endif?>
							
						</td>
					</tr>
		
		<?php 
	}
	
	
	/**
	 * put tab table sap
	 */
	private function putHtml_tabTableSap($isItemsRelated = false){
		
		$rowClass = "";
		if($isItemsRelated == true)
			$rowClass = "class='uc-items-related'";
		
		?>
			<tr <?php echo $rowClass?>>
				<td colspan="2"><div class="vert_sap10"></div></td>
			</tr>
		<?php 
	}
	
	
	/**
	 * put overwiew tab html
	 */
	private function putHtml_overviewTab(){
		
		$title = $this->objAddon->getTitle();
		$name = $this->objAddon->getName();
		$description = $this->objAddon->getDescription();
		$link = $this->objAddon->getOption("link_resource");
		if(!empty($link))
			$link = HelperHtmlUC::getHtmlLink($link, $link, "uc_overview_link","",true);
		
		$addonIcon = $this->objAddon->getUrlIcon();
		
		
		?>
		<div class="uc-tab-overview">
			<div class="uc-section-inline"><?php _e("Addon Title", BLOXBUILDER_TEXTDOMAIN)?>: <span id="uc_overview_title" class="unite-bold"><?php echo $title?></span></div>
			<div class="uc-section-inline"><?php _e("Addon Name", BLOXBUILDER_TEXTDOMAIN)?>: <span id="uc_overview_name" class="unite-bold"><?php echo $name?></span></div>
			<div class="uc-section">
				<div class="uc-section-title"><?php _e("Addon Description", BLOXBUILDER_TEXTDOMAIN)?>:</div>
				<div id="uc_overview_description" class="uc-section-content uc-desc-wrapper">
					<?php echo $description?>
				</div>
				<div class="unite-clear"></div>
			</div>
			<div class="uc-section-inline"><?php _e("Link to resource", BLOXBUILDER_TEXTDOMAIN)?>: <?php echo $link?></div>
			<div class="uc-section">
				<div class="uc-section-title uc-title-icon"><?php _e("Addon Icon", BLOXBUILDER_TEXTDOMAIN)?>:</div>
				<div id="uc_overview_icon" class="uc-section-content uc-addon-icon-small" style="background-image:url('<?php echo $addonIcon?>')"></div> 
			</div>
			
		</div>
		
		
		<?php
	}
	
	
	/**
	 * put tabs content
	 */
	private function putHtml_content(){
		
		$css = $this->objAddon->getCss(true);
		$cssItem = $this->objAddon->getCssItem(true);
		
		$html = $this->objAddon->getHtml(true);
		$htmlItem = $this->objAddon->getHtmlItem(true);
		$htmlItem2 = $this->objAddon->getHtmlItem2(true);
		
		$js = $this->objAddon->getJs(true);
		$hasItems = $this->objAddon->isHasItems();
		
		$params = $this->objAddon->getParams();
		$paramsItems = $this->objAddon->getParamsItems();
		
		$paramsEditorItems = new UniteCreatorParamsEditor();
		
		if($hasItems == false)
			$paramsEditorItems->setHiddenAtStart();
		
		$paramsEditorItems->init("items");
		
		?>
		
		<div id="uc_tab_contents" class="uc-tabs-content-wrapper uc-addon-props">
			
			<!-- General -->
			
			<div id="uc_tab_general" class="uc-tab-content" style="display:none">
				
				<?php 
				try{
					
					$this->putHtml_generalSettings();
					
				}catch(Exception $e){
					HelperHtmlUC::outputException($e);
				}
				?>
					
			</div>
			
			<!-- Attributes -->
			
			<div id="uc_tab_attr" class="uc-tab-content" style="display:none">
					
				<?php 
					$paramsEditorMain = new UniteCreatorParamsEditor();
					$paramsEditorMain->init("main");
					$paramsEditorMain->outputHtmlTable();
				?>
				
			</div>
			
			<!-- Item Attributes -->
			
			<div id="uc_tab_itemattr" class="uc-tab-content uc-tab-itemattr" style="display:none">
			
				<?php 
					$this->settingsItemOutput->draw("uc_form_edit_addon");
					$paramsEditorItems->outputHtmlTable();
				?>
			
			</div>
			
			
			<!-- HTML -->
		
			<div id="uc_tab_html" class="uc-tab-content" style="display:none">
						
				<table class="uc-tabcontent-table">
					
					<?php 
						
						//------------- put html row
					
						$textareaID = "area_addon_html";
						$rowTitle = __("Addon HTML",BLOXBUILDER_TEXTDOMAIN);
						
						if($this->isSVG == true)
							$rowTitle = __("SVG Content",BLOXBUILDER_TEXTDOMAIN);
						
						if(!empty($this->tabHtmlTitle))
							$rowTitle = $this->tabHtmlTitle.__(" Content",BLOXBUILDER_TEXTDOMAIN);
						
							
						$areaHtml = $html;
						$paramsPanelID = "uc_params_panel_main";
						$addVariableID = "uc_params_panel_main_addvar";
						
						$params = array();
						if(!empty($this->htmlEditorMode))
							$params["mode"] = $this->htmlEditorMode;
						
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, $addVariableID, false, $params);
						
						
						//------------- put html item row
						
						$this->putHtml_tabTableSap(true);
						
						$textareaID = "area_addon_html_item";
						$rowTitle = __("Addon Item HTML",BLOXBUILDER_TEXTDOMAIN);
						$areaHtml = $htmlItem;
						$paramsPanelID = "uc_params_panel_item";
						$addVariableID = "uc_params_panel_item_addvar";
						$isItemsRelated = true;
												
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, $addVariableID, $isItemsRelated);

						$this->putHtml_tabTableSap(true);
						
						//------------- put html item row 2
						
						$textareaID = "area_addon_html_item2";
						$rowTitle = __("Addon Item HTML 2",BLOXBUILDER_TEXTDOMAIN);
						$areaHtml = $htmlItem2;
						$paramsPanelID = "uc_params_panel_item2";
						$addVariableID = "uc_params_panel_item_addvar2";
						$isItemsRelated = true;
						
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, $addVariableID, $isItemsRelated);
						
					?>				
					
				</table>
				
			</div>
			
			<!-- CSS -->
			
			<div id="uc_tab_css" class="uc-tab-content" style="display:none">
			
				<table class="uc-tabcontent-table">
				
					<?php 
						//--------- css addon --------
					
						$textareaID = "area_addon_css";
						$rowTitle = __("Addon CSS",BLOXBUILDER_TEXTDOMAIN);
						$areaHtml = $css;
						$paramsPanelID = "uc_params_panel_css";
						
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID);
						
						//--------- css item --------
						
						$textareaID = "area_addon_css_item";
						$rowTitle = __("Addon Item CSS",BLOXBUILDER_TEXTDOMAIN);
						$areaHtml = $cssItem;
						$paramsPanelID = "uc_params_panel_css_item";
						
						$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, null, true);
						
					?>
					
					
				</table>
			
			</div>
			
			<!-- JS -->
			
			<div id="uc_tab_js" class="uc-tab-content" style="display:none">
				
				<table class="uc-tabcontent-table">
					<?php 
					$textareaID = "area_addon_js";
					$rowTitle = __("Addon Javascript",BLOXBUILDER_TEXTDOMAIN);
					$areaHtml = $js;
					$paramsPanelID = "uc_params_panel_js";
					$params = array();
					$params["expanded"] = true;
					
					$this->putHtml_tabTableRow($textareaID, $rowTitle, $areaHtml, $paramsPanelID, null, false, $params);
					
					?>
				
				</table>
				
			</div>
			
			<!-- INCLUDES -->
			<div id="uc_tab_includes" class="uc-tab-content" style="display:none">
				
				<?php $this->putHtml_Includes()?>
				
			</div>
	
			<div id="uc_tab_assets" class="uc-tab-content" style="display:none">
				
				<?php $this->putHtml_assetsTab() ?>
				
			</div>
			
		</div>
		
		<!-- END TABS -->
		
		
		<?php 
	}

	
	/**
	 * put action buttons html
	 */
	private function putHtml_actionButtons(){
		
		$addonID = $this->objAddon->getID();
		$addonType = $this->objAddon->getType();
		
		$urlTestAddon = HelperUC::getViewUrl_TestAddon($addonID);
		
		$urlPreviewAddon = HelperUC::getViewUrl_TestAddon($addonID,"preview=1");
		
		$urlAddonDefaults = helperuc::getViewUrl_AddonDefaults($addonID);
		
		$textPreviewAddon = __("Preview ",BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
		$textTestAddon = __("Test ",BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
		$textBack = __("Back To ",BLOXBUILDER_TEXTDOMAIN).$this->textPlural.__(" List",BLOXBUILDER_TEXTDOMAIN);
		
		$textExport = __("Export ", BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
		
		$isExistsInCatalog = $this->objAddon->isExistsInCatalog();
		
		?>
		
		<div class="uc-edit-addon-buttons-panel-wrapper">
		
			<div id="uc_buttons_panel" class="uc-edit-addon-buttons-panel">
			
				<div class="unite-float-left">
				
					<div class="uc-button-action-wrapper">
						<a id="button_update_addon" class="button_update_addon unite-button-primary" href="javascript:void(0)"><?php _e("Update", BLOXBUILDER_TEXTDOMAIN);?></a>
						
						<div style="padding-top:6px;">
							
							<span id="uc_loader_update" class="loader_text" style="display:none"><?php _e("Updating...", BLOXBUILDER_TEXTDOMAIN)?></span>
							<span id="uc_message_addon_updated" class="unite-color-green" style="display:none"></span>
							
						</div>
					</div>
					
					<a class="unite-button-secondary" href="<?php echo HelperUC::getViewUrl_Addons($addonType)?>"><?php echo $textBack?></a>
										
					<?php if($this->showAddonDefaluts == true):?>
					<a href="<?php echo $urlAddonDefaults?>" class="unite-button-secondary"><?php _e("Addon Defaults", BLOXBUILDER_TEXTDOMAIN) ?></a>
					<?php endif?>
					
					<?php if($this->showTestAddon == true):?>
					<a href="<?php echo $urlTestAddon?>" class="unite-button-secondary " ><?php echo $textTestAddon?></a>
					
					<a href="<?php echo $urlPreviewAddon?>" class="unite-button-secondary " ><?php echo $textPreviewAddon?></a>
					<?php endif?>

					<?php if($isExistsInCatalog == true): ?>
					
						<a id="uc_button_update_catalog" class="button_update_addon unite-button-secondary" href="javascript:void(0)"><?php _e("Update From Catalog", BLOXBUILDER_TEXTDOMAIN);?></a>
						<span id="uc_loader_update_catalog" class="loader_text" style="display:none"><?php __("Updating...", BLOXBUILDER_TEXTDOMAIN); ?></span>
						<span id="uc_message_addon_updated_catalog" class="unite-color-green" style="display:none"></span>
					
					<?php endif?>
					
				</div>
				
				<div class="unite-float-right mright_10">
					<a id="button_export_addon" href="javascript:void(0)" class="unite-button-secondary " ><?php echo $textExport?></a>
				</div>
				
				
				<div class="unite-clear"></div>
							
			</div>
		</div>
		<?php 
	}
	
	private function __________PARAMS___________(){}
	
	
	/**
	 * create child param
	 */
	protected function createChildParam($param, $type = null, $addParams = false){
		
		$arr = array("name"=>$param, "type"=>$type);
		
		switch($type){
			case UniteCreatorDialogParam::PARAM_IMAGE:
				$arr["add_thumb"] = true;
				$arr["add_thumb_large"] = true;
			break;
		}
		
		if(!empty($addParams))
			$arr = array_merge($arr, $addParams);
		
		return($arr);
	}
	
	
	/**
	 * get post child params
	 */
	protected function getChildParams_post(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("id");
		$arrParams[] = $this->createChildParam("title",UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("alias");
		$arrParams[] = $this->createChildParam("content", UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("intro", UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("link");
		$arrParams[] = $this->createChildParam("image", UniteCreatorDialogParam::PARAM_IMAGE);
		$arrParams[] = $this->createChildParam("date",null,array("raw_insert_text"=>"{{[param_name]|date(\"d F Y, H:i\")}}"));
		$arrParams[] = $this->createChildParam("postdate",null,array("raw_insert_text"=>"{{putPostDate([param_prefix].id,\"d F Y, H:i\")}}"));
		$arrParams[] = $this->createChildParam("tagslist",null,array("raw_insert_text"=>"{{putPostTags([param_prefix].id)}}"));
		$arrParams[] = $this->createChildParam("metafield",null,array("raw_insert_text"=>"{{putPostMeta([param_prefix].id,\"yourmetakey\")}}"));
		
		$isAcfExists = class_exists('acf');
		if($isAcfExists == true)
			$arrParams[] = $this->createChildParam("acf_field",null,array("raw_insert_text"=>"{{putAcfField([param_prefix].id,\"yourfield\")}}"));
		
		
		return($arrParams);
	}
	
		
	/**
	 * get post child params
	 */
	protected function getChildParams_instagramItem(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("caption",UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("thumb");
		$arrParams[] = $this->createChildParam("image");
		$arrParams[] = $this->createChildParam("link");
		$arrParams[] = $this->createChildParam("num_likes");
		$arrParams[] = $this->createChildParam("num_comments");
		$arrParams[] = $this->createChildParam("time_passed");
		$arrParams[] = $this->createChildParam("link");
		$arrParams[] = $this->createChildParam("isvideo");
		$arrParams[] = $this->createChildParam("num_video_views");
		
		return($arrParams);
	}
	
	
	/**
	 * get post child params
	 */
	protected function getAddParams_form(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("start",UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("end",UniteCreatorDialogParam::PARAM_EDITOR);
		
		return($arrParams);
	}
	
	
	/**
	 * add param for form item
	 */
	protected function getAddParams_formItem(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("form_field",UniteCreatorDialogParam::PARAM_EDITOR);
		
		return($arrParams);
	}
	
	/**
	 * get post child params
	 */
	protected function getChildParams_instagramMain(){
		
		$arrParams = array();
		$arrParams[] = $this->createChildParam("name", UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("username");
		$arrParams[] = $this->createChildParam("biography", UniteCreatorDialogParam::PARAM_EDITOR);
		$arrParams[] = $this->createChildParam("image_profile");
		$arrParams[] = $this->createChildParam("num_followers");
		$arrParams[] = $this->createChildParam("num_following");
		$arrParams[] = $this->createChildParam("num_posts");
		$arrParams[] = $this->createChildParam("url_external");
		$arrParams[] = $this->createChildParam("link");
		$arrParams[] = $this->createChildParam("no_items_code",null,array("child_param_name"=>"hasitems"));
		
		return($arrParams);
	}
	
	/**
	 * get dataset param
	 */
	protected function getAddParams_dataset($paramDataset){
		
		$datasetType = UniteFunctionsUC::getVal($paramDataset, "dataset_type");
		$datasetQuery = UniteFunctionsUC::getVal($paramDataset, "dataset_{$datasetType}_query");
				
		$arrItemHeaders = array();
		$arrItemHeaders = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_GET_DATASET_HEADERS, $arrItemHeaders, $datasetType, $datasetQuery);
		
		$arrChildKeys = array();
		
		foreach($arrItemHeaders as $key){
			$arrChildKeys[] = $this->createChildParam($key);
		}
		
		
		return($arrChildKeys);
	}
	
	
	/**
	 * get params child keys
	 */
	protected function getParamChildKeys(){
		
		$arrChildKeys = array();
		$arrChildKeys[UniteCreatorDialogParam::PARAM_POST] = $this->getChildParams_post();
		$arrChildKeys[UniteCreatorDialogParam::PARAM_INSTAGRAM] = $this->getChildParams_instagramMain();
		$arrChildKeys["uc_instagram_item"] = $this->getChildParams_instagramItem();
		
		
		//add dataset params
		$paramDataset = $this->objAddon->getParamByType(UniteCreatorDialogParam::PARAM_DATASET);
		if(!empty($paramDataset))
			$arrChildKeys[UniteCreatorDialogParam::PARAM_DATASET] = $this->getAddParams_dataset($paramDataset);
		
		
		return($arrChildKeys);
	}

	
	
	/**
	 * get additional param keys by type
	 */
	protected function getAddParamKeys(){
		
		$arrAddKeys = array();
		$arrAddKeys[UniteCreatorDialogParam::PARAM_FORM] = $this->getAddParams_form();
		$arrAddKeys["uc_form_item"] = $this->getAddParams_formItem();
		
		
		return($arrAddKeys);
	}
	
	
	/**
	 * get code replacements for params panel
	 */
	protected function getParamTemplateCodes(){
		
		$codeNoItems = "{% if [param_name] == false %}\n\n";
		$codeNoItems .= "	No items text\n\n";
		$codeNoItems .= "{% else %}\n\n";
		$codeNoItems .= "	main output\n\n";
		$codeNoItems .= "{% endif %}";
		
		$arrCode = array();
		$arrCode["no_items_code"] = $codeNoItems;
		
		return($arrCode);
	}
	
	
	private function _________OTHERS___________(){}
	
	/**
	 * get thumb sizes - function for override
	 */
	protected function getThumbSizes(){
		return(null);
	}
	
	
	
	
	/**
	 * put config
	 */
	private function putConfig(){
		
		$options = array();
		$options["url_preview"] = $this->objAddon->getUrlPreview();
		$options["thumb_sizes"] = $this->getThumbSizes();
		$options["items_type"] = $this->objAddon->getItemsType();
		
		$dataOptions = UniteFunctionsUC::jsonEncodeForHtmlData($options, "options");
		
		$params = $this->objAddon->getParams();
		$dataParams = UniteFunctionsUC::jsonEncodeForHtmlData($params, "params");
		
		$paramsItems = $this->objAddon->getParamsItems();
		$dataParamsItems = UniteFunctionsUC::jsonEncodeForHtmlData($paramsItems, "params-items");
		
		$variablesItems = $this->objAddon->getVariablesItem();
		$variablesMain = $this->objAddon->getVariablesMain();
		
		$dataVarItems = UniteFunctionsUC::jsonEncodeForHtmlData($variablesItems, "variables-items");
		$dataVarMain = UniteFunctionsUC::jsonEncodeForHtmlData($variablesMain, "variables-main");
		
		$objOutput = new UniteCreatorOutput();
		$objOutput->setProcessType(UniteCreatorParamsProcessor::PROCESS_TYPE_CONFIG);
		
		$objOutput->initByAddon($this->objAddon);
		
		$arrConstantData = $objOutput->getConstantDataKeys(true);
				
		if($this->showContstantVars == false)
			$arrConstantData = array();
		
		if(!empty($this->arrCustomConstants))
			$arrConstantData += $this->arrCustomConstants;

		
		/*
		$arrConstantData[] = array(
			"name" => "uc_parent",
			"is_parent"=>true,
			"child_params"=>array("child1","child2","child3")
		);
		*/
		
		//dmp($arrConstantData);exit();
			
		$dataPanelKeys = UniteFunctionsUC::jsonEncodeForHtmlData($arrConstantData, "panel-keys");
		
		$arrItemConstantData = $objOutput->getItemConstantDataKeys();
		$dataItemPanelKeys = UniteFunctionsUC::jsonEncodeForHtmlData($arrItemConstantData, "panel-item-keys");
		
		//child keys of some fields
		$arrPanelChildKeys = $this->getParamChildKeys();
		$dataPanelChildKeys = UniteFunctionsUC::jsonEncodeForHtmlData($arrPanelChildKeys, "panel-child-keys");
		
		$arrPanelAddKeys = $this->getAddParamKeys();
		$dataPanelAddKeys = UniteFunctionsUC::jsonEncodeForHtmlData($arrPanelAddKeys, "panel-add-keys");
		
		$arrPanelTemplateCode = $this->getParamTemplateCodes();
		$dataPanelCode = UniteFunctionsUC::jsonEncodeForHtmlData($arrPanelTemplateCode, "panel-template-code");
		
		
		?>
		
		<div id="uc_edit_item_config" style="display:none"
			<?php echo $dataParams?>
			<?php echo $dataParamsItems?>
			<?php echo $dataPanelKeys?>
			<?php echo $dataPanelAddKeys?>
			<?php echo $dataItemPanelKeys?>
			<?php echo $dataVarItems?>
			<?php echo $dataVarMain?>
			<?php echo $dataOptions?>
			<?php echo $dataPanelChildKeys?>
			<?php echo $dataPanelCode?>
		></div>
		
		<?php 
	}
	
	
	/**
	 * put js
	 */
	private function putJs(){
		?>
		
		<script type="text/javascript">
		
		jQuery(document).ready(function(){
			var objAdmin = new UniteCreatorAdmin();
			objAdmin.initEditAddonView();
		});
		
		</script>
		
		<?php 
	}
	
	
	/**
	 * bulk dialog
	 */
	private function putBulkDialog(){
		?>
		<div id="uc_dialog_bulk" title="<?php _e("Bulk Operations", BLOXBUILDER_TEXTDOMAIN)?>" class="unite-inputs" style="display:none">
			
			bulk operations dialog
			
		</div>
		<?php 
	}
	
	
	/**
	 * get contents of bulk dialog from ajax
	 */
	public function getBulkDialogContents($data){
		
		$addonID = UniteFunctionsUC::getVal($data, "addon_id");
		UniteFunctionsUC::validateNotEmpty($addonID,"addon id");
		
		$paramType = UniteFunctionsUC::getVal($data, "param_type");
		
		$paramData = UniteFunctionsUC::getVal($data, "param_data");
		
		$paramTitle = UniteFunctionsUC::getVal($paramData, "title"); 
		$paramName = UniteFunctionsUC::getVal($paramData, "name"); 
		
		
		//get data
		$addon = new UniteCreatorAddon();
		$addon->initByID($addonID);
		$addonType = $addon->getType();
		
		$catID = $addon->getCatID();
		UniteFunctionsUC::validateNotEmpty($catID);
		
		$addons = new UniteCreatorAddons();
		$arrAddons = $addons->getCatAddons($catID, false, null, $addonType);
		
		//make html
		
		ob_start();
		
		$addonTitle = $addon->getTitle();
		
		?>
		<br>
		
		<?php echo $paramType ?> param: <b> <?php echo $paramTitle?> ( <?php echo $paramName?> ) </b>
		<span class="hor_sap40"></span>
		Addon: <b> <?php echo $addonTitle?> </b>
		
		<br><br>
		
		<div class="unite-dialog-inner-constant">
		
		<div class="uc-dialog-loader loader_text" style="display:none"><?php _e("Updating Addons", BLOXBUILDER_TEXTDOMAIN)?>...</div>
		
		<table class="unite_table_items">
		
			<tr>
				<th class="">
					<input type='checkbox' title="<?php _e("Select All Addons", BLOXBUILDER_TEXTDOMAIN)?>" class="uc-check-all">
				</th>
				<th><b><?php _e("Addon Title", BLOXBUILDER_TEXTDOMAIN)?></b></th>
				<th><b><?php _e("Status", BLOXBUILDER_TEXTDOMAIN)?></b></th>
			</tr>
		
		<?php 
		
		$numSelected = 0;
		
		foreach($arrAddons as $index=>$catAddon){
			$title = $catAddon->getTitle();
			$catAddonID = $catAddon->getID();
			if($catAddonID == $addonID)
				continue;
				
			$rowClass = $index%2?"unite-row1":"unite-row2";
			
			$isMain = ($paramType == "main");
			$isExists = $catAddon->isParamExists($paramName, $isMain);
			
			$status = "<span class='unite-color-red'>not exists</span>";
			if($isExists)
				$status = "<span class='unite-color-green'>exists</span>";
			
			$checked = "";
			if($isExists == false){
				$checked = " checked";
				$numSelected++;
				$rowClass .= " unite-row-selected";
			}
			
			?>
			<tr class="<?php echo $rowClass?>">
				<td>
					<input type='checkbox' data-id="<?php echo $catAddonID?>" <?php echo $checked?> class="uc-check-select">
				</td>
				<td><?php echo $title?></td>
				<td><?php echo $status?></td>
			</tr>
			<?php 
		}
				
		?>
		</table>
		</div>
		
		<br>

		<span class='uc-section-selected'>
			<span id='uc_bulk_dialog_num_selected'><?php echo $numSelected?></span> <?php _e("selected")?>
		</span>
		
		<span class="hor_sap"></span>
		
		<a href="javascript:void(0)" data-action="update" class="uc-action-button unite-button-primary"><?php _e("Add / Update in Addons", BLOXBUILDER_TEXTDOMAIN)?></a>
		
		<span class="hor_sap40"></span>
		
		<a href="javascript:void(0)" data-action="delete" class="uc-action-button unite-button-secondary"><?php _e("Delete From Addons", BLOXBUILDER_TEXTDOMAIN)?></a>
		
		
		<?php 
		
		$html = ob_get_contents();
		ob_end_clean();
		
		
		$response = array();
		$response["html"] = $html;
		
		return($response);
	}
	
	/**
	 * put params and variables dialog
	 */
	private function putDialogs(){
		
		//dialog param		
		$objDialogParam = new UniteCreatorDialogParam();
		$objDialogParam->init(UniteCreatorDialogParam::TYPE_MAIN, $this->objAddon);
		$objDialogParam->outputHtml();
		
		//dialog variable item
		
		$objDialogVariableItem = new UniteCreatorDialogParam();
		$objDialogVariableItem->init(UniteCreatorDialogParam::TYPE_ITEM_VARIABLE, $this->objAddon);
		$objDialogVariableItem->outputHtml();
		
		//dialog variable main
		$objDialogVariableMain = new UniteCreatorDialogParam();
		$objDialogVariableMain->init(UniteCreatorDialogParam::TYPE_MAIN_VARIABLE, $this->objAddon);
		$objDialogVariableMain->outputHtml();
		
		$this->putBulkDialog();
	}
	
	
	/**
	 * put some html that will appear before tabs
	 */
	private function putHtml_beforeTabs(){
		?>
				<div id="uc_update_addon_error" class="unite_error_message" style="display:none"></div>
		<?php 
	}
	
	
	/**
	 * put html
	 */
	private function putHtml(){
		
		if($this->showHeader == true)
			$this->putHtml_top();
		else
			require HelperUC::getPathTemplate("header_missing");
		?>
		<div class="content_wrapper unite-content-wrapper">
		<?php 
		if($this->showToolbar == true)
			$this->putHtml_actionButtons();
		
		$this->putHtml_beforeTabs();
			
		$this->putHtml_tabs();
		$this->putHtml_content();
		
		$this->putConfig();
		$this->putJs();
		
		$this->putDialogs();
		
		?>
		</div>
		<?php 
	}
	
	
}

