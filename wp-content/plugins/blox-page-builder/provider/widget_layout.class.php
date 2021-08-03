<?php

// no direct access
defined('BLOXBUILDER_INC') or die;


class Blox_WidgetLayout extends WP_Widget {
	
    public function __construct(){
    	
        // widget actual processes
     	$widget_ops = array('classname' => 'widget_blox_layout', 'description' => __('Show Blox Layout On Page') );
        parent::__construct('blox-layout-widget', __('Blox Builder Layout', BLOXBUILDER_TEXTDOMAIN), $widget_ops);
    }

    
    /**
     * 
     * the form
     */
    public function form($instance) {
		
    	$objLayouts = new UniteCreatorLayouts();
    	$arrLayouts = $objLayouts->getArrLayoutsShort(true,array(), GlobalsUC::ADDON_TYPE_LAYOUT_GENERAL);
    	
    	$fieldID = "blox_layout_id";
    	$layoutID = UniteFunctionsUC::getVal($instance, $fieldID);
    	
    	if(empty($arrLayouts)){
    		
    		$urlLayouts = HelperUC::getViewUrl_LayoutsList();
    		
    		$linkCreate = HelperHtmlUC::getHtmlLink($urlLayouts, __("create a layout",BLOXBUILDER_TEXTDOMAIN),"","",true);
    		
    		?>
    		<div style="padding-top:10px;padding-bottom:10px;">
    		<?php echo __("No layouts found, Please ", BLOXBUILDER_TEXTDOMAIN).$linkCreate; ?>
    		</div>
    		<?php }
    	else{
    		$fieldOutputID = $this->get_field_id( $fieldID );
    		$fieldOutputName = $this->get_field_name( $fieldID );
    		
    		$selectLayouts = HelperHtmlUC::getHTMLSelect($arrLayouts, $layoutID,'name="'.$fieldOutputName.'" id="'.$fieldOutputID.'"',true);
    		?>
				<div style="padding-top:10px;padding-bottom:10px;">
				
				<?php _e("Title", BLOXBUILDER_TEXTDOMAIN)?>: 
				&nbsp; <input type="text" id="<?php echo $this->get_field_id( "title" );?>" name="<?php echo $this->get_field_name( "title" )?>" value="<?php echo UniteFunctionsUC::getVal($instance, 'title')?>" />
				
				<br><br>
				
				<?php _e("Choose a Layout", BLOXBUILDER_TEXTDOMAIN)?>: 
				<?php echo $selectLayouts?>
				
				</div>
				
				<br>
    		
    		<?php 
    	}

    }
 
    
    /**
     * 
     * update
     */
    public function update($new_instance, $old_instance) {
    	
        return($new_instance);
    }

    
    /**
     * 
     * widget output
     */
    public function widget($args, $instance) {
    	
    	$title = UniteFunctionsUC::getVal($instance, "title");
		    	
    	$layoutID =  UniteFunctionsUC::getVal($instance, "blox_layout_id");
    	
    	if(empty($layoutID))
    		return(false);
    	    	
    	//widget output
    	$beforeWidget = UniteFunctionsUC::getVal($args, "before_widget");
    	$afterWidget = UniteFunctionsUC::getVal($args, "after_widget");
    	$beforeTitle = UniteFunctionsUC::getVal($args, "before_title");
    	$afterTitle = UniteFunctionsUC::getVal($args, "after_title");
    	
    	echo $beforeWidget;
    	
    	if(!empty($title))
    		echo $beforeTitle.$title.$afterTitle;
    	
    	if(is_numeric($layoutID) == false)
    		_e("no layout selected", BLOXBUILDER_TEXTDOMAIN);
    	else
    		HelperUC::outputLayout($layoutID);
 		
    	echo $afterWidget;
    }
 
    
}


?>