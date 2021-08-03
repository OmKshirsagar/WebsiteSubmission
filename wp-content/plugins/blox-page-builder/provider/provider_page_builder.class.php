<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
// no direct access
defined('BLOXBUILDER_INC') or die;

class UniteCreatorPageBuilder extends UniteCreatorPageBuilderWork{

	protected $pageTemplate = null;
	protected $post;
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		parent::__construct();
	}
	
	
	/**
	 * init post
	 */
	private function initPost(){
		
		if(empty($this->layoutID))
			return(false);
					
		$this->post = get_post($this->layoutID);
		
	}
	
	
	/**
	 * init page template
	 */
	private function initPageTemplate(){
		
		if(!empty($this->pageTemplate))
			return(false);
		
		if(empty($this->layoutID))
			return(false);

		$this->initPost();
		
		if(empty($this->post))
			return(false);
		
		$this->pageTemplate = UniteFunctionsWPUC::getPostPageTemplate($this->post);
		
	}
	
	
	/**
	 * get layout edit url
	 */
	protected function getUrlInnerLayoutEdit(){
		
		$this->initPageTemplate();

		$postType = $this->post->post_type;
		
		if($postType == "uc_layout")
			return parent::getUrlInnerLayoutEdit();
		
		if($postType == GlobalsProviderUC::POST_TYPE_LAYOUT)
			return parent::getUrlInnerLayoutEdit();
		
		if($this->pageTemplate == GlobalsProviderUC::PAGE_TEMPLATE_LANDING_PAGE)
			return parent::getUrlInnerLayoutEdit();
		
		//if not set that it's landing page, assume that it's blox page
		$url = UniteFunctionsWPUC::getPermalink($this->post);
		$params = "bloxedit=".$this->layoutID;
		
		$url = UniteFunctionsUC::addUrlParams($url, $params);
		
		return($url);
	}
	
	
	/**
	 * init inner by layout
	 */
	public function initInner(UniteCreatorLayout $objLayout){
				
		parent::initInner($objLayout);
						
	}
	
	
	/**
	 * init outer by layout
	 */
	public function initOuter($objLayout){
		
		parent::initOuter($objLayout);
		
		//$this->initPost();
		
		//disable shortcode output in the top panel
		$this->objActionsPanel->setPutShorcodeSection(false);
		
	}
	
}