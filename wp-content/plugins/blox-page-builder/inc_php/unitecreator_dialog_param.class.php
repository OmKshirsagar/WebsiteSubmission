<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorDialogParamWork{
	
	const TYPE_MAIN = "main";
	const TYPE_ITEM_VARIABLE = "variable_item";
	const TYPE_MAIN_VARIABLE = "variable_main";
	const TYPE_FORM_ITEM = "form_item";
	
	const PARAM_EDITOR = "uc_editor";
	const PARAM_TEXTFIELD = "uc_textfield";
	const PARAM_TEXTAREA = "uc_textarea";
	const PARAM_NUMBER = "uc_number";
	const PARAM_RADIOBOOLEAN = "uc_radioboolean";
	const PARAM_DROPDOWN = "uc_dropdown";
	const PARAM_HR = "uc_hr";	
	const PARAM_CONTENT = "uc_content";	
	const PARAM_POST = "uc_post";
	const PARAM_DATASET = "uc_dataset";
	const PARAM_POSTS_LIST = "uc_posts_list";
	const PARAM_INSTAGRAM = "uc_instagram";
	const PARAM_MENU = "uc_menu";
	const PARAM_COLORPICKER = "uc_colorpicker";
	const PARAM_CHECKBOX = "uc_checkbox";
	const PARAM_AUDIO = "uc_mp3";
	const PARAM_FONT_OVERRIDE = "uc_font_override";
	const PARAM_ICON = "uc_icon";
	const PARAM_SHAPE = "uc_shape";
	const PARAM_IMAGE = "uc_image";
	const PARAM_MAP = "uc_map";
	const PARAM_FORM = "uc_form";
	const PARAM_ADDONPICKER = "uc_addonpicker";
	
	const PARAM_VAR_GET = "uc_var_get";
	
	private $addon, $objSettings, $objDatasets;
	private $type;
	private $arrContentIDs = array();
	private $arrParamsTypes = array();
	protected $arrParams = array();
	
	private $option_putTitle = true;
	private $option_putAdminLabel = true;
	private $option_arrTexts = array();
	private $option_putDecsription = true;
	private $option_allowFontEditCheckbox = true;
	
	
	/**
	 * init all params
	 */
	public function __construct(){
		$this->initParamTypes();
	}
	
	/**
	 * modify param text, function for override
	 */
	protected function modifyParamText($paramType, $paramText){
		
		return($paramText);
	}
	
	
	/**
	 * add param to the list
	 */
	protected function addParam($paramType, $paramText){
		
		$paramText = $this->modifyParamText($paramType, $paramText);
		
		$this->arrParamsTypes[$paramType] = $paramText;
	}
	
	
	/**
	 * set the param types
	 */
	protected function initParamTypes(){
		
		$this->addParam("uc_textfield", __("Text Field", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam("uc_number", __("Number", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam("uc_radioboolean", __("Radio Boolean", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam("uc_textarea", __("Text Area", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_EDITOR, __("Editor", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam("uc_checkbox", __("Checkbox", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam("uc_dropdown", __("Dropdown", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam("uc_colorpicker", __("Color Picker", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_IMAGE, __("Image", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_HR, __("HR Line", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_FONT_OVERRIDE, __("Font Override", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_ADDONPICKER, __("Addon Picker", BLOXBUILDER_TEXTDOMAIN));
		
		$this->addParam(self::PARAM_AUDIO, __("Audio", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_ICON, __("Icon", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_SHAPE, __("Shape", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_CONTENT, __("Content", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_POST, __("Post", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_POSTS_LIST, __("Posts List", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_FORM, __("Form", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_INSTAGRAM, __("Instagram", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_MAP, __("Google Map", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_MENU, __("Menu", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_DATASET, __("Dataset", BLOXBUILDER_TEXTDOMAIN));
		
		//variables
		$this->addParam("uc_varitem_simple", __("Simple Variable", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam("uc_var_paramrelated", __("Attribute Related", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam("uc_var_paramitemrelated", __("Item Attribute Related", BLOXBUILDER_TEXTDOMAIN));
		$this->addParam(self::PARAM_VAR_GET, __("GET Param", BLOXBUILDER_TEXTDOMAIN));
		
	}
	
	
	/**
	 * validate that the dialog inited
	 */
	private function validateInited(){
		if(empty($this->type))
			UniteFunctionsUC::throwError("Empty params dialog");
	}

	
	private function a___________________MAIN_PARAMS________________(){}
	
	
	/**
	 * put instagram param
	 */
	private function putInstagramParam(){
		?>
			<div class="unite-inputs-label">
				<?php _e("Max Items", BLOXBUILDER_TEXTDOMAIN)?>
			</div>
			
			<input type="text" name="max_items" class="unite-input-number" value="">
			
			<div class="unite-inputs-description">
				* <?php _e("Put number of items (1-12), or empty for all the items (12)", BLOXBUILDER_TEXTDOMAIN)?>
			</div>
			
			<br>
			
		<?php 
		
		$this->putStyleCheckbox();
	}
	
	
	/**
	 * put google map param
	 */
	private function putGoogleMapParam(){
		?>
			<div class="unite-inputs-label">
				<?php _e("Defaults for google map", BLOXBUILDER_TEXTDOMAIN)?>
			</div>
			
		<?php 
	}
	
	
	/**
	 * put form param
	 */
	private function putFormParam(){
		?>
			<div class="unite-inputs-label">
				<?php _e("Form Params Goes Here", BLOXBUILDER_TEXTDOMAIN)?>
			</div>
		<?php 
	}
	
	/**
	 * put no default value text
	 */
	protected function putNoDefaultValueText($text = "", $addStyleCheckbox = false){
		
		if(empty($text))
			_e("No default value for this attribute", BLOXBUILDER_TEXTDOMAIN);
		else
			echo $text;
			
		if($addStyleCheckbox == true)
			$this->putStyleCheckbox();
	}
	
	
	/**
	 * put style checkbox
	 */
	private function putStyleCheckbox(){
		?>
				<div class='uc-dialog-param-style-checkbox-wrapper'>
					<div class="unite-inputs-sap"></div>
					<label class="unite-inputs-label-inline-free">
							<?php _e("Allow Font Edit", BLOXBUILDER_TEXTDOMAIN)?>:
						 	<input type="checkbox" onfocus="this.blur()" name="font_editable">
					</label>
					<div class="unite-dialog-description-left"><?php _e("Allow edit font for this field in font style tab. Must be put with the {{fieldname|raw}} in html", BLOXBUILDER_TEXTDOMAIN)?></div>
				</div>
		<?php 
	}
	
	/**
	 * put items available only for the form
	 */
	private function putFormItemInputs(){
		
		$id = "required_checkbox_".UniteFunctionsUC::getRandomString();
		
		?>
		
		<div class="vert_sap20"></div>
			
		<div class="unite-inputs-label">
			
			<label for="<?php echo $id?>">
			<?php _e("Field Required", BLOXBUILDER_TEXTDOMAIN) ?>:
			</label> 
			
			<input id="<?php echo $id?>" type="checkbox" name="is_required">
			
		</div>
		
		<?php 
		
	}
	
	/**
	 * put default value param in params dialog
	 */
	private function putDefaultValueParam($isTextarea = false, $class="", $addStyleChekbox = false){
		
		//disable in form item mode
		$putTextareaText = true;
		
		if($this->option_allowFontEditCheckbox == false){
			$addStyleChekbox = false;
			$putTextareaText = false;
		}
				
		$strClass = "";
		if(!empty($class))
			$strClass = "class='{$class}'";
				
		?>
				<div class="unite-inputs-label">
					<?php _e("Default Value", BLOXBUILDER_TEXTDOMAIN)?>:
				</div>
				
				<?php if($isTextarea == false):?>
				
				<input type="text" name="default_value" <?php echo $strClass?> value="">
				
				<?php else: ?>
				
				<textarea name="default_value" <?php echo $strClass?>> </textarea>
				
					<?php if($putTextareaText == true):?>
					
						<br><br>
						
						* <?php _e("To allow html tags, use",BLOXBUILDER_TEXTDOMAIN)?> <b>|raw</b> <?php _e("filter", BLOXBUILDER_TEXTDOMAIN) ?> <br><br>
						&nbsp;&nbsp;&nbsp; <?php _e("example",BLOXBUILDER_TEXTDOMAIN)?> : {{myfield|raw}}
						
					<?php endif?>
				
				<?php endif?>
		
				<?php if($addStyleChekbox == true):
					
					$this->putStyleCheckbox();
				
				endif?>
				
				<?php 
				if($this->type == self::TYPE_FORM_ITEM)
					$this->putFormItemInputs();
				?>
		<?php 
	}
	
	
	/**
	 * put font override param
	 */
	private function putFontOverrideParam(){
		?>
				
				* <?php _e("Use this font override in css tab using special function",BLOXBUILDER_TEXTDOMAIN)?> 
				
		<?php 
	}
	
	/**
	 * put color picker default value
	 */
	private function putColorPickerDefault(){
		?>
			<?php _e("Default Value", BLOXBUILDER_TEXTDOMAIN)?>:
			
			<div class="vert_sap5"></div>
 		    <input type="text" name="default_value" class="uc-text-colorpicker" value="#ffffff" data-initval="#ffffff">
			<div class='unite-color-picker-element'></div>
		<?php 
	}
	
	
	/**
	 * put number unit select
	 */
	private function putNumberUnitSelect(){
		?>
				<div class="unite-inputs-label-inline-suffix">
					<?php _e("Suffix")?>:
				</div>
				
				<select name="unit" class='uc-select-unit' data-initval="px">
					<option value="px">px</option>
					<option value="ms">ms</option>
					<option value="%">ms</option>
					<option value="">[none]</option>
					<option value="other">[custom]</option>
				</select>
				
				<input type="text" class='uc-text-unit-custom input-small' name="unit_custom" style="display:none">
		<?php
	}

	
	/**
	 * put radio yes no option
	 */
	private function putRadioYesNo($name, $text = null, $defaultTrue = false, $yesText = "Yes", $noText="No", $isTextNear = false){
	
		if($defaultTrue == true){
			$trueChecked = " checked ";
			$falseChecked = "";
			$defaultValue = "true";
		}else{
			$defaultValue = "false";
			$trueChecked = "";
			$falseChecked = " checked ";
		}
		
		//make not repeated id's
		$idPrefix = "uc_param_radio_".$this->type."_".$name;
		
		$idYes = $idPrefix."_yes";
		$idNo = $idPrefix."_no";
		
		?>
			<div class='uc-radioset-wrapper' data-defaultchecked="<?php echo $defaultValue?>">
			
			<?php if(!empty($text)): ?>
				<span class="uc-radioset-title">
				<?php _e($text, BLOXBUILDER_TEXTDOMAIN)?>:
				</span>
			<?php endif?>
			
				<input id="<?php echo $idYes?>" type="radio" name="<?php echo $name?>" value="true" <?php echo $trueChecked?>>
				<label for="<?php echo $idYes?>"><?php _e($yesText, BLOXBUILDER_TEXTDOMAIN)?></label>
				
				<input id="<?php echo $idNo?>" type="radio" name="<?php echo $name?>" value="false" <?php echo $falseChecked?>>
				<label for="<?php echo $idNo?>"><?php _e($noText, BLOXBUILDER_TEXTDOMAIN)?></label>
				
				<?php if($isTextNear == true):?>
					<input type="text" name="text_near" class="unite-input-medium">
					<?php _e("(text near)", BLOXBUILDER_TEXTDOMAIN)?>
					
				<?php endif?>
			</div>
			
		
		<?php 
	}
	
	
	/**
	 * put radio boolean param
	 */
	private function putRadioBooleanParam(){
		?>
			<table data-inputtype="radio_boolean"  class='uc-table-dropdown-items uc-table-dropdown-full'>
				<thead>
					<tr>
						<th width="100px"><?php _e("Item Text", BLOXBUILDER_TEXTDOMAIN)?></th>
						<th width="100px"><?php _e("Item Value", BLOXBUILDER_TEXTDOMAIN)?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="text" name="true_name" value="Yes" data-initval="Yes" class='uc-dropdown-item-name'></td>
						<td><input type="text" name="true_value" value="true" data-initval="true" class='uc-dropdown-item-value'></td>
						<td>
							<div class='uc-dropdown-icon uc-dropdown-item-default uc-selected' title="<?php _e("Default Item", BLOXBUILDER_TEXTDOMAIN)?>"></div>
						</td>
					</tr>
					<tr>
						<td><input type="text" name="false_name" value="No" data-initval="No" class='uc-dropdown-item-name'></td>
						<td><input type="text" name="false_value" value="false" data-initval="false" class='uc-dropdown-item-value'></td>
						<td>
							<div class='uc-dropdown-icon uc-dropdown-item-default' title="<?php _e("Default Item", BLOXBUILDER_TEXTDOMAIN)?>"></div>
						</td>
					</tr>
					
				</tbody>
			</table>
		<?php 
	}
	
	
	/**
	 * add checkbox section param to image param type
	 */
	private function putImageParam_addThumbSection($thumbName, $text, $addSuffix){
		$IDprefix = "uc_param_image_".$this->type."_";
		
		$checkID = $IDprefix.$thumbName;
		$inputID = $IDprefix.$thumbName."_input";
		
		?>
			<label for="<?php echo $checkID?>">
				<input id="<?php echo $checkID?>" type="checkbox" class="uc-param-image-checkbox uc-control" data-controlled-selector="#<?php echo $inputID?>" name="<?php echo $thumbName?>">
				<?php _e($text, BLOXBUILDER_TEXTDOMAIN)?>
			</label>
			<input id="<?php echo $inputID?>" type="text" data-addsuffix="<?php echo $addSuffix?>" style="display:none" disabled class="mleft_5 unite-input-alias uc-param-image-thumbname">
			
		<?php 
	}
	
	
	/**
	 * put single setting input
	 */
	private function putSingleSettingInput($name, $text, $type){
		
		?>			
			<div class="unite-inputs-label"><?php echo $text?>:</div>
		<?php 
		
		$objSettings = new UniteCreatorSettings();
		$objSettings->setCurrentAddon($this->addon);
		
		switch($type){
			case "image":
				$objSettings->addImage($name, "", $text, array("source"=>"addon"));
			break;
			case "mp3":
				$objSettings->addMp3($name, "", $text, array("source"=>"addon"));
			break;
			default:
				UniteFunctionsUC::throwError("Wrong seting type: $type");
			break;
		}
		
		$objOutput = new UniteSettingsOutputWideUC();
		$objOutput->init($objSettings);
		$objOutput->drawSingleSetting($name);
		
	}
	
	
	/**
	 * put image select input
	 */
	private function putImageSelectInput($name, $text){
		
		$this->putSingleSettingInput($name, $text, "image");
	}
	
	
	/**
	 * put mp3 select input
	 */
	private function putMp3SelectInput($name, $text){
		
		$this->putSingleSettingInput($name, $text, "mp3");
		
	}
	
	/**
	 * put image param settings
	 */
	private function putImageParam(){
		
		?>
			<div class="uc-tab-content-desc">
				<?php _e("* You can add thumbnails creation to the image. Turn them on if you will use them in addon", BLOXBUILDER_TEXTDOMAIN)?>
			</div>
			
			<div class="unite-inputs-sap"></div>
			
			<?php $this->putImageParam_addThumbSection("add_thumb", "Add Thumbnail", "thumb") ?>
			
			<div class="unite-inputs-sap"></div>
			
			<?php $this->putImageParam_addThumbSection("add_thumb_large", "Add Thumbnail - Large","thumb_large") ?>
			
			<div class="unite-inputs-sap"></div>
						
			<?php $this->putImageSelectInput("default_value",__("Default Image",BLOXBUILDER_TEXTDOMAIN)); ?>
			
		<?php 
	}

	
	/**
	 * put mp3 param
	 */
	private function putMp3Param(){
	
		$this->putMp3SelectInput("default_value",__("Default Audio File Url",BLOXBUILDER_TEXTDOMAIN));
	}
	
	/**
	 * put menu param
	 */
	private function putMenuParam(){
		
		$settings = new UniteCreatorSettings();
		$settings->addSelect("menu_html_base", array("UL LI"=>"ul","DIV"=>"div","NAV"=>"nav"), __("Menu Html Based On", BLOXBUILDER_TEXTDOMAIN), "ul",array("description"=>"The main menu tag, and child tags accordingly"));
		$settings->addTextBox("menu_class","","Menu Class",array("description"=>"ul or div that wraps the menu items class"));
		$settings->addTextBox("menu_submenu_class","","Sub Menu Class",array("description"=>"submenu wrapper ul or div class"));
		$settings->addTextBox("menu_item_class","","Menu Item Class",array("description"=>"items li or a class"));
		$settings->addTextBox("menu_item_active_class","","Menu Active Item Class",array("description"=>"the item of current page"));
		$settings->addTextBox("menu_item_current_class","","Menu Current Item Class",array("description"=>"the parent item of the active child item"));
		
		$settings->addHr();
		$settings->addSelect("menu_wrap_submenu_item", array("Don't wrap"=>"no","Wrap"=>"wrap"), __("Wrap Items With Submenu", BLOXBUILDER_TEXTDOMAIN), "no", array("description"=>"Wrap items that contains submenu with div"));
		$settings->addTextBox("menu_submenu_wrapper_class","","Submenu Wrapper Div Class",array("description"=>"The class of the submenu wrapper div"));
		$settings->addControl("menu_wrap_submenu_item", "menu_submenu_wrapper_class", "show", "wrap");
		
		
		$objOutput = new UniteSettingsOutputWideUC();
		$objOutput->init($settings);
		$objOutput->draw("menu_param_settings", false);
		
		?>
		<!--  
			<div class="unite-inputs-label-inline-suffix">
				<?php _e("Menu Html Based On")?>:
			</div>
			
			<select name="menu_html_base" class='' data-initval="ul">
				<option value="ul" selected>UL LI</option>
				<option value="div">DIV</option>
			</select>
		-->
		<?php 
	}

	
	/**
	 * put menu param
	 */
	private function putDatasetParam(){
				
		$arrDatasetsNames = $this->objDatasets->getDatasetTypeNames();
		$settings = new UniteCreatorSettings();
		
		if(empty($arrDatasetsNames))
			$settings->addStaticText("No dataset types found");
		else{
			
			$firstType = UniteFunctionsUC::getFirstNotEmptyKey($arrDatasetsNames);
			$arrDatasetsNames = array_flip($arrDatasetsNames);
			
			$settings->addSelect("dataset_type", $arrDatasetsNames, __("Choose Dataset Type", BLOXBUILDER_TEXTDOMAIN), $firstType ,array("description"=>"select the datase type"));
			
			//put queries
			$arrDatasetObjects = $this->objDatasets->getDatasetTypes();
			
			foreach($arrDatasetObjects as $type=>$dataset){
								
				$queries = UniteFunctionsUC::getVal($dataset, "queries");
				
				if(empty($queries))
					continue;
				
				$firstQuery = UniteFunctionsUC::getFirstNotEmptyKey($queries);
				$queries = array_flip($queries);
				
				$queries["---Not Selected---"] = "";
				
				$settingName = "dataset_{$type}_query";
				$settings->addSelect($settingName, $queries, __("Choose Query", BLOXBUILDER_TEXTDOMAIN), $firstQuery ,array("description"=>"select the dataset query"));
				$settings->addControl("dataset_type", $settingName, "show", $type);
			}
			
		}
		
		
		$objOutput = new UniteSettingsOutputWideUC();
		$objOutput->init($settings);
		$objOutput->draw("dataset_param_settings", false);
	}
	
	
	/**
	 * put addonpicker addon
	 */
	private function putAddonPickerParam(){
		
		$arrTypes = UniteCreatorAddonType::getAddonTypesForAddonPicker();
		$firstType = UniteFunctionsUC::getFirstNotEmptyKey($arrTypes);
		$arrTypes = array_flip($arrTypes);
		
		$settings = new UniteCreatorSettings();

		$settings->addSelect("addon_type", $arrTypes, __("Choose Addon Type", BLOXBUILDER_TEXTDOMAIN), $firstType ,array("description"=>"select the addon type"));
		
		$objOutput = new UniteSettingsOutputWideUC();
		$objOutput->init($settings);
		$objOutput->draw("addonpicker_param_settings", false);
				
	}
	
	private function a___________________DROPDOWN_PARAM________________(){}
	
	
	/**
	 * put dropdown items table
	 */
	private function putDropdownItems(){
		?>
				<table data-inputtype="table_dropdown" class='uc-table-dropdown-items uc-table-dropdown-full'>
					<thead>
						<tr>
							<th></th>
							<th width="100px"><?php _e("Item Text", BLOXBUILDER_TEXTDOMAIN)?></th>
							<th width="100px"><?php _e("Item Value", BLOXBUILDER_TEXTDOMAIN)?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><div class='uc-dropdown-item-handle'></div></td>
							<td><input type="text" value="" class='uc-dropdown-item-name'></td>
							<td><input type="text" value="" class='uc-dropdown-item-value'></td>
							<td>
								<div class='uc-dropdown-icon uc-dropdown-item-delete' title="<?php _e("Delete Item", BLOXBUILDER_TEXTDOMAIN)?>"></div>
								<div class='uc-dropdown-icon uc-dropdown-item-add' title="<?php _e("Add Item", BLOXBUILDER_TEXTDOMAIN)?>"></div>
								<div class='uc-dropdown-icon uc-dropdown-item-default uc-selected' title="<?php _e("Default Item", BLOXBUILDER_TEXTDOMAIN)?>"></div>
							</td>
						</tr>
					</tbody>
				</table>
		
		<?php 
	}
	
	
	/**
	 * put select related dropdown
	 */
	private function putDropdownSelectRelated($selectSelector, $valueText = null, $putText = null){
		
		$valueTextOutput = __("Attribute Value", BLOXBUILDER_TEXTDOMAIN);
		$putTextOutput = __("Html Output", BLOXBUILDER_TEXTDOMAIN);
		
		if(!empty($valueText))
			$valueTextOutput = $valueText;
		
		if(!empty($putText))
			$putTextOutput = $putText;
		
		?>
				<table data-inputtype="table_select_related" class='uc-table-dropdown-items uc-table-dropdown-simple uc-table-select-related' data-relateto="<?php echo $selectSelector?>">
					<thead>
						<tr>
							<th><?php echo $valueTextOutput?></th>
							<th><?php echo $putTextOutput?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
		<?php 
	}
	
	
	private function a___________________VARIABLE_PARAMS________________(){}
	
	
	/**
	 * put item variable fields
	 */
	private function putVarItemSimpleFields(){
		
		$checkboxFirstID = "uc_check_first_varitem_".$this->type;
		$checkboxLastID = "uc_check_last_varitem_".$this->type;
		
		?>
			
			<div class="unite-inputs-label">
				<?php _e("Default Value", BLOXBUILDER_TEXTDOMAIN)?>:
			</div>
			
			<input type="text" name="default_value" value="" class="uc_default_value">
			
			<a class="uc-link-add" data-addto-selector=".uc_default_value" data-addtext="%numitem%" href="javascript:void(0)"><?php _e("Add Numitem", BLOXBUILDER_TEXTDOMAIN)?></a>
			
			<div class="unite-inputs-label mtop_5 mbottom_5">
				
				<input id="<?php echo $checkboxFirstID?>" type="checkbox" name="enable_first_item" class="uc-control" data-controlled-selector=".uc_section_first">
				
				<label for="<?php echo $checkboxFirstID?>">
				<?php _e("Value for First Item", BLOXBUILDER_TEXTDOMAIN)?>:
				</label>
			</div>
			
			<div class="uc_section_first" style="display:none">
				
				<input type="text" name="first_item_value" value="" class="uc_first_item_value">
				
				<a class="uc-link-add" data-addto-selector=".uc_first_item_value" data-addtext="%numitem%" href="javascript:void(0)"><?php _e("Add Numitem", BLOXBUILDER_TEXTDOMAIN)?></a>
				
			</div>
			
			<div class="unite-inputs-label mtop_5 mbottom_5">
				
				<input id="<?php echo $checkboxLastID?>" type="checkbox" name="enable_last_item" class="uc-control" data-controlled-selector=".uc_section_last">
				
				<label for="<?php echo $checkboxLastID?>">
				<?php _e("Value for Last Item", BLOXBUILDER_TEXTDOMAIN)?>:
				</label>
			</div>
			
			<div class="uc_section_last" style="display:none">
				
				<input type="text" name="last_item_value" value="" class="uc_last_item_value" >
				
				<a class="uc-link-add" data-addto-selector=".uc_last_item_value" data-addtext="%numitem%" href="javascript:void(0)"><?php _e("Add Numitem", BLOXBUILDER_TEXTDOMAIN)?></a>
							
			</div>
			
			<div class="unite-dialog-description-right">
				* <?php _e("The %numitem% is 1,2,3,4... numbers serials", BLOXBUILDER_TEXTDOMAIN)?>
			</div>
			
		<?php
	}
	
	
	/**
	 * put fields of item params related variable
	 * type: item / main
	 */
	private function putParamsRelatedFields($type = "main"){
		
		$title = __("Select Main Attribute", BLOXBUILDER_TEXTDOMAIN);
		$source = "main";
		
		if($type == "item"){
			$title = __("Select Item Attribute", BLOXBUILDER_TEXTDOMAIN);
			$source = "item";
		}
		
		?>
		
		<div class="unite-inputs-label-inline-free ptop_5" >
			<?php echo $title?>:
		</div>
		
		<select class="uc-select-param uc_select_param_name" data-source="<?php echo $source?>" name="param_name"></select>
		
		<div class="unite-inputs-sap"></div>
		
		<div class="uc-dialog-param-min-height">
		
		<?php $this->putDropdownSelectRelated(".uc_select_param_name");?>
		
		</div>
		
		<?php HelperHtmlUC::putDialogControlFieldsNotice() ?>
		
		<?php
		
	}
	
	/**
	 * put GET query string params
	 */
	private function putGetParamFields(){
		
		$text = __("This parameter will go from GET query string", BLOXBUILDER_TEXTDOMAIN);
		
		?>			
			<div class="unite-inputs-label"><?php echo $text?>:</div>
		<?php 
		
		$objSettings = new UniteCreatorSettings();
		
		$arrSanitize = UniteFunctionsUC::getArrSanitizeTypes();		
		$firstType = UniteFunctionsUC::getFirstNotEmptyKey($arrSanitize);
		$arrSanitize = array_flip($arrSanitize);
		
		$objSettings->addSelect("sanitize_type", $arrSanitize, __("Sanitize Type", BLOXBUILDER_TEXTDOMAIN), $firstType);
		$objSettings->addTextBox("default_value", "", __("Default Value", BLOXBUILDER_TEXTDOMAIN));
		
		$objOutput = new UniteSettingsOutputWideUC();
		$objOutput->init($objSettings);
		$objOutput->draw("get_param_settings", false);
		
	}
	
	
	private function a___________________OUTPUT________________(){}
	
	
	/**
	 * put tab html
	 */
	private function putTab($paramType, $isSelected = false, $isSelect = false){
		
		$tabPrefix = "uc_tabparam_".$this->type."_";
		$contentID = $tabPrefix.$paramType;
		
		//check for duplicates
		if(isset($this->arrContentIDs[$paramType]))
			UniteFunctionsUC::throwError("dialog param error: duplicate tab type: $paramType");
		
		//save content id
		$this->arrContentIDs[$paramType] = $contentID;
		
		$title = UniteFunctionsUC::getVal($this->arrParamsTypes, $paramType);
		if(empty($title))
			UniteFunctionsUC::throwError("Attribute: {$paramType} is not found in param list.");
		
		
		//put tab content
		$class = "uc-tab";
		$selectHtml = "";
		if($isSelected == true){
			$class = "uc-tab uc-tab-selected";
			$selectHtml = "selected='selected' ";
		}
		
		if($isSelect == true):
		?>
			<option <?php echo $selectHtml?> data-type="<?php echo $paramType?>" value="<?php echo $contentID?>" >
				<?php _e($title, BLOXBUILDER_TEXTDOMAIN)?>
			</option>
		<?php
		else:
		?>
			<a href="javascript:void(0)" data-type="<?php echo $paramType?>" data-contentid="<?php echo $contentID?>" class="<?php echo $class?>">
				<?php _e($title, BLOXBUILDER_TEXTDOMAIN)?>
			</a>
		<?php
		endif;
		
	}
	
	
	/**
	 * put param content
	 */
	protected function putParamFields($paramType){
		
		switch($paramType){
			case "uc_textfield":
				$this->putDefaultValueParam(false, "", true);
			break;
			case "uc_number":
				$this->putDefaultValueParam(false, "input-small");
				$this->putNumberUnitSelect();
			break;
			case "uc_radioboolean":
				$this->putRadioBooleanParam();
			break;
			case "uc_textarea":
				$this->putDefaultValueParam(true,"",true);
			break;
			case self::PARAM_EDITOR:
				$this->putDefaultValueParam(true);
			break;
			case "uc_checkbox":
				$this->putRadioYesNo("is_checked", __("Checked By Default", BLOXBUILDER_TEXTDOMAIN), false, "Yes", "No", true);
			break;
			case "uc_dropdown":
				$this->putDropDownItems();
			break;
			case "uc_colorpicker":
				$this->putColorPickerDefault();
			break;
			case self::PARAM_IMAGE:
				$this->putImageParam();
			break;
			case "uc_mp3":
				$this->putMp3Param();
			break;
			case self::PARAM_ICON:
				$this->putDefaultValueParam();
			break;
			case self::PARAM_SHAPE:
				$this->putDefaultValueParam();
			break;
			case self::PARAM_CONTENT:
				$this->putDefaultValueParam(true,"");
			break;
			case self::PARAM_POSTS_LIST:
				$this->putNoDefaultValueText(null, true);
			break;
			case self::PARAM_FORM:
				$this->putFormParam();
			break;
			case self::PARAM_INSTAGRAM:
				$this->putInstagramParam();
			break;
			case self::PARAM_MAP:
				$this->putGoogleMapParam();
			break;
			case self::PARAM_HR:
				$this->putNoDefaultValueText();
			break;
			case self::PARAM_FONT_OVERRIDE:
				$text = __("Use this font override in css tab using special function", BLOXBUILDER_TEXTDOMAIN);
				$this->putNoDefaultValueText($text);
			break;
			//variable params
			case "uc_varitem_simple":
				$this->putVarItemSimpleFields();
			break;
			case "uc_var_paramrelated":
				$this->putParamsRelatedFields("main");
			break;
			case "uc_var_paramitemrelated":
				$this->putParamsRelatedFields("item");
			break;
			case self::PARAM_MENU:
				$this->putMenuParam();
			break;
			case self::PARAM_DATASET:
				$this->putDatasetParam();
			break;
			case self::PARAM_ADDONPICKER:
				$this->putAddonPickerParam();
			break;
			case self::PARAM_VAR_GET:
				$this->putGetParamFields();
			break;
			default:
				UniteFunctionsUC::throwError("Wrong param type, fields not found: $paramType");
			break;
		}
		
	}
	
	
	/**
	 * get texts array
	 */
	private function getArrTexts(){
		
		$arrTexts = array();
		
		switch($this->type){
			case self::TYPE_FORM_ITEM:
				$arrTexts["add_title"] = __("Add Form Item",BLOXBUILDER_TEXTDOMAIN);
				$arrTexts["add_button"] = __("Add Form Item",BLOXBUILDER_TEXTDOMAIN);
				$arrTexts["edit_title"] = __("Edit Form Item",BLOXBUILDER_TEXTDOMAIN);
				$arrTexts["update_button"] = __("Update Form Item",BLOXBUILDER_TEXTDOMAIN);				
			break;
			default:
				$arrTexts["add_title"] = __("Add Attribute",BLOXBUILDER_TEXTDOMAIN);
				$arrTexts["add_button"] = __("Add Attribute",BLOXBUILDER_TEXTDOMAIN);
				$arrTexts["edit_title"] = __("Edit Attribute",BLOXBUILDER_TEXTDOMAIN);
				$arrTexts["update_button"] = __("Update Attribute",BLOXBUILDER_TEXTDOMAIN);				
			break;
		}
		
		$arrTexts = array_merge($arrTexts, $this->option_arrTexts);
		
		return($arrTexts);
	}
	
	
	/**
	 * put dialog tabs
	 */
	private function putTabs(){
		?>
		<div class="uc-tabs uc-tabs-paramdialog">
			<?php 
			
			$firstParam = true;
			foreach($this->arrParams as $paramType){
			
				$this->putTab($paramType, $firstParam);
				$firstParam = false;
			}
			
			?>			
		</div>
		
		<div class="unite-clear"></div>
		
		<?php 
	}
	
	/**
	 * put tabs as dropdown
	 */
	private function putTabsDropdown(){
		?>
		
		<?php _e("Attribute Type: " , BLOXBUILDER_TEXTDOMAIN)?>
		
		<select class="uc-paramdialog-select-type">
			
			<?php
				$firstParam = true;
				foreach($this->arrParams as $paramType){
					$this->putTab($paramType, $firstParam, true);
					$firstParam = false;
				}
			?>
		</select>
		<?php
		
	}
	
	
	/**
	 * output html
	 */
	public function outputHtml(){
		
		$this->validateInited();
		$type = $this->type;
		$dialogID = "uc_dialog_param_".$type;
		
		//fill texts
		$arrTexts = $this->getArrTexts();
		$dataTexts = UniteFunctionsUC::jsonEncodeForHtmlData($arrTexts);
		
		?>
			
			<!-- Dialog Param: <?php echo $type?> -->
			
			<div id="<?php echo $dialogID?>" class="uc-dialog-param uc-dialog-param-<?php echo $type?>" data-texts="<?php echo $dataTexts?>" style="display:none">
				
				<div class="dialog-param-wrapper unite-inputs">
					
					<?php 
						//$this->putTabs() 
						$this->putTabsDropdown();
					?>
					
					<div class="uc-tabsparams-content-wrapper">
					
						<div class="dialog-param-left">
							
							<?php if($this->option_putTitle == true): ?>
							
								<div class="unite-inputs-label">
								<?php _e("Title")?>:
								</div>
								
								<input type="text" class="uc-param-title" name="title" value="">
								
								<div class="unite-inputs-sap"></div>
							
							<?php endif?>
							
							
							<div class="unite-inputs-label">
							<?php _e("Name", BLOXBUILDER_TEXTDOMAIN)?>:
							</div>
							<input type="text" class="uc-param-name" name="name" value="">
							
							
							<?php if($this->option_putDecsription == true):?>
							<div class="unite-inputs-sap"></div>
							
							<div class="unite-inputs-label">
							<?php _e("Description", BLOXBUILDER_TEXTDOMAIN)?>:
							</div>
							
							<textarea name="description"></textarea>
							
							<?php endif?>
							
							<?php if($this->option_putAdminLabel == true):?>
							<div class='uc-dialog-param-admin-label-wrapper'>
								<div class="unite-inputs-sap"></div>
								
								<div class="unite-inputs-label-inline-free">
										<?php _e("Admin Label", BLOXBUILDER_TEXTDOMAIN)?>:
								</div>
								<input type="checkbox" name="admin_label">
								<div class="unite-dialog-description-left"><?php _e("Show attribute content on admin side", BLOXBUILDER_TEXTDOMAIN)?></div>
							</div>
							<?php endif?>
						</div>
						
						
						<div class="dialog-param-right">
							
							<?php 
							
							$firstParam = true;
							foreach($this->arrParams as $paramType):
								
								$tabContentID = UniteFunctionsUC::getVal($this->arrContentIDs, $paramType);
								if(empty($tabContentID))
									UniteFunctionsUC::throwError("No content ID found for param: {$paramType} ");
								
								$addHTML = "";
								$addClass = "uc-content-selected";
								if($firstParam == false){
									$addHTML = " style='display:none'";
									$addClass = "";
								}
								
								$firstParam = false;
								
								?>
								
								<!-- <?php echo $paramType?> fields -->
								
								<div id="<?php echo $tabContentID?>" class="uc-tab-content <?php echo $addClass?>" <?php echo $addHTML?> >
									
									<?php 
									
										$this->putParamFields($paramType);
									
									?>
									
								</div>
								
								<?php 								
								
							endforeach;
							?>
							
							
						</div>
						
						<div class="unite-clear"></div>
					
					</div>	<!-- end uc-tabs-content-wrapper -->
					
					<div class="uc-dialog-param-error unite-color-red" style="display:none"></div>
					
				</div>
				
					
			</div>		
		
		
		<?php 
	}
	
	
	private function a___________________INIT________________(){}
	
	
	/**
	 * init main dialog params
	 */
	public function initMainParams(){
		
		$this->arrParams = array(
			self::PARAM_TEXTFIELD,
			self::PARAM_NUMBER,
			self::PARAM_RADIOBOOLEAN,
			self::PARAM_TEXTAREA,
			self::PARAM_CHECKBOX,
			self::PARAM_DROPDOWN,
			self::PARAM_COLORPICKER,
			self::PARAM_ADDONPICKER,
			self::PARAM_EDITOR,
			self::PARAM_HR,
			self::PARAM_IMAGE,
			self::PARAM_AUDIO,
			self::PARAM_ICON,
			self::PARAM_SHAPE,
			self::PARAM_FONT_OVERRIDE,
			self::PARAM_INSTAGRAM,
		);
		
		//add dataset
		$arrDatasets = $this->objDatasets->getDatasetTypeNames();
		if(!empty($arrDatasets))
			$this->arrParams[] = self::PARAM_DATASET;
		
	}
	
	
	/**
	 * init common variable dialogs
	 */
	private function initVariableCommon(){
		
		$this->option_putAdminLabel = false;
		$this->option_putTitle = false;
		$this->option_arrTexts["add_title"] = __("Add Item Variable",BLOXBUILDER_TEXTDOMAIN);
		$this->option_arrTexts["add_button"] = __("Add Variable",BLOXBUILDER_TEXTDOMAIN);
		$this->option_arrTexts["update_button"] = __("Update Variable",BLOXBUILDER_TEXTDOMAIN);
		$this->option_arrTexts["edit_title"] = __("Edit Variable",BLOXBUILDER_TEXTDOMAIN);
		
	}
		
	
	/**
	 * init variable params
	 */
	private function initVariableMainParams(){
	
		$this->initVariableCommon();
		
		$this->arrParams = array(
				"uc_var_paramrelated",
				self::PARAM_VAR_GET
		);
	
	}
	
	
	/**
	 * init variable item params
	 */
	private function initVariableItemParams(){
	
		$this->initVariableCommon();
		
		$this->arrParams = array(
				"uc_varitem_simple",
				"uc_var_paramrelated",
				"uc_var_paramitemrelated"
		);
		
	}
	
	
	/**
	 * init form item params
	 */
	private function initFormItemParams(){
		
		$objForm = new UniteCreatorForm();
		$this->arrParams = $objForm->getDialogFormParams();
		
		$this->option_putDecsription = false;
		$this->option_allowFontEditCheckbox = false;
	}
	
	
	/**
	 * init the params dialog
	 */
	public function init($type, $addon){
		$this->type = $type;
		
		if(empty($addon))
			UniteFunctionsUC::throwError("you must pass addon");
		
		$this->addon = $addon;
		
		$this->objSettings = new UniteCreatorSettings();
		$this->objDatasets = new UniteCreatorDataset();
		
		
		switch($this->type){
			case self::TYPE_MAIN:
				$this->initMainParams();
			break;
			case self::TYPE_ITEM_VARIABLE:
				$this->initVariableItemParams();
			break;
			case self::TYPE_MAIN_VARIABLE:
				$this->initVariableMainParams();
			break;
			case self::TYPE_FORM_ITEM:
				$this->initFormItemParams();
			break;
			default:
				UniteFunctionsUC::throwError("Wrong param dialog type: $type");
			break;
		}
		
	}
	
	
	
}
