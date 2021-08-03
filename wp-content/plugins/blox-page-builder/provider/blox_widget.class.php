<?php

defined('BLOXBUILDER_INC') or die('Restricted access');


class BloxPlatform_Widget extends WP_Widget {
	
	
    public function __construct(){
    	
        // widget actual processes
     	$widget_ops = array('classname' => 'widget_blox', 'description' => __('Put the blox addon widget') );
        parent::__construct('blox-widget', __('Blox Addon Test', UNITEGALLERY_TEXTDOMAIN), $widget_ops);
    }

    
    /**
     * 
     * the form
     */
    public function form($instance) {
				
			$field = "bloxtest";
			
			$fieldID = $this->get_field_id( $field );
			$fieldName = $this->get_field_name( $field );

			
			?>
				<div style="padding-top:10px;padding-bottom:10px;">
				
				<?php _e("Title", UNITEGALLERY_TEXTDOMAIN)?>: 
				<input type="text" id="<?php echo $this->get_field_id( "title" );?>" name="<?php echo $this->get_field_name( "title" )?>" value="<?php echo UniteFunctionsUC::getVal($instance, 'title')?>" />
					
				put some form here
												
				</div>
			<?php
			
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
    	
    	$title = UniteFunctionsUG::getVal($instance, "title");
    	
    	if(empty($galleryID))
    		return(false);
    	    	
    	//widget output
    	$beforeWidget = UniteFunctionsUG::getVal($args, "before_widget");
    	$afterWidget = UniteFunctionsUG::getVal($args, "after_widget");
    	$beforeTitle = UniteFunctionsUG::getVal($args, "before_title");
    	$afterTitle = UniteFunctionsUG::getVal($args, "after_title");
    	
    	echo $beforeWidget;
    	
    	if(!empty($title))
    		echo $beforeTitle.$title.$afterTitle;
    	
    	dmp("output widget");
    	
    	echo $afterWidget;
    	
    }
 
    
}


?>