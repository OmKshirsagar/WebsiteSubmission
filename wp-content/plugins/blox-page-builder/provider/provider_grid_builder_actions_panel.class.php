<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorGridBuilderActionsPanel extends UniteCreatorGridBuilderActionsPanelWork{
	
	/**
	 * get exit url
	 */
	protected function getUrlBack(){
		
		$urlEditPost = get_edit_post_link( $this->layoutID);
		
		return($urlEditPost);
	}
	
	
	/**
	 * get preview page url
	 */
	protected function getUrlPreview(){
		
		if($this->isEditMode == true){
			$post = get_post($this->layoutID);
			$postType = $post->post_type;
			
			if(!empty($post) && $postType != "uc_layout"){
				$urlPreview = UniteFunctionsWPUC::getPermalink($post);
				return($urlPreview);
			}
		}
		
		$urlPreview = parent::getUrlPreview();
		
		if(strpos($urlPreview, "ucwindow") !== false)
			$urlPreview .= "&superclear=true";
		
		return($urlPreview);
	}
	
	
}