<?php

/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorManagerAddonsWork extends UniteCreatorManager{
	
	const STATE_FILTER_CATALOG = "manager_filter_catalog";
	const STATE_FILTER_ACTIVE = "fitler_active_addons";
	const STATE_LAST_ADDONS_CATEGORY = "last_addons_cat";
	
	const FILTER_CATALOG_MIXED = "mixed";
	const FILTER_CATALOG_INSTALLED = "installed";
	const FILTER_CATALOG_WEB = "web";
	
	protected $numLocalCats = 0;
	private $filterAddonType = null;
	protected $objAddonType = null, $isLayouts = false, $enableActiveFilter = true, $enableEnterName = true;
	protected $enablePreview = false, $enableViewThumbnail = false, $enableMakeScreenshots = false;
	
	protected $textAddAddon, $textSingle, $textPlural, $textSingleLower, $textPluralLower;
	
	private $filterActive = "";
	private $showAddonTooltip = false, $showTestAddon = true;
	
	protected $filterCatalogState;
	protected $defaultFilterCatalog;
	protected $objBrowser;
	protected $urlBuy;
	
	
	/**
	 * construct the manager
	 */
	public function __construct(){
		
	}
	
	
	/**
	 * set filter active state
	 */
	public static function setStateFilterCatalog($filterCatalog){
		
		if(!empty($filterCatalog))
			HelperUC::setState(self::STATE_FILTER_CATALOG, $filterCatalog);
	}
	
	/**
	 * get filter active statge
	 */
	protected function getStateFilterCatalog(){
		
		if(GlobalsUC::$enableWebCatalog == false)
			return(self::FILTER_CATALOG_INSTALLED);
		
		if($this->objAddonType->allowWebCatalog == false)
			return(self::FILTER_CATALOG_INSTALLED);
		
		$filterCatalog = HelperUC::getState(self::STATE_FILTER_CATALOG);
		if(empty($filterCatalog))
			$filterCatalog = $this->defaultFilterCatalog;
				
		return($filterCatalog);
	}
	
	
	/**
	 * set filter active state
	 */
	public static function setStateFilterActive($filterActive){
		
		if(!empty($filterActive))
			HelperUC::setState(UniteCreatorManagerAddons::STATE_FILTER_ACTIVE, $filterActive);
		
	}
	
	/**
	 * get filter active statge
	 */
	public static function getStateFilterActive(){
		$filterActive = HelperUC::getState(UniteCreatorManagerAddons::STATE_FILTER_ACTIVE);
		
		return($filterActive);
	}
	
	
	private function a________INIT______(){}
	
	/**
	 * validate that addon type is set
	 */
	protected function validateAddonType(){
		
		if(empty($this->objAddonType))
			UniteFunctionsUC::throwError("addons manager error: no addon type is set");
		
		if($this->objAddonType->isLayout != $this->isLayouts)
			UniteFunctionsUC::throwError("addons manager error: mismatch addon and layout types");
		
	}
	
	
	/**
	 * before init
	 */
	protected function beforeInit($addonType){
		
		$this->type = self::TYPE_ADDONS;
		$this->viewType = self::VIEW_TYPE_THUMB;
		$this->defaultFilterCatalog = self::FILTER_CATALOG_INSTALLED;
		
		if(emptY($this->filterAddonType))
			$this->setAddonType($addonType);
		
		$this->objBrowser = new UniteCreatorBrowser();
		$this->objBrowser->initAddonType($addonType);
		
		
		$this->urlBuy = GlobalsUC::URL_BUY;
		
		$this->hasCats = true;
	}
	
	/**
	 * run after init
	 */
	protected function afterInit($addonType){
		
		$this->validateAddonType();
		
		$this->itemsLoaderText = __("Getting ",BLOXBUILDER_TEXTDOMAIN).$this->textPlural;
		$this->textItemsSelected = $this->textPluralLower . __(" selected",BLOXBUILDER_TEXTDOMAIN);
		
		if($this->enableActiveFilter == true)
			$this->filterActive = self::getStateFilterActive();
		
		$this->filterCatalogState = $this->getStateFilterCatalog();
		
		//set selected category
		$lastCatID = HelperUC::getState(self::STATE_LAST_ADDONS_CATEGORY);
		if(!empty($lastCatID))
			$this->selectedCategory = $lastCatID;
		
		
		UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_MODIFY_ADDONS_MANAGER, $this);
		
	}
	
	/**
	 * init layout specific permissions
	 */
	protected function initByAddonType_layout(){
				
		$this->isLayouts = true;
		
		if($this->objAddonType->isLayout == false)
			return(false);
		
		$this->enableActiveFilter = false;
		$this->enableEnterName = false;
		$this->showTestAddon = false;
		$this->enablePreview = true;
		$this->enableViewThumbnail = true;
		
		if($this->objAddonType->paramsSettingsType == "screenshot")
			$this->enableMakeScreenshots = true;
		
	}
	
	
	/**
	 * init some settings by addon type
	 */
	protected function initByAddonType(){
				
		//svg permissions
		if($this->objAddonType->isSVG == true){
			$this->showTestAddon = false;
		}
		
		//layout permissions
		if($this->objAddonType->isLayout == true)
			$this->initByAddonType_layout();
		
		
		$single = $this->objAddonType->textSingle;
		$plural = 	$this->objAddonType->textPlural;
		
		$pluralLower = strtolower($plural);
		
		$this->textSingle = $single;
		$this->textPlural = $plural;
		$this->textSingleLower = strtolower($single);
		$this->textPluralLower = strtolower($plural);
		
		//set text
		$this->arrText["confirm_remove_addons"] = __("Are you sure you want to delete those {$pluralLower}?", BLOXBUILDER_TEXTDOMAIN);
		
		$objLayouts = new UniteCreatorLayouts();
		
		$this->arrOptions["is_layout"] = $this->isLayouts;
		$this->arrOptions["url_screenshot_template"] = $objLayouts->getUrlTakeScreenshot();
		
		
		$this->textAddAddon = __("Add ", BLOXBUILDER_TEXTDOMAIN).$single;
		
		//set default filter
		if($this->objAddonType->allowManagerWebCatalog == true)
			$this->defaultFilterCatalog = true;
		
	}
	
	
	/**
	 * set filter addon type to use only it
	 */
	public function setAddonType($addonType){
		
		$this->filterAddonType = $addonType;
				
		$this->objAddonType = UniteCreatorAddonType::getAddonTypeObject($addonType, $this->isLayouts);
		
		//UniteFunctionsUC::showTrace();
		
		//dmp($this->objAddonType);
		//exit();
		
		$this->initByAddonType();
	}
	
	
	/**
	 * set manager name
	 */
	public function setManagerNameFromData($data){
				
		$name = UniteFunctionsUC::getVal($data, "manager_name");
		$addontype = UniteFunctionsUC::getVal($data, "manager_addontype");
		$passData = UniteFunctionsUC::getVal($data, "manager_passdata");
		
		if(!empty($name))
			$this->setManagerName($name);
			
		if(!empty($passData) && is_array($passData)){
			$this->arrPassData = $passData;
		}
		
		$this->init($addontype);
	}
	
	
	private function a________ADDON_HTML______(){}
	
	
	/**
	 * get addon admin html add
	 */
	protected function getAddonAdminAddHtml(UniteCreatorAddon $objAddon){
		
		$addHtml = "";
				
		$addHtml = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_MANAGER_ADDON_ADDHTML, $addHtml, $objAddon);
		
		return($addHtml);
	}
	
	
	/**
	 * get data of the admin html from addon
	 */
	private function getAddonAdminHtml_getDataFromAddon(UniteCreatorAddon $objAddon){
		
		$data = array();
		
		$objAddon->validateInited();
		
		$title = $objAddon->getTitle();
		
		$name = $objAddon->getNameByType();
		
		$description = $objAddon->getDescription();
		
		//set html icon
		$urlIcon = $objAddon->getUrlIcon();
		
		//get preview html
		$urlPreview = $objAddon->getUrlPreview();
		
		$itemID = $objAddon->getID();
		
		$isActive = $objAddon->getIsActive();
		
		$addHtml = $this->getAddonAdminAddHtml($objAddon);
		
		$data["title"] = $title;
		$data["name"] = $name;
		$data["description"] = $description;
		$data["url_icon"] = $urlIcon;
		$data["url_preview"] = $urlPreview;
		$data["id"] = $itemID;
		$data["is_active"] = $isActive;
		$data["add_html"] = $addHtml;
		
		return($data);
	}
	
	/**
	 * get data from layout
	 */
	private function getAddonAdminHtml_getDataFromLayout(UniteCreatorLayout $objLayout){
		
		$data = array();
		
		$data["title"] = $objLayout->getTitle();
		$data["name"] = $objLayout->getName();
		$data["description"] = $objLayout->getDescription();
		$data["url_icon"] = $objLayout->getIcon();
		$data["url_preview"] = $objLayout->getPreviewImage();
		$data["id"] = $objLayout->getID();
		$data["is_active"] = true;		//no setting in layout yet
		$data["add_html"] = "";
		
		return($data);
	}
	
	
	/**
	 * get add html of web addon
	 */
	private function getWebAddonData($addon){
		
		$isFree = UniteCreatorBrowser::isWebAddonFree($addon); 
		
		$state = UniteCreatorBrowser::STATE_PRO;
		if($isFree == true)
			$state = UniteCreatorBrowser::STATE_FREE;
		
		$data = $this->objBrowser->getCatalogAddonStateData($state);
		
		return($data);
	}
	
	
	/**
	 * get addons or layout by type
	 */
	private function getCatAddonsOrLayouts($catID, $filterActive){
		
		$isLayout = $this->objAddonType->isLayout;
		
		if($isLayout == false){		//addons
			$objAddons = new UniteCreatorAddons();
			$addons = $objAddons->getCatAddons($catID, false, $filterActive, $this->filterAddonType);
			
			return($addons);
		}
				
		//layouts
		$objLayouts = new UniteCreatorLayouts();
		$arrLayouts = $objLayouts->getCatLayouts($catID, $this->objAddonType);
		
		return($arrLayouts);
	}
	
	
	/**
	 * get category addons, objects or array from catalog
	 */
	private function getCatAddons($catID, $title = "", $isweb = false){
		
		$filterActive = self::getStateFilterActive();
		$filterType = $this->filterAddonType;
		
		$filterCatalog = $this->getStateFilterCatalog();
		
		$addons = array();
		
		switch($filterCatalog){
			case self::FILTER_CATALOG_WEB:
			break;
			case self::FILTER_CATALOG_INSTALLED:
				if($isweb == false)
					$addons = $this->getCatAddonsOrLayouts($catID, $filterActive);
									
				return($addons);
			break;
			case self::FILTER_CATALOG_MIXED:
				if($isweb == false)
					$addons = $this->getCatAddonsOrLayouts($catID, $filterActive);
			break;
		}
		
		
		//mix with the catalog
				
		//get category title
		if(!empty($catID) && empty($title)){
			$objCategories = new UniteCreatorCategories();
			$arrCat = $objCategories->getCat($catID);
			$title = UniteFunctionsUC::getVal($arrCat, "title");
		}
		
		if(empty($title))
			return($addons);
		
		if($this->objAddonType->allowManagerWebCatalog == false)
			return($addons);
		
		$webAPI = new UniteCreatorWebAPI();
		$addons = $webAPI->mergeCatAddonsWithCatalog($title, $addons, $this->objAddonType);

		
		return($addons);
	}
	
	/**
	 * get additional addhtml, function for override
	 */
	protected function getAddonAdminHtml_AddHtml($addHtml, $objAddon){
		
		
		return($addHtml);
	}
	
	/**
	 * get html addon
	 */
	public function getAddonAdminHtml($objAddon){
		
		
		if(is_array($objAddon))
			$data = $objAddon;
		else{
			if($this->objAddonType->isLayout == false)
				$data = $this->getAddonAdminHtml_getDataFromAddon($objAddon);
			else
				$data = $this->getAddonAdminHtml_getDataFromLayout($objAddon);
		}
		
		//--- prepare data
		
		$title = UniteFunctionsUC::getVal($data, "title");
		$name = UniteFunctionsUC::getVal($data, "name");
		$description = UniteFunctionsUC::getVal($data, "description");
		$urlIcon = UniteFunctionsUC::getVal($data, "url_icon");
		$urlPreview = UniteFunctionsUC::getVal($data, "url_preview");
		$itemID = UniteFunctionsUC::getVal($data, "id");
		$isActive = UniteFunctionsUC::getVal($data, "is_active");
		$addHtml = UniteFunctionsUC::getVal($data, "add_html");
		$isweb = UniteFunctionsUC::getVal($data, "isweb");
		$liAddHTML = "";
		
		$state = null;
		
		if($isweb == true){
						
			$urlPreview = UniteFunctionsUC::getVal($data, "image");
			$isActive = true;
			$webData = $this->getWebAddonData($data);
			
			$addHtml = $webData["html_state"];
			$addHtml .= $webData["html_additions"];
			$state = $webData["state"];
			
			$itemID = UniteFunctionsUC::getSerialID("webaddon");
			$liAddHTML = " data-itemtype='web' data-state='{$state}'";
		}
		
		UniteFunctionsUC::validateNotEmpty($itemID, "item id");
		
		$addHtml = $this->getAddonAdminHtml_AddHtml($addHtml, $objAddon);
		
		
		//--- prepare output
				
		$title = htmlspecialchars($title);
		$name = htmlspecialchars($name);
		$description = htmlspecialchars($description);
		
		$descOutput = $description;
		
		$htmlPreview = "";
		
		if($this->showAddonTooltip === true && !empty($urlPreview)){
			$urlPreviewHtml = htmlspecialchars($urlPreview);
			$htmlPreview = "data-preview='$urlPreviewHtml'";
		}
		
		$class = "uc-addon-thumbnail";
		if($isActive == false)
			$class .= " uc-item-notactive";
		
		if($isweb == true)
			$class .= " uc-item-web";
			
		$class = "class=\"{$class}\"";
		
		//set html output
		$htmlItem  = "<li id=\"uc_item_{$itemID}\" data-id=\"{$itemID}\" data-title=\"{$title}\" data-name=\"{$name}\" data-description=\"{$description}\" {$liAddHTML} {$htmlPreview} {$class} >";
		
		if($state == UniteCreatorBrowser::STATE_PRO){
			$urlBuy = $this->urlBuy;
			$htmlItem .= "<a href='$urlBuy' target='_blank'>";
		}
		
		if($this->viewType == self::VIEW_TYPE_INFO){
			
			$htmlItem .= "	<div class=\"uc-item-title unselectable\" unselectable=\"on\">{$title}</div>";
			$htmlItem .= "	<div class=\"uc-item-description unselectable\" unselectable=\"on\">{$descOutput}</div>";
			$htmlItem .= "	<div class=\"uc-item-icon unselectable\" unselectable=\"on\"></div>";
			
			//add icon
			$htmlIcon = "";
			if(!empty($urlIcon))
				$htmlIcon = "<div class='uc-item-icon' style=\"background-image:url('{$urlIcon}')\"></div>";
			
			$htmlItem .= $htmlIcon;
			
		}elseif($this->viewType == self::VIEW_TYPE_THUMB){
						
			$classThumb = "";
			$style = "";
						
			//if svg type - set preview url as svg
			
			if($this->objAddonType->isSVG == true){
				
				$classThumb .= " uc-type-shape-devider";
				
				if($isweb == false){
					$urlPreview = null;
					
					$svgContent = $objAddon->getHtml();
					$urlPreview = UniteFunctionsUC::encodeSVGForBGUrl($svgContent);
				}
				
			}
			
			
			if(empty($urlPreview))
				$classThumb = " uc-no-thumb";
			else{
				$style = "style=\"background-image:url('{$urlPreview}')\"";
			}
			
			
			$htmlItem .= "	<div class=\"uc-item-thumb{$classThumb} unselectable\" unselectable=\"on\" {$style}></div>";
			
			
			$htmlItem .= "	<div class=\"uc-item-title unselectable\" unselectable=\"on\">{$title}</div>";
			
			if($addHtml)
				$htmlItem .= $addHtml;
			
		}else{
			UniteFunctionsUC::throwError("Wrong addons view type");
		}
		
		if($state == UniteCreatorBrowser::STATE_PRO){
			$htmlItem .= "</a>";
		}
		
		$htmlItem .= "</li>";
		
		
		return($htmlItem);
	}
	
	
	/**
	 * get html of cate items
	 */
	public function getCatAddonsHtml($catID, $title = "", $isweb = false){
		
		$addons = $this->getCatAddons($catID, $title, $isweb);
		
		$htmlAddons = "";
		
		foreach($addons as $addon){
			
			$html = $this->getAddonAdminHtml($addon);
			$htmlAddons .= $html;
		}
		
		return($htmlAddons);
	}
	
	
	
	/**
	 * get html of categories and items.
	 */
	public function getCatsAndAddonsHtml($catID, $catTitle = "", $isweb = false){
		
		$arrCats = $this->getArrCats();
		
		
		//change category if needed
		$arrCatsAssoc = UniteFunctionsUC::arrayToAssoc($arrCats, "id");
		
		if(isset($arrCatsAssoc[$catID]) == false){
			$firstCat = reset($arrCats);
			if(!empty($firstCat)){
				$catID = $firstCat["id"];
				$catTitle = $firstCat["title"];
				$isweb = UniteFunctionsUC::getVal($firstCat, "isweb");
				$isweb = UniteFunctionsUC::strToBool($isweb);
			}
		}
		
		$objCats = new UniteCreatorCategories();
		$htmlCatList = $this->getCatList($catID);
		
		$htmlAddons = $this->getCatAddonsHtml($catID, $catTitle, $isweb);
		
		$response = array();
		$response["htmlItems"] = $htmlAddons;
		$response["htmlCats"] = $htmlCatList;
	
		return($response);
	}
	
	/**
	 * set last selected category state
	 */
	private function setStateLastSelectedCat($catID){
		HelperUC::setState(self::STATE_LAST_ADDONS_CATEGORY, $catID);
	}
	
	
	/**
	 * get category items html
	 */
	public function getCatAddonsHtmlFromData($data){
		
		$catID = UniteFunctionsUC::getVal($data, "catID");
		$catTitle = UniteFunctionsUC::getVal($data, "title");
		
		
		$objAddons = new UniteCreatorAddons();
		
		$resonseCombo = UniteFunctionsUC::getVal($data, "response_combo");
		$resonseCombo = UniteFunctionsUC::strToBool($resonseCombo);
				
		$filterActive = UniteFunctionsUC::getVal($data, "filter_active");
		
		$isweb = UniteFunctionsUC::getVal($data, "isweb");
		$isweb = UniteFunctionsUC::strToBool($isweb);
		
		if($isweb == false && $catID != "all")
			UniteFunctionsUC::validateNumeric($catID,"category id");
		
		if(GlobalsUC::$enableWebCatalog == true){
			
			$filterCatalog = UniteFunctionsUC::getVal($data, "filter_catalog");
			self::setStateFilterCatalog($filterCatalog);
		}
		
		self::setStateFilterActive($filterActive);
		$this->setStateLastSelectedCat($catID);
		
		if($resonseCombo == true){
			
			$response = $this->getCatsAndAddonsHtml($catID, $catTitle, $isweb);
			
		}else{
			$itemsHtml = $this->getCatAddonsHtml($catID, $catTitle, $isweb);
			$response = array("itemsHtml"=>$itemsHtml);
		}
		
		
		return($response);
	}
		
		
	private function a________DIALOGS______(){}
	
	
	/**
	 * put import addons dialog
	 */
	private function putDialogImportAddons(){
		
		$importText = __("Import ", BLOXBUILDER_TEXTDOMAIN).$this->textPlural;
		$textSelect = __("Select ",BLOXBUILDER_TEXTDOMAIN) . $this->textPluralLower . __(" export zip file (or files)",BLOXBUILDER_TEXTDOMAIN);
		$textLoader = __("Uploading ",BLOXBUILDER_TEXTDOMAIN) . $this->textSingleLower. __(" file...", BLOXBUILDER_TEXTDOMAIN);
		$textSuccess = $this->textSingle . __(" Added Successfully", BLOXBUILDER_TEXTDOMAIN);
		
		$dialogTitle = $importText;
		
		$textOverwrite = __("Overwrite Existing ", BLOXBUILDER_TEXTDOMAIN).$this->textPlural;
		if($this->isLayouts == true)
			$textOverwrite = __("Overwrite Addons", BLOXBUILDER_TEXTDOMAIN);
		
		
		$nonce = "";
		if(method_exists("UniteProviderFunctionsUC", "getNonce"))
			$nonce = UniteProviderFunctionsUC::getNonce();
		?>
		
			<div id="dialog_import_addons" class="unite-inputs" title="<?php echo $dialogTitle?>" style="display:none;">
				
				<div class="unite-dialog-top"></div>
				
				<div class='dialog-import-addons-left'>
					
					<div class="unite-inputs-label">
						<?php echo $textSelect?>:
					</div>
					
					<div class="unite-inputs-sap-small"></div>
				
					<form id="dialog_import_addons_form" action="<?php echo GlobalsUC::$url_ajax?>" name="form_import_addon" class="dropzone uc-import-addons-dropzone">
						<input type="hidden" name="action" value="<?php echo GlobalsUC::PLUGIN_NAME?>_ajax_action">
						<input type="hidden" name="client_action" value="import_addons">
						
						<?php if(!empty($nonce)):?>
							<input type="hidden" name="nonce" value="<?php echo $nonce?>">
						<?php endif?>
						<script type="text/javascript">
							if(typeof Dropzone != "undefined")
								Dropzone.autoDiscover = false;
						</script>
					</form>	
						<div class="unite-inputs-sap-double"></div>
						
						<div class="unite-inputs-label">
							<?php _e("Import to Category", BLOXBUILDER_TEXTDOMAIN)?>:
							
						<select id="dialog_import_catname">
							<option value="autodetect" ><?php _e("[Autodetect]", BLOXBUILDER_TEXTDOMAIN)?></option>
							<option id="dialog_import_catname_specific" value="specific"><?php _e("Current Category", BLOXBUILDER_TEXTDOMAIN)?></option>
						</select>
							
						</div>
						
						<div class="unite-inputs-sap-double"></div>
						
						<div class="unite-inputs-label">
							<label for="dialog_import_check_overwrite">
								
								<?php echo $textOverwrite ?>:
								
							</label>
							<input type="checkbox" checked="checked" id="dialog_import_check_overwrite"></input>
						</div>
						
				
				</div>
				
				<div id="dialog_import_addons_log" class='dialog-import-addons-right' style="display:none">
					
					<div class="unite-bold"> <?php echo $importText.__(" Log",BLOXBUILDER_TEXTDOMAIN)?> </div>
					
					<br>
					
					<div id="dialog_import_addons_log_text" class="dialog-import-addons-log"></div>
				</div>
				
				<div class="unite-clear"></div>
				
				<?php 
					$prefix = "dialog_import_addons";
					$buttonTitle = $importText;
					$loaderTitle = $textLoader;
					$successTitle = $textSuccess;
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
				
					
			</div>		
		<?php 
	}
	
	/**
	 * put quick edit dialog
	 */
	private function putDialogQuickEdit(){
		?>
			<!-- dialog quick edit -->
		
			<div id="dialog_edit_item_title"  title="<?php _e("Quick Edit",BLOXBUILDER_TEXTDOMAIN)?>" style="display:none;">
			
				<div class="dialog_edit_title_inner unite-inputs mtop_20 mbottom_20" >
			
					<div class="unite-inputs-label-inline">
						<?php _e("Title", BLOXBUILDER_TEXTDOMAIN)?>:
					</div>
					<input type="text" id="dialog_quick_edit_title" class="unite-input-wide">
					
					
					<?php if($this->enableEnterName):?>
					<div class="unite-inputs-sap"></div>
							
					<div class="unite-inputs-label-inline">
						<?php _e("Name", BLOXBUILDER_TEXTDOMAIN)?>:
					</div>
					<input type="text" id="dialog_quick_edit_name" class="unite-input-wide">
					
					<?php else:?>
					
					<input type="hidden" id="dialog_quick_edit_name">
					
					<?php endif?>
					
					<div class="unite-inputs-sap"></div>
					
					<div class="unite-inputs-label-inline">
						<?php _e("Description", BLOXBUILDER_TEXTDOMAIN)?>:
					</div>
					
					<textarea class="unite-input-wide" id="dialog_quick_edit_description"></textarea>
					
				</div>
				
			</div>
		
		<?php 
	}

	
	/**
	 * put category edit dialog
	 */
	protected function putDialogEditCategory(){
		
		$prefix = "uc_dialog_edit_category";
		
		?>
			<div id="uc_dialog_edit_category" class="uc-dialog-edit-category" data-custom='yes' title="<?php _e("Edit Category",BLOXBUILDER_TEXTDOMAIN)?>" style="display:none;" >
				
				<div class="unite-dialog-top"></div>
				
				<div class="unite-dialog-inner-constant">	
					<div id="<?php echo $prefix?>_settings_loader" class="loader_text"><?php _e("Loading Settings", BLOXBUILDER_TEXTDOMAIN)?>...</div>
					
					<div id="<?php echo $prefix?>_settings_content"></div>
					
				</div>
				
				<?php 
					$buttonTitle = __("Update Category", BLOXBUILDER_TEXTDOMAIN);
					$loaderTitle = __("Updating Category...", BLOXBUILDER_TEXTDOMAIN);
					$successTitle = __("Category Updated", BLOXBUILDER_TEXTDOMAIN);
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>			
				
			</div>
		
		<?php
	}
	
	/**
	 * put category edit dialog
	 */
	protected function putDialogAddonProperties(){
		
		$prefix = "uc_dialog_addon_properties";
		
		$textTitle =  $this->textSingle.__(" Properties", BLOXBUILDER_TEXTDOMAIN);
		
		
		?>
			<div id="uc_dialog_addon_properties" class="uc-dialog-addon-properties" data-custom='yes' title="<?php echo $textTitle?>" style="display:none;" >
				
				<div class="unite-dialog-top"></div>
				
				<div class="unite-dialog-inner-constant">	
					<div id="<?php echo $prefix?>_settings_loader" class="loader_text uc-settings-loader"><?php _e("Loading Properties", BLOXBUILDER_TEXTDOMAIN)?>...</div>
					
					<div id="<?php echo $prefix?>_settings_content" class="uc-settings-content"></div>
					
				</div>
				
				<?php 
					$buttonTitle = __("Update ", BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
					$loaderTitle = __("Updating...", BLOXBUILDER_TEXTDOMAIN);
					$successTitle = $this->textSingle.__(" Updated", BLOXBUILDER_TEXTDOMAIN);
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>			
				
			</div>
		
		<?php
	}
	
	
	/**
	 * put add addon dialog
	 */
	private function putDialogAddAddon(){
				
		?>
			<!-- add addon dialog -->
			
			<div id="dialog_add_addon" class="unite-inputs" title="<?php echo $this->textAddAddon?>" style="display:none;">
			
				<div class="unite-dialog-top"></div>
			
				<div class="unite-inputs-label">
					<?php echo $this->textSingle.__(" Title", BLOXBUILDER_TEXTDOMAIN)?>:
				</div>
				
				<input type="text" id="dialog_add_addon_title" class="dialog_addon_input unite-input-regular" />
				
				<?php if($this->enableEnterName):?>
				<div class="unite-inputs-sap"></div>
				
				<div class="unite-inputs-label">
					<?php echo $this->textSingle.__(" Name")?>:
				</div>
				
				<input type="text" id="dialog_add_addon_name" class="dialog_addon_input unite-input-alias" />
				
				<?php else:?>
				
				<input type="hidden" id="dialog_add_addon_name" value="" />
				
				<?php endif?>
				
				<div class="unite-inputs-sap"></div>
				
				<div class="unite-inputs-label">
					<?php echo $this->textSingle.__(" Description")?>:
				</div>
				
				<textarea id="dialog_add_addon_description" class="dialog_addon_input unite-input-regular"></textarea>
				
				<?php 
				
					$prefix = "dialog_add_addon";
					$buttonTitle = $this->textAddAddon;
					$loaderTitle = __("Adding ",BLOXBUILDER_TEXTDOMAIN).$this->textSingle."...";
					$successTitle = $this->textSingle. __(" Added Successfully", BLOXBUILDER_TEXTDOMAIN);
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
				
			</div>
		
		<?php 
	}	
	
	private function a________MENUS______(){}
	
	
	/**
	 * get single item menu
	 */
	protected function getMenuSingleItem(){
		
		$arrMenuItem = array();
		$arrMenuItem["edit_addon"] = __("Edit ",BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
		$arrMenuItem["edit_addon_blank"] = __("Edit In New Tab",BLOXBUILDER_TEXTDOMAIN);
		
		if($this->enablePreview == true)
			$arrMenuItem["preview_addon"] = __("Preview",BLOXBUILDER_TEXTDOMAIN);
		
		if($this->enableViewThumbnail)
			$arrMenuItem["preview_thumb"] = __("View Thumbnail",BLOXBUILDER_TEXTDOMAIN);
		
		if($this->enableMakeScreenshots)
			$arrMenuItem["make_screenshots"] = __("Make Thumbnail",BLOXBUILDER_TEXTDOMAIN);
		
			
		$arrMenuItem["quick_edit"] = __("Quick Edit",BLOXBUILDER_TEXTDOMAIN);
		$arrMenuItem["remove_item"] = __("Delete",BLOXBUILDER_TEXTDOMAIN);
		
		if($this->showTestAddon){
			$arrMenuItem["test_addon"] = __("Test ",BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
			$arrMenuItem["test_addon_blank"] = __("Test In New Tab",BLOXBUILDER_TEXTDOMAIN);
		}	
		
		$arrMenuItem["export_addon"] = __("Export ",BLOXBUILDER_TEXTDOMAIN).$this->textSingle;
		
		$arrMenuItem = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_MANAGER_MENU_SINGLE, $arrMenuItem);
		
		return($arrMenuItem);
	}

	
	/**
	 * get item field menu
	 */
	protected function getMenuField(){
		$arrMenuField = array();
				
		$arrMenuField["select_all"] = __("Select All",BLOXBUILDER_TEXTDOMAIN);
		
		$arrMenuField = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_MANAGER_MENU_FIELD, $arrMenuField);
		
		return($arrMenuField);
	}
	
	
	
	/**
	 * get multiple items menu
	 */
	protected function getMenuMulitipleItems(){
		$arrMenuItemMultiple = array();
		$arrMenuItemMultiple["remove_item"] = __("Delete",BLOXBUILDER_TEXTDOMAIN);
		
		if($this->enableMakeScreenshots == true)
			$arrMenuItemMultiple["make_screenshots"] = __("Make Thumbnails",BLOXBUILDER_TEXTDOMAIN);
		
		$arrMenuItemMultiple = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_MANAGER_MENU_MULTIPLE, $arrMenuItemMultiple);
		
		return($arrMenuItemMultiple);
	}
	
	
	/**
	 * get category menu
	 */
	protected function getMenuCategory(){
	
		$arrMenuCat = array();
		$arrMenuCat["edit_category"] = __("Edit Category",BLOXBUILDER_TEXTDOMAIN);
		$arrMenuCat["delete_category"] = __("Delete Category",BLOXBUILDER_TEXTDOMAIN);
		
		
		$arrMenuCat = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_MANAGER_MENU_CATEGORY, $arrMenuCat);
		
		return($arrMenuCat);
	}
	
	private function a________DATA______(){}
	
	/**
	 * filter categories without web addons
	 */
	private function filterCatsWithoutWeb($arrCats){
		
		foreach($arrCats as $key=>$cat){
			$isweb = UniteFunctionsUC::getVal($cat, "isweb");
			$isweb = UniteFunctionsUC::strToBool($isweb);
			if($isweb == true)
				continue;
			
			$numWebAddons = UniteFunctionsUC::getVal($cat, "num_web_addons");
			if($numWebAddons == 0)
				unset($arrCats[$key]);
		}
		
		return($arrCats);
	}
	
	
	/**
	 * get categories with catalog
	 */
	private function getCatsWithCatalog($filterCatalog){
		
		$objAddons = new UniteCreatorAddons();
		$webAPI = new UniteCreatorWebAPI();
		
		$arrCats = $objAddons->getAddonsWidthCategories(true, true, $this->filterAddonType);
		
		$arrCats = $this->modifyLocalCats($arrCats);
		
		if($this->objAddonType->allowManagerWebCatalog == true)
			$arrCats = $webAPI->mergeCatsAndAddonsWithCatalog($arrCats, true, $this->objAddonType);
		
		if($filterCatalog == self::FILTER_CATALOG_WEB)
			$arrCats = $this->filterCatsWithoutWeb($arrCats);
		
				
		return($arrCats);
	}
	
	
	/**
	 * modify local categories - create one if empty, and required
	 */
	protected function modifyLocalCats($arrCats){
		
		if(!empty($arrCats))
			return($arrCats);
		
		if($this->objAddonType->allowNoCategory == true)
			return($arrCats);

		//add default category
		
		$objCategory = new UniteCreatorCategory();
		$objCategory->addDefaultByAddonType($this->objAddonType);
		
		$arrCats = $this->objCats->getListExtra($this->objAddonType);
		
		return($arrCats);
	}
	
	
	/**
	 * get categories
	 */
	protected function getArrCats(){
		
		$filterCatalog = $this->getStateFilterCatalog();
		
		switch($filterCatalog){
			case self::FILTER_CATALOG_MIXED:
			case self::FILTER_CATALOG_WEB:
				$arrCats = $this->getCatsWithCatalog($filterCatalog);
			break;
			default:	//installed type
				
				$arrCats = $this->objCats->getListExtra($this->objAddonType);
				
				$arrCats = $this->modifyLocalCats($arrCats);
				
			break;
		}
		
		
		return($arrCats);
	}
	
	
	/**
	 * get category list
	 */
	protected function getCatList($selectCatID = null, $arrCats = null){
		
		if($arrCats === null)
			$arrCats = $this->getArrCats();
					
		
		//dmp("add web cats");
		//dmp($arrCats);
		//exit();
		
		$htmlCatList = $this->objCats->getHtmlCatList($selectCatID, $this->objAddonType, $arrCats);
		
		return($htmlCatList);
	}
	
	/**
	 * get category settings from cat ID
	 */
	protected function getCatagorySettings(UniteCreatorCategory $objCat){
		
		$title = $objCat->getTitle();
		$alias = $objCat->getAlias();
		$params = $objCat->getParams();
		$catID = $objCat->getID();
		
		$settings = new UniteCreatorSettings();
		
		$settings->addStaticText("Category ID: <b>$catID</b>","some_name");
		$settings->addTextBox("category_title", $title, __("Category Title",BLOXBUILDER_TEXTDOMAIN));
		$settings->addTextBox("category_alias", $alias, __("Category Name",BLOXBUILDER_TEXTDOMAIN));
		$settings->addIconPicker("icon","",__("Category Icon", BLOXBUILDER_TEXTDOMAIN));
		
		$settings = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_MANAGER_ADDONS_CATEGORY_SETTINGS, $settings, $objCat, $this->filterAddonType);
		
		$settings->setStoredValues($params);
		
		return($settings);
	}
	
	
	
	private function a________OTHERS______(){}
	
	
	/**
	 * get addon type object
	 */
	public function getObjAddonType(){
		
		return($this->objAddonType);
	}
	
	/**
	 * return if layouts or addons type
	 */
	public function getIsLayoutType(){
		$this->validateAddonType();
		
		return($this->isLayouts);
	}
	
	
	/**
	 * get no items text
	 */
	protected function getNoItemsText(){
		
		$text = $this->objAddonType->textNoAddons;

		UniteFunctionsUC::validateNotEmpty($text,"text addon type");
		
		return($text);
	}
	
	
	/**
	 * get html categories select
	 */
	protected function getHtmlSelectCats(){
		
		if($this->hasCats == false)
			UniteFunctionsUC::throwError("the function ");
		
		$htmlSelectCats = $this->objCats->getHtmlSelectCats($this->filterAddonType);
		
		return($htmlSelectCats);
	}
	
	
	/**
	 * put content to items wrapper div
	 */
	protected function putListWrapperContent(){
		$addonType = $this->filterAddonType;
		if(empty($addonType))
			$addonType = "default";
		
		$filepathEmptyAddons = GlobalsUC::$pathProviderViews."empty_addons_text_{$addonType}.php";
		if(file_exists($filepathEmptyAddons) == false)
			return(false);
		
		?>
		<div id="uc_empty_addons_wrapper" class="uc-empty-addons-wrapper" style="display:none">
			
			<?php include $filepathEmptyAddons?>
			
		</div>
		<?php 
	}
	
	
	/**
	 * put items buttons
	 */
	protected function putItemsButtons(){
		
		$textImport = __("Import ",BLOXBUILDER_TEXTDOMAIN) . $this->textPlural;
		$textEdit = __("Edit ",BLOXBUILDER_TEXTDOMAIN) . $this->textSingle;
		$textTest = "Test ".$this->textSingle;
		
		?>
			
			<?php 
			 UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_MANAGER_ITEM_BUTTONS1);
			?>
 			<a data-action="add_addon" type="button" class="unite-button-secondary unite-button-blue button-disabled uc-button-item uc-button-add"><?php echo $this->textAddAddon?></a> 
 			<a data-action="import_addon" type="button" class="unite-button-secondary unite-button-blue button-disabled uc-button-item uc-button-add"><?php echo $textImport?></a>
 			<a data-action="select_all_items" type="button" class="unite-button-secondary button-disabled uc-button-item uc-button-select" data-textselect="<?php _e("Select All",BLOXBUILDER_TEXTDOMAIN)?>" data-textunselect="<?php _e("Unselect All",BLOXBUILDER_TEXTDOMAIN)?>"><?php _e("Select All",BLOXBUILDER_TEXTDOMAIN)?></a>

			<?php 
			 UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_MANAGER_ITEM_BUTTONS2);
			?>
 			
	 		<a data-action="remove_item" type="button" class="unite-button-secondary button-disabled uc-button-item"><?php _e("Delete",BLOXBUILDER_TEXTDOMAIN)?></a>
	 			 		
	 		<a data-action="edit_addon" type="button" class="unite-button-primary button-disabled uc-button-item uc-single-item"><?php echo $textEdit?> </a>
	 		<a data-action="quick_edit" type="button" class="unite-button-secondary button-disabled uc-button-item uc-single-item"><?php _e("Quick Edit",BLOXBUILDER_TEXTDOMAIN)?></a>
	 		
	 		<?php if($this->showTestAddon):?>
	 		<a data-action="test_addon" type="button" class="unite-button-secondary button-disabled uc-button-item uc-single-item"><?php echo $textTest?></a>
			<?php endif?>
			
			<?php 
			 UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_MANAGER_ITEM_BUTTONS3);
			?>
			
			<?php if($this->enablePreview == true):?>
	 		
	 		<a data-action="preview_addon" type="button" class="unite-button-secondary button-disabled uc-button-item uc-single-item"><?php _e("Preview", BLOXBUILDER_TEXTDOMAIN)?> </a>
			
			<?php endif?>
			
	 		<?php if($this->enableActiveFilter == true):?>
	 			
		 		<a data-action="activate_addons" type="button" class="unite-button-secondary button-disabled uc-button-item uc-notactive-item"><?php _e("Activate",BLOXBUILDER_TEXTDOMAIN)?></a>
		 		<a data-action="deactivate_addons" type="button" class="unite-button-secondary button-disabled uc-button-item uc-active-item"><?php _e("Deactivate",BLOXBUILDER_TEXTDOMAIN)?></a>
	 		
	 		<?php endif?>
	 		
	 		<?php if($this->enableMakeScreenshots == true):?>
	 		
	 		<a data-action="make_screenshots" type="button" class="unite-button-secondary button-disabled uc-button-item uc-single-item"><?php _e("Make Thumb", BLOXBUILDER_TEXTDOMAIN)?> </a>
	 		<a data-action="make_screenshots" type="button" class="unite-button-secondary button-disabled uc-button-item uc-multiple-items"><?php _e("Make Thumbs", BLOXBUILDER_TEXTDOMAIN)?> </a>
	 		
	 		<?php endif?>
		<?php
	}
	
	/**
	 * get current layout shortcode template
	 */
	protected function getShortcodeTemplate(){
		
		$shortcodeTemplate = "{blox_page id=%id% title=\"%title%\"}";
		
		return($shortcodeTemplate);
	}
	
	
	/**
	 * put shortcode in the filters area
	 */
	protected function putShortcode(){
	
		if($this->objAddonType->enableShortcodes == false)
			return(false);
		
		$shortcodeTemplate = $this->getShortcodeTemplate();
		$shortcodeTemplate = htmlspecialchars($shortcodeTemplate);
		
		?>
		<div class="uc-single-item-related">
			<div class="uc-filters-set-title"><?php _e("Shortcode", BLOXBUILDER_TEXTDOMAIN)?>:</div>
			<div class="uc-filters-set-content"> <input type="text" readonly class="uc-filers-set-shortcode" data-template="<?php echo $shortcodeTemplate?>" value=""></div>
		</div>
		
		<?php 
		
	}
	
	/**
	 * put catalog filters
	 */
	protected function putFiltersCatalog(){
		
		if(GlobalsUC::$enableWebCatalog == false)
			return(false);
		
		if($this->objAddonType->allowManagerWebCatalog == false)
			return(false); 
		
		$classActive = "class='uc-active'";
			
		$filterCatalog = $this->filterCatalogState;
		
		$textFilterAddons = __("Filter ", BLOXBUILDER_TEXTDOMAIN).$this->textPlural;
		
		?>
			<div class="uc-filters-set-sap"></div>
			
			<div class="uc-filters-set-title"><?php echo $textFilterAddons?>:</div>
			
			<div id="uc_filters_catalog" class="uc-filters-set">
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="<?php echo self::FILTER_CATALOG_MIXED?>" <?php echo ($filterCatalog == self::FILTER_CATALOG_MIXED)?$classActive:""?> ><?php _e("Web and Installed", BLOXBUILDER_TEXTDOMAIN)?></a>
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="<?php echo self::FILTER_CATALOG_INSTALLED?>" <?php echo ($filterCatalog == self::FILTER_CATALOG_INSTALLED)?$classActive:""?> ><?php _e("Installed", BLOXBUILDER_TEXTDOMAIN)?></a>
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="<?php echo self::FILTER_CATALOG_WEB?>" <?php echo ($filterCatalog == self::FILTER_CATALOG_WEB)?$classActive:""?> ><?php _e("Web", BLOXBUILDER_TEXTDOMAIN)?></a>
			</div>
		
		<?php 
	}
	
	/**
	 * put filters - function for override
	 */
	protected function putItemsFilters(){
		
		$classActive = "class='uc-active'";
		$filter = $this->filterActive;
		if(empty($filter))
			$filter = "all";
				
		$textShow = __("Show ", BLOXBUILDER_TEXTDOMAIN).$this->textPlural;
		
		?>
		
		<div class="uc-items-filters">
		
			<?php if($this->enableActiveFilter):?>
			
			<div class="uc-filters-set-title"><?php echo $textShow?>:</div>
						
			<div id="uc_filters_active" class="uc-filters-set">
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="all" <?php echo ($filter == "all")?$classActive:""?> ><?php _e("All", BLOXBUILDER_TEXTDOMAIN)?></a>
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="active" <?php echo ($filter == "active")?$classActive:""?> ><?php _e("Active", BLOXBUILDER_TEXTDOMAIN)?></a>
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="not_active" <?php echo ($filter == "not_active")?$classActive:""?> ><?php _e("Not Active", BLOXBUILDER_TEXTDOMAIN)?></a>
			</div>
			
			<?php endif?>
			
			<?php $this->putFiltersCatalog()?>
			
			<?php $this->putShortcode()?>
			
			<div class="unite-clear"></div>
		</div>
		
		<?php 
	}
	
	
	
	/**
	 * get category settings html
	 */
	public function getCatSettingsHtmlFromData($data){
		
		$catID = UniteFunctionsUC::getVal($data, "catid");
		UniteFunctionsUC::validateNotEmpty($catID, "category id");
		
		$objCat = new UniteCreatorCategory();
		$objCat->initByID($catID);
		
		$settings = $this->getCatagorySettings($objCat);
		
		$output = new UniteSettingsOutputWideUC();
		$output->init($settings);
		
		ob_start();
		$output->draw("uc_category_settings");
		
		$htmlSettings = ob_get_contents();
		
		ob_end_clean();
		
		$response = array();
		$response["html"] = $htmlSettings;
		
		return($response);
	}
	
	/**
	 * 
	 * get properties html from data
	 */
	public function getAddonPropertiesDialogHtmlFromData($data){
		
		if($this->objAddonType->isLayout == false)
			UniteFunctionsUC::throwError("The addon type should be layouts for props");
		
		$layoutID = UniteFunctionsUC::getVal($data, "id");
		$objLayout = new UniteCreatorLayout();
		$objLayout->initByID($layoutID);
		
		$settings = $objLayout->getPageParamsSettingsObject();
		
		$htmlSettings = HelperHtmlUC::drawSettingsGetHtml($settings,"settings_addon_props");
		
		$output = array();
		$output["html"] = $htmlSettings;
		
		return($output);
	}
	
	
	
	
	

	/**
	 * put scripts
	 */
	private function putScripts(){
		
		$arrPlugins = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_MANAGER_ADDONS_PLUGINS, array());
		
		//$arrPlugins[] = "UCManagerMaster";
		
		$script = "
			var g_ucManagerAdmin;
			
			jQuery(document).ready(function(){
				var selectedCatID = \"{$this->selectedCategory}\";
				g_ucManagerAdmin = new UCManagerAdmin();";
		
		if(!empty($arrPlugins)){
			foreach($arrPlugins as $plugin)
				$script .= "\n				g_ucManagerAdmin.addPlugin('{$plugin}');";
		}
		
		$script .= "
				g_ucManagerAdmin.initManager(selectedCatID);
			});
		";
		
		
		UniteProviderFunctionsUC::printCustomScript($script);
	}
	
	
	/**
	 * put preview tooltips
	 */
	protected function putPreviewTooltips(){
		?>
		<div id="uc_manager_addon_preview" class="uc-addon-preview-wrapper" style="display:none"></div>
		<?php 
	}
	
	
	/**
	 * put additional html here
	 */
	protected function putAddHtml(){
		$this->putDialogQuickEdit();
		$this->putDialogAddAddon();
		$this->putDialogAddonProperties();
		$this->putDialogImportAddons();
		
		if($this->showAddonTooltip)
			$this->putPreviewTooltips();
		
		$this->putScripts();
	}
	
	
	/**
	 * put init items, will not run, because always there are cats
	 */
	protected function putInitItems(){
		
		if($this->hasCats == true)
			return(false);
		
		$objAddons = new UniteCreatorAddons();
		$htmlAddons = $objAddons->getCatAddonsHtml(null,false,null,$this->filterAddonType);
		
		echo $htmlAddons;
	}
	
	
	/**
	 * 
	 * set the custom data to manager wrapper div
	 */
	protected function onBeforePutHtml(){
				
		$addonsType = $this->objAddonType->typeNameDistinct;
				
		$addHTML = "data-addonstype=\"{$addonsType}\"";
		
		$this->setManagerAddHtml($addHTML); 
	}
	
		
	
}