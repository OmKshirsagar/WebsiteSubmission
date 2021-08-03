<?php
/**
 * @package Blox Page Builder
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('BLOXBUILDER_INC') or die('Restricted access');

class UniteCreatorActivationView extends UniteElementsBaseUC{

	const CODE_TYPE_ACTIVATION = "activation";
	const CODE_TYPE_ENVATO = "envato";
	
	protected $urlPricing;
	protected $urlSupport;
	protected $textGoPro, $textAndTemplates, $textPasteActivationKey, $textPlaceholder;
	protected $textLinkToBuy, $textDontHave, $textActivationFailed, $textActivationCode;
	protected $codeType = self::CODE_TYPE_ACTIVATION;
	protected $isExpireEnabled = true, $textSwitchTo;
	protected $writeRefreshPageMessage = true;
	protected $textDontHaveLogin, $textLinkToLogin, $urlLogin;
	
	/**
	 * init the variables
	 */
	public function __construct(){
		
		$this->urlPricing = GlobalsUC::URL_BUY;
		$this->urlSupport = GlobalsUC::URL_SUPPORT;
		
		$this->textGoPro = __("GO PRO", BLOXBUILDER_TEXTDOMAIN);
		
		$this->textAndTemplates = __("+100 page templates and +50 section designs", BLOXBUILDER_TEXTDOMAIN);
		
		$this->textPasteActivationKey = __("Paste your activation key here", BLOXBUILDER_TEXTDOMAIN);
		
		$this->textPlaceholder = "xxxx-xxxx-xxxx-xxxx";
		$this->textLinkToBuy = __("View our pricing plans", BLOXBUILDER_TEXTDOMAIN);
		
		$this->textDontHave = __("Don't have a pro activation key?", BLOXBUILDER_TEXTDOMAIN);

		$this->textDontHaveLogin = __("If you already purchased, get the key from my account?", BLOXBUILDER_TEXTDOMAIN);
		$this->textLinkToLogin = __("Go to My Account", BLOXBUILDER_TEXTDOMAIN);
		$this->urlLogin = "http://my.unitecms.net";
		
		$this->textActivationFailed = __("You probably got your activation code wrong", BLOXBUILDER_TEXTDOMAIN);
		
	}
	
	/**
	 * put pending activation html
	 */
	public function putPendingHTML(){
		?>
		You are using free version of <b>Blox Page Builder</b>. The pro version will be available for sale in codecanyon.net within 5 days.
		<br>
		<br>
		Please follow the plugin updates, and the pro version activation will be revealed.
		<br>
		<br>
		For any quesiton you can turn to: <b>support@blox-builder.com</b>
		<?php 
	}
	
	/**
	 * put activation html
	 */
	public function putActivationHtml(){
		
		?>
		   <div class="uc-activation-view">
		   	   
	           <div class="uc-popup-container uc-start">
	                <div class="uc-popup-content">
	                    <div class="uc-popup-holder">
	                        <div class="xlarge-title"><?php echo $this->textGoPro?></div>
	                        
	                        <div class="popup-text"><?php _e("Unleash access to +700 addons", BLOXBUILDER_TEXTDOMAIN)?>,<br> <?php echo $this->textAndTemplates?></div>
	                        <div class="popup-form">
	                                <label><?php echo $this->textPasteActivationKey?>:</label>
	                                <input id="uc_activate_pro_code" type="text" placeholder="<?php echo $this->textPlaceholder?>" value="">
	                                
	                                <div class="uc-activation-section-wrapper">
	                                
	                                <input id="uc_button_activate_pro" type="button" class='uc-button-activate' data-codetype="<?php echo $this->codeType?>" value="Activate Blox Pro">
	                                
	                                <div id="uc_loader_activate_pro" class="uc-loader-activation" style='display:none'>
										<span class='loader_text'>	                                	
	                                		<?php _e("Activating", BLOXBUILDER_TEXTDOMAIN)?>...
	                                	</span>
	                                </div>
		                                
	                                </div>
	                        </div>
	                        
	                        <div class="bottom-text">
	                        	<?php echo $this->textDontHave?>
	                        	<br>
	                        	<a href="<?php echo $this->urlPricing?>" target="_blank" class="blue-text"><?php echo $this->textLinkToBuy?></a>
	                        </div>
	                        
	                        <?php if(!empty($this->textDontHaveLogin)):?>
	                        
	                        <div class="bottom-text">
	                        	<?php echo $this->textDontHaveLogin?>
	                        	<br>
	                        	<a href="<?php echo $this->urlLogin?>" target="_blank" class="blue-text"><?php echo $this->textLinkToLogin?></a>
	                        </div>
	                        
	                        <?php endif?>
	                        
							<?php if(!empty($this->textSwitchTo)):?>
	                        <div class="bottom-text">
	                        	<?php echo $this->textSwitchTo?><br>
	                        </div>
	                        <?php endif?>
	                        
	                	</div>
	            	</div>
	            </div>
	            
	            <!-- failed dialog -->
	            
	            <div class="uc-popup-container uc-fail hidden">
	                <div class="uc-popup-content">
	                    <div class="uc-popup-holder">
	                        <div class="large-title"><?php _e("Ooops", BLOXBUILDER_TEXTDOMAIN)?>.... <br><?php _e("Activation Failed", BLOXBUILDER_TEXTDOMAIN)?> :(</div>
	                        <div class="popup-error"></div>
	                        <div class="popup-text"><?php echo $this->textActivationFailed?> <br>to try again <a id="activation_link_try_again" href="javascript:void(0)">click here</a></div>
	                        <div class="bottom-text"><?php _e("or contact our",BLOXBUILDER_TEXTDOMAIN)?> <a href="<?php echo $this->urlSupport?>" target="_blank"><?php _e("support center", BLOXBUILDER_TEXTDOMAIN)?></a></div>
	                    </div>
	                </div>
	            </div>
	            
	            <!-- activated dialog -->
	            
	            <div class="uc-popup-container uc-activated hidden">
	                <div class="uc-popup-content">
	                    <div class="uc-popup-holder">
	                        <div class="xlarge-title"><?php _e("Hi Five", BLOXBUILDER_TEXTDOMAIN)?>!</div>
	                        
	                        <?php if($this->isExpireEnabled == true):?>
	                        	<div class="popup-text small-padding"><?php _e("Your pro account is activated for the next", BLOXBUILDER_TEXTDOMAIN)?></div>
		                        <div class="days"></div>
		                        <span><?php _e("DAYS", BLOXBUILDER_TEXTDOMAIN)?></span>
		                        <br><br>
		                        
		                        <?php if($this->writeRefreshPageMessage == true):?>
		                        <a href="javascript:location.reload()" class="btn"><?php _e("Refresh page to View Your Pro Catalog", BLOXBUILDER_TEXTDOMAIN)?></a>
		                        <?php endif?>
		                        
	                        <?php else:?>
	                        	
	                        	<div class="popup-text small-padding"><?php _e("Your pro account is activated lifetime for this site",BLOXBUILDER_TEXTDOMAIN)?>!</div>
		                       	
	                        	<div class="popup-text small-padding"><?php _e("Thank you for purchasing from us and good luck", BLOXBUILDER_TEXTDOMAIN)?>!</div>
	                        	
	                        <?php endif?>
	                        
	                    </div>
	                </div>
	            </div>
		</div>
		
		<?php 
	}
	
	/**
	 * put deactivate html
	 */
	public function putHtmlDeactivate(){
		?>
		<h2><?php _e("This pro version is active!", BLOXBUILDER_TEXTDOMAIN)?></h2>
		
		<a href="javascript:void(0)" class="uc-link-deactivate unite-button-primary"><?php _e("Deactivate Pro Version", BLOXBUILDER_TEXTDOMAIN)?></a>
		
		<?php 
	}
	
	/**
	 * put initing JS
	 */
	public function putJSInit(){
		?>
		
		<script>

		jQuery("document").ready(function(){

			if(!g_ucAdmin)
				var g_ucAdmin = new UniteAdminUC();
			
			g_ucAdmin.initActivationDialog(true);
			
			
		});
		
		</script>
		
		<?php 
	}
	
	/**
	 * put activation HTML
	 */
	public function putHtmlPopup(){
		
		$title = __("Activate Your Pro Account", BLOXBUILDER_TEXTDOMAIN);
		
		?>
           <div class="activateProDialog" title="<?php echo $title?>" style="display:none">
           
           		<?php $this->putActivationHtml(true) ?>
            	
            </div>
		
		<?php 		
	}
	
}

