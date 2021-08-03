<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');


class UniteCreatorLibraryView{
	
	protected $showButtons = true;
	protected $showHeader = true;
	
	protected $arrPages = array();
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$this->init();
		$this->putHtml();
	}
	
	/**
	 * init the pages
	 */
	protected function init(){
		
		$urlAddons = helperUC::getViewUrl_Addons();
		$urlDividers = helperUC::getViewUrl_Addons(GlobalsUC::ADDON_TYPE_SHAPE_DEVIDER);
		$urlShapes = helperUC::getViewUrl_Addons(GlobalsUC::ADDON_TYPE_SHAPES);
		$urlBGAddons = helperUC::getViewUrl_Addons(GlobalsUC::ADDON_TYPE_BGADDON);
		
		
		$urlSections = HelperUC::getViewUrl_LayoutsList(array(), GlobalsUC::ADDON_TYPE_LAYOUT_SECTION);
		//$urlPageTemplates = HelperUC::getViewUrl_LayoutsList(array(), GlobalsUC::ADDON_TYPE_LAYOUT_PAGE_TEMPLATE);
		
		$textAddons = __("My Addons", BLOXBUILDER_TEXTDOMAIN);
		$textDividers = __("Dividers", BLOXBUILDER_TEXTDOMAIN);
		$textShapes = __("Shapes", BLOXBUILDER_TEXTDOMAIN);
		$textSection = __("Sections", BLOXBUILDER_TEXTDOMAIN);
		$textPageTemplates = __("Page Templates", BLOXBUILDER_TEXTDOMAIN);
		$textBackgroundAddons = __("Background Addons", BLOXBUILDER_TEXTDOMAIN);
		
		$defaultIcon = "puzzle-piece";
		
		$this->addPage($urlAddons, $textAddons, $defaultIcon);
		$this->addPage($urlBGAddons, $textBackgroundAddons, $defaultIcon);
		$this->addPage($urlDividers, $textDividers, "map");
		$this->addPage($urlShapes, $textShapes, "map");
		$this->addPage($urlSections, $textSection, $defaultIcon);
		
		//$this->addPage($urlPageTemplates, $textPageTemplates, $defaultIcon);
		
	}
	
	
	/**
	 * get header text
	 * @return unknown
	 */
	protected function getHeaderText(){
		$headerTitle = __("My Library", BLOXBUILDER_TEXTDOMAIN);
		return($headerTitle);
	}
	
	/**
	 * add page
	 */
	protected function addPage($url, $title, $icon){
		
		$this->arrPages[] = array(
			"url"=>$url,
			"title"=>$title,
			"icon"=>$icon);
		
	}
	
	/**
	 * show buttons panel
	 */
	protected function putHtmlButtonsPanel(){
		
		$urlLayouts = HelperUC::getViewUrl_LayoutsList();
		$urlAddons = HelperUC::getViewUrl_Addons();
		
		?>
		<div class="uc-buttons-panel unite-clearfix">
			<a href="<?php echo $urlLayouts?>" class="unite-float-right mleft_20 unite-button-secondary"><?php HelperUC::putText("my_layouts")?></a>
			<a href="<?php echo $urlAddons?>" class="unite-float-right mleft_20 unite-button-secondary"><?php _e("My Addons", BLOXBUILDER_TEXTDOMAIN)?></a>
			
		</div>
		
		<?php 
	}
	
	
	/**
	 * put pages html
	 */
	protected function putHtmlPages(){
		
		if($this->showHeader == true){
			
			$headerTitle = $this->getHeaderText();
			
			require HelperUC::getPathTemplate("header");
		}else
			require HelperUC::getPathTemplate("header_missing");
		
		if($this->showButtons == true)
			$this->putHtmlButtonsPanel();
		
		?>
		
		<div class="content_wrapper unite-content-wrapper">
			
		
		<ul class='uc-list-pages-thumbs'>
		<?php 
		foreach($this->arrPages as $page){
			
			$url = $page["url"];
			$icon = $page["icon"];
			
			if(empty($icon))
				$icon = "angellist";
			
			$title = $page["title"];
				
			?>
			<li>				
				<a href="<?php echo $url?>">
					<i class="fa fa-<?php echo $icon?>"></i>
					<?php echo $title?>
				</a>
			</li>
			<?php 
		}
		?>
		</ul>
		
		</div>
		
		<?php 
		
	}
	
	
	/**
	 * constructor
	 */
	protected function putHtml(){
		
		$this->putHtmlPages();
		
	}

}

$pathProviderAddons = GlobalsUC::$pathProvider."views/library.php";

if(file_exists($pathProviderAddons) == true){
	require_once $pathProviderAddons;
	new UniteCreatorLibraryViewProvider();
}
else{
	new UniteCreatorLibraryView();
}

