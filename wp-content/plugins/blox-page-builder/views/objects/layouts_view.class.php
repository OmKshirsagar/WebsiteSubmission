<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');


class UniteCreatorLayoutsView{
	
	protected $isTemplate = false;
	protected $layoutType, $layoutTypeTitle, $layoutTypeTitlePlural;
	protected $objLayoutType;
	
	protected $showButtonsPanel = true, $showHeaderTitle = true;
	protected $showColCategory = true, $showColShortcode = true;
	protected $isDisplayTable = true;
	protected $objTable, $urlViewCreateObject, $urlManageAddons;
	protected $arrLayouts, $pageBuilder, $objLayouts, $objManager;
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$this->objTable = new UniteTableUC();
		$this->pageBuilder = new UniteCreatorPageBuilder();
		$this->objLayouts = new UniteCreatorLayouts();
		
	}
	
	private function _______INIT____________(){}
	
	
	/**
	 * set templates text
	 */
	protected function getTemplatesTextArray(){
		
		$pluralLower = strtolower($this->layoutTypeTitlePlural);
		$titleLower = strtolower($this->layoutTypeTitle);
		
		
		$arrText = array(
			"import_layout"=>__("Import ",BLOXBUILDER_TEXTDOMAIN).$this->layoutTypeTitle,
			"import_layouts"=>__("Import ",BLOXBUILDER_TEXTDOMAIN).$this->layoutTypeTitlePlural,
			"uploading_layouts_file"=>__("Uploading ",BLOXBUILDER_TEXTDOMAIN). $this->layoutTypeTitlePlural. __("  file...",BLOXBUILDER_TEXTDOMAIN),
			"layouts_added_successfully"=> $this->layoutTypeTitle.__(" Added Successfully",BLOXBUILDER_TEXTDOMAIN),
			"my_layouts"=>__("My ",BLOXBUILDER_TEXTDOMAIN).$this->layoutTypeTitlePlural,
			"search_layout"=> __("Search",BLOXBUILDER_TEXTDOMAIN)." ". $this->layoutTypeTitlePlural,
			"layout_title"=>$this->layoutTypeTitle." ". __("Title",BLOXBUILDER_TEXTDOMAIN),
			"no_layouts_found"=>__("No",BLOXBUILDER_TEXTDOMAIN)." ".$this->layoutTypeTitlePlural. " ". __("Found",BLOXBUILDER_TEXTDOMAIN),
			"are_you_sure_to_delete_this_layout"=>__("Are you sure to delete this ?",BLOXBUILDER_TEXTDOMAIN).$titleLower,
			"edit_layout"=>__("Edit",BLOXBUILDER_TEXTDOMAIN)." ".$this->layoutTypeTitle,
			"manage_layout_categories"=>__("Manage ",BLOXBUILDER_TEXTDOMAIN). $this->layoutTypeTitlePlural. __(" Categories",BLOXBUILDER_TEXTDOMAIN),
			"select_layouts_export_file"=>__("Select ",BLOXBUILDER_TEXTDOMAIN). $pluralLower.  __(" export file",BLOXBUILDER_TEXTDOMAIN),
			"new_layout"=>__("New",BLOXBUILDER_TEXTDOMAIN)." ". $this->layoutTypeTitle,
		);
		
		return($arrText);
	}
	
	
	/**
	 * set templat etype
	 */
	public function setLayoutType($layoutType){
		
		$this->layoutType = $layoutType;
		
		$this->objLayoutType = UniteCreatorAddonType::getAddonTypeObject($layoutType, true);
		
		//set title
		$this->layoutTypeTitle = $this->objLayoutType->textSingle;
		$this->layoutTypeTitlePlural = $this->objLayoutType->textPlural;
		
		//set text
		$arrText = $this->getTemplatesTextArray();
		
		HelperUC::setLocalText($arrText);
		
		//set other settings
		$this->isTemplate = $this->objLayoutType->isTemplate;
		$this->showColCategory = $this->objLayoutType->enableCategories;
		$this->showColShortcode = $this->objLayoutType->enableShortcodes;
		
		//set display type manager / table
		$displayType = UniteFunctionsUC::getGetVar("displaytype", "",UniteFunctionsUC::SANITIZE_TEXT_FIELD);
		if(empty($displayType))
			$displayType = $this->objLayoutType->displayType;
		
			
		if($displayType == UniteCreatorAddonType_Layout::DISPLAYTYPE_MANAGER)
			$this->isDisplayTable = false;
		
	}
	
	
	/**
	 * validate inited
	 */
	protected function validateInited(){
		
		if(empty($this->objLayoutType))
			UniteFunctionsUC::throwError("The layout type not inited, please use : setLayoutType function");
		
		if($this->objLayoutType->isLayout == false)
			UniteFunctionsUC::throwError("The layout type should be layout type, now: ".$this->objLayoutType->textShowType);
		
			
	}
	
	/**
	 * init display vars table related
	 */
	protected function initDisplayVars_table(){
		
		$this->objTable->setDefaultOrderby("title");
		
		$pagingOptions = $this->objTable->getPagingOptions();
		
		if(!empty($this->layoutType)){
			$pagingOptions["layout_type"] = $this->layoutType;
		}
		
		$response = $this->objLayouts->getArrLayoutsPaging($pagingOptions);
		
		$this->arrLayouts = $response["layouts"];
		$pagingData = $response["paging"];
		
		$urlLayouts = HelperUC::getViewUrl_LayoutsList();
		
		$this->objTable->setPagingData($urlLayouts, $pagingData);
		
	}
	
	
	/**
	 * 
	 * init manager display vars
	 */
	protected function initDisplayVars_manager(){
		
		$this->objManager = new UniteCreatorManagerLayouts();
		$this->objManager->init($this->layoutType);
		
	}
	
	
	/**
	 * init display vars
	 */
	protected function initDisplayVars(){
		
		//init layout type		
		$this->urlViewCreateObject = HelperUC::getViewUrl_Layout();
		$this->urlManageAddons = HelperUC::getViewUrl_Addons();
			
		
		if($this->showHeaderTitle == true){
			$headerTitle = HelperUC::getText("my_layouts");
			require HelperUC::getPathTemplate("header");
		}else
			require HelperUC::getPathTemplate("header_missing");
		
		//table object
		if($this->isDisplayTable == true)
			$this->initDisplayVars_table();
		else
			$this->initDisplayVars_manager();
	}
	
	
	private function _______PUT_HTML____________(){}
	
	
	/**
	 * put page catalog browser
	 */
	public function putDialogPageCatalog(){
		
		$webAPI = new UniteCreatorWebAPI();
		$isPageCatalogExists = $webAPI->isPagesCatalogExists();
		if($isPageCatalogExists == false)
			return(false);
		
		$objBrowser = new UniteCreatorBrowser();		
		$objBrowser->initAddonType(GlobalsUC::ADDON_TYPE_REGULAR_LAYOUT);
		$objBrowser->putBrowser();
		
	}
	
	
	/**
	 * put manage categories dialog
	 */
	public function putDialogCategories(){
		
		$prefix = "uc_dialog_add_category";
		
		?>
			<div id="uc_dialog_add_category"  title="<?php HelperUC::putText("manage_layout_categories")?>" style="display:none; height: 300px;" class="unite-inputs">
				
				<div class="unite-dialog-top">
				
					<input type="text" class="uc-catdialog-button-clearfilter" style="margin-bottom: 1px;">
					<a class='uc-catdialog-button-filter unite-button-secondary' href="javascript:void(0)"><?php _e("Filter", BLOXBUILDER_TEXTDOMAIN)?></a>
					<a class='uc-catdialog-button-filter-clear unite-button-secondary' href="javascript:void(0)"><?php _e("Clear Filter", BLOXBUILDER_TEXTDOMAIN)?></a>
					
					<span class="uc-catlist-sort-wrapper">
					
						<?php _e("Sort: ",BLOXBUILDER_TEXTDOMAIN)?>
						<a href="javascript:void(0)" class="uc-link-change-cat-sort" data-type="a-z">a-z</a>
						, 
						<a href="javascript:void(0)" class="uc-link-change-cat-sort" data-type="z-a">z-a</a>
					</span>
					
				</div>
				
				<div id="list_layouts_cats" class="uc-categories-list"></div>
				
				<hr/>
				
					<?php _e("Add New Category", BLOXBUILDER_TEXTDOMAIN)?>: 
					<input id="uc_dialog_add_category_catname" type="text" class="unite-input-regular" value="">
					
					<a id="uc_dialog_add_category_button_add" href="javascript:void(0)" class="unite-button-secondary" data-action="add_category"><?php _e("Create Category", BLOXBUILDER_TEXTDOMAIN)?></a>
					
				<div>
				
					<?php 
					$buttonTitle = __("Set Category to Page", BLOXBUILDER_TEXTDOMAIN);
					$loaderTitle = __("Updating Category...", BLOXBUILDER_TEXTDOMAIN);
					$successTitle = __("Category Updated", BLOXBUILDER_TEXTDOMAIN);
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
				
				
			</div>
			
			<div id="uc_layout_categories_message" title="<?php _e("Categories Message", BLOXBUILDER_TEXTDOMAIN)?>">
			</div>
			
		</div>
		
		<?php 
	}
	
	
	/**
	 * put import addons dialog
	 */
	public function putDialogImportLayout(){
	
		$dialogTitle = HelperUC::getText("import_layout");
		
		?>
		
			<div id="uc_dialog_import_layouts" class="unite-inputs" title="<?php echo $dialogTitle?>" style="display:none;">
				
				<div class="unite-dialog-top"></div>
				
				<div class="unite-inputs-label">
					<?php HelperUC::putText("select_layouts_export_file")?>:
				</div>
				
				<form id="dialog_import_layouts_form" name="form_import_layouts">
					<input id="dialog_import_layouts_file" type="file" name="import_layout">
							
				</form>	
				
				<div class="unite-inputs-sap-double"></div>
				
				<div class="unite-inputs-label" >
					<label for="dialog_import_layouts_file_overwrite">
						<?php _e("Overwrite Addons", BLOXBUILDER_TEXTDOMAIN)?>:
					</label>
					<input type="checkbox" id="dialog_import_layouts_file_overwrite">
				</div>
				
				
				<div class="unite-clear"></div>
				
				<?php 
					$prefix = "uc_dialog_import_layouts";
					
					$buttonTitle = HelperUC::getText("import_layouts");
					$loaderTitle = HelperUC::getText("uploading_layouts_file");
					$successTitle = HelperUC::getText("layouts_added_successfully");
					
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
					
			</div>		
		
	<?php
	}
	
	
	
	
	/**
	 * put buttons panel html
	 */
	protected function putHtmlButtonsPanel(){
				
		?>
		<div class="uc-buttons-panel unite-clearfix">
			<a href="<?php echo $this->urlViewCreateObject?>" class="unite-button-primary unite-float-left"><?php HelperUC::putText("new_layout");?></a>
			
			<a id="uc_button_import_layout" href="javascript:void(0)" class="unite-button-secondary unite-float-left mleft_20"><?php HelperUC::putText("import_layouts");?></a>
			
			<a href="javascript:void(0)" id="uc_layouts_global_settings" class="unite-float-right mright_20 unite-button-secondary"><?php HelperUC::putText("layouts_global_settings");?></a>
			<a href="<?php echo $this->urlManageAddons?>" class="unite-float-right mright_20 unite-button-secondary"><?php _e("My Addons", BLOXBUILDER_TEXTDOMAIN)?></a>
			
		</div>
		<?php 
	}
	
	/**
	 * display notice
	 */
	protected function putHtmlTemplatesNotice(){
		
		if($this->isTemplate == false)
			return(false);
			
		?>
			<div class="uc-layouts-notice"> Notice - The templates will work for only if the blox template selected</div>
		<?php 
	}
	
	
	/**
	 * put layout type tabs
	 */
	public function putLayoutTypeTabs(){
		
		//$arrLayoutTypes = $this->objLayouts->getArrLayoutTypes();
		
		dmp("get all template types");
		exit();
		
		?>
		<div class="uc-layout-type-tabs-wrapper">
			
			<?php foreach($arrLayoutTypes as $type => $arrType):

				$tabTitle = UniteFunctionsUC::getVal($arrType, "plural");
				
				$urlView = HelperUC::getViewUrl_TemplatesList(null, $type);
				
				$addClass = "";
				if($type == $this->layoutType){
					$addClass = " uc-tab-selected";
					$urlView = "javascript:void(0)";
				}
				
			?>
			<a href="<?php echo $urlView?>" class="uc-tab-layouttype<?php echo $addClass?>"><?php echo $tabTitle?></a>
			
			<?php endforeach?>
						
		</div>
		<?php 
		
	}
	
	/**
	 * display manager
	 */
	public function displayManager(){
		
		$this->objManager->outputHtml();
				
	}
	
	
	/**
	 * display table view
	 */
	public function display(){
		
		$this->validateInited();
		$this->initDisplayVars();
		
		if($this->isDisplayTable)
			$this->displayTable();
		else
			$this->displayManager();
			
	}
	
	
	/**
	 * display layouts view
	 */
	public function displayTable(){
				
		$sizeActions = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_LAYOUTS_ACTIONS_COL_WIDTH, 380);
		
		$numLayouts = count($this->arrLayouts);

		
?>
	<?php if($this->showButtonsPanel == true)
			$this->putHtmlButtonsPanel();
	?>

	<div class="unite-content-wrapper">
						
		<?php //$this->putHtmlTemplatesNotice(); ?>
		
		<?php
		
		$this->objTable->putActionsFormStart();
		
		if($this->isTemplate == true)
			$this->putLayoutTypeTabs();
		
		?>
		<div class="unite-table-filters">
		
		
		<?php 
		$this->objTable->putSearchForm(HelperUC::getText("search_layout"), "Clear");
		
			if($this->isTemplate == false):
			
				$this->objTable->putFilterCategory();
		
			endif;
		
		?>
		
		</div>
		
		<?php if(empty($this->arrLayouts)): ?>
		<div class="uc-no-layouts-wrapper">
			<?php HelperUC::putText("no_layouts_found");?>
		</div>			
		<?php else:?>
	
			<!-- sort chars: &#8743 , &#8744; -->
			
			<table id="uc_table_layouts" class='unite_table_items' data-text-delete="<?php HelperUC::putText("are_you_sure_to_delete_this_layout")?>">
				<thead>
					<tr>
						<th width=''>
							<?php $this->objTable->putTableOrderHeader("title", HelperUC::getText("layout_title")) ?>
						</th>
						
						<?php if($this->showColShortcode == true):?>
						<th width='200'><?php _e("Shortcode",BLOXBUILDER_TEXTDOMAIN); ?></th>
						<?php endif?>
						
						<?php if($this->showColCategory == true):?>
						<th width='200'><?php $this->objTable->putTableOrderHeader("catid", __("Category",BLOXBUILDER_TEXTDOMAIN)) ?>
						<?php endif?>
						
						<th width='<?php echo $sizeActions?>'><?php _e("Actions",BLOXBUILDER_TEXTDOMAIN); ?></th>
						<th width='60'><?php _e("Preview",BLOXBUILDER_TEXTDOMAIN); ?></th>						
					</tr>
				</thead>
				<tbody>

					<?php foreach($this->arrLayouts as $key=>$layout):
						
						$id = $layout->getID();
																
						$title = $layout->getTitle();

						$shortcode = $layout->getShortcode();
						$shortcode = UniteFunctionsUC::sanitizeAttr($shortcode);
												
						$editLink = HelperUC::getViewUrl_Layout($id);
												
						$previewLink = HelperUC::getViewUrl_LayoutPreview($id, true);
						
						$showTitle = HelperHtmlUC::getHtmlLink($editLink, $title);
						
						$rowClass = ($key%2==0)?"unite-row1":"unite-row2";
						
						$arrCategory = $layout->getCategory();
						
						$catID = UniteFunctionsUC::getVal($arrCategory, "id");
						$catTitle = UniteFunctionsUC::getVal($arrCategory, "name");
						
					?>
						<tr class="<?php echo $rowClass?>">
							<td><?php echo $showTitle?></td>
							
							<?php if($this->showColShortcode):?>
							
							<td>
								<input type="text" readonly onfocus="this.select()" class="unite-input-medium unite-cursor-text" value="<?php echo $shortcode?>" />
							</td>
							
							<?php endif?>
							
							<?php if($this->showColCategory):?>
							
							<td><a href="javascript:void(0)" class="uc-layouts-list-category" data-layoutid="<?php echo $id?>" data-catid="<?php echo $catID?>" data-action="manage_category"><?php echo $catTitle?></a></td>
							
							<?php endif?>
							
							<td>
								<a href='<?php echo $editLink?>' class="unite-button-primary float_left mleft_15"><?php HelperUC::putText("edit_layout"); ?></a>
								
								<a href='javascript:void(0)' data-layoutid="<?php echo $id?>" data-id="<?php echo $id?>" class="button_delete unite-button-secondary float_left mleft_15"><?php _e("Delete",BLOXBUILDER_TEXTDOMAIN); ?></a>
								<span class="loader_text uc-loader-delete" style="display:none"><?php _e("Deleting", BLOXBUILDER_TEXTDOMAIN)?></span>
								<a href='javascript:void(0)' data-layoutid="<?php echo $id?>" data-id="<?php echo $id?>" class="button_duplicate unite-button-secondary float_left mleft_15"><?php _e("Duplicate",BLOXBUILDER_TEXTDOMAIN); ?></a>
								<span class="loader_text uc-loader-duplicate" style="display:none"><?php _e("Duplicating", BLOXBUILDER_TEXTDOMAIN)?></span>
								<a href='javascript:void(0)' data-layoutid="<?php echo $id?>" data-id="<?php echo $id?>" class="button_export unite-button-secondary float_left mleft_15"><?php _e("Export",BLOXBUILDER_TEXTDOMAIN); ?></a>
								<?php UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_LAYOUTS_LIST_ACTIONS, $id); ?>
							</td>
							<td>
								<a href='<?php echo $previewLink?>' target="_blank" class="unite-button-secondary float_left"><?php _e("Preview",BLOXBUILDER_TEXTDOMAIN); ?></a>					
							</td>
						</tr>							
					<?php endforeach;?>
					
				</tbody>		 
			</table>
			
			<?php 
			
				$this->objTable->putPaginationHtml();				
				$this->objTable->putInpageSelect();
				
			?>
			
		<?php endif?>
		
		<?php
		 
			$this->objTable->putActionsFormEnd();
			
			$this->pageBuilder->putLayoutsGlobalSettingsDialog();
			$this->putDialogImportLayout();
			
			$this->putDialogCategories();
			
			//put pages catalog if exists
			$this->putDialogPageCatalog();
		?>
		
		
	</div>
	
<script type="text/javascript">

	jQuery(document).ready(function(){

		var objAdmin = new UniteCreatorAdmin_LayoutsList();
		objAdmin.initObjectsListView();
		
	});

</script>

	<?php 	
		
	}
	
}

