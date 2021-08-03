<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

/**
 * actions
 */
class UniteCreatorActions extends UniteCreatorActionsWork{
	
	
	/**
	 * on update layout response, function for override
	 */
	protected function onUpdateLayoutResponse($response){
		
				
		$isUpdate = $response["is_update"];
		
		//create
		if($isUpdate == false){
			
			$layoutID = $response["layout_id"];
			
			$urlRedirect = HelperUC::getViewUrl_Layout($layoutID);
			
			HelperUC::ajaxResponseSuccessRedirect(__("Layout Created, redirecting...", BLOXBUILDER_TEXTDOMAIN), $urlRedirect);
			
		}else{
			//update
			
			$message = __("Updated", BLOXBUILDER_TEXTDOMAIN);
			
			HelperUC::ajaxResponseSuccess($message);
		}
		
	}
	
	
}