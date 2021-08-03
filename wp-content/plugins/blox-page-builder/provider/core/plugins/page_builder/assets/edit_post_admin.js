
function BloxEditPostAdmin(){
	
	var t = this;
	if(!g_ucAdmin)
		var g_ucAdmin = new UniteAdminUC();
	var g_postID, g_objListAdmin, g_selectPageTemplate, g_inputBloxPage;
	var g_postType;
	var g_temp = {
			is_gutenberg: false
	};
	
	
	/**
	 * set blox page
	 */
	function setBloxPage(){
		var objBody = jQuery("body");
		
		objBody.addClass("uc-blox-page");
				
		g_inputBloxPage.val("1");
		
		if(g_temp.is_gutenberg == true)
			g_ucAdmin.ajaxRequest("update_post_blox_page", {postid: g_postID});
	}
	
	
	/**
	 * unset blox page
	 */
	function unsetBloxPage(){
		var objBody = jQuery("body");
		
		objBody.removeClass("uc-blox-page");
		
		g_inputBloxPage.val("");
		
		if(g_temp.is_gutenberg == true)
			g_ucAdmin.ajaxRequest("update_post_wp_page", {postid: g_postID});
	}
	
	/**
	 * tells if it's blox page or not
	 */
	function isBloxPage(){
		
		var objBody = jQuery("body");
		if(objBody.hasClass("uc-blox-page"))
			return(true);
		
		return(false);
	}
	
	
	/**
	 * on page template change, set modes
	 */
	function onPageTemplateChange(){
		
		var template = g_selectPageTemplate.val();
		var objBody = jQuery("body");
		
		if(template == "blox_landing_page"){
			objBody.addClass("uc-blox-landing-page");
			
			setBloxPage();
		}else{
			objBody.removeClass("uc-blox-landing-page");
		}
		
	}
	
	
	/**
	 * init the page template select
	 */
	function initPageTemplateSelect(){
		
		g_selectPageTemplate = jQuery("#page_template");
				
		if(g_selectPageTemplate.length == 0){
			g_selectPageTemplate = null;
			return(false);
		}
		
		g_selectPageTemplate.change(onPageTemplateChange);
		
	}
	
	
	/**
	 * init import layout
	 */
	function initImportLayout(){
		
		var params = {};
		params["post_type"] = g_postType;
		params["redirect_to_wp_page"] = true;
		
		g_objListAdmin.initImportLayoutDialog(params, g_postID);
		
		params["layout_id"] = g_postID;
		
		g_objListAdmin.initImportPageCatalog(params);
		
	}
	
	
	/**
	 * on export layout click
	 */
	function onExportLayoutClick(event){
		
		g_objListAdmin.onExportClick(event, g_postID);
		
	}
	
	
	/**
	 * on duplicate page click
	 */
	function onDuplicateClick(event){
		
		var objButton = jQuery(this);
		
		var addParams = {
				redirect_to_wp_page: true
		};
		
		g_objListAdmin.onDuplicateClick(event, g_postID, objButton, addParams);
		
	}
	
	/**
	 * on edit with blox click, go to blox page mode
	 */
	function onEditWithBloxButtonClick(){
		
		setBloxPage();
	}
	
	
	/**
	 * on blox edit click
	 */
	function onEditWithBloxClick(){
		
		var objButton = jQuery(this);
		var urlEdit = objButton.data("link");
		
		var postTitle = jQuery("#title").val();
		if(postTitle){
			urlEdit += "&title=" + encodeURIComponent(postTitle);
		}
		
		location.href = urlEdit;
	}
	
	
	/**
	 * init events
	 */
	function initEvents(){
		
		jQuery("#uc_button_export_layout").click(onExportLayoutClick);
		jQuery("#uc_button_duplicate").click(onDuplicateClick);
		
		jQuery("#uc_button_to_blox_page").click(onEditWithBloxButtonClick);
		
		jQuery("#uc_button_return_to_wp").click(unsetBloxPage);
		
		jQuery("#uc_button_edit_with_blox").click(onEditWithBloxClick);
		
	}
	
	
	/**
	 * check if gutenberg
	 */
	function isGutenberg(){
		
		var objEditor = jQuery("#editor");
		
		if(objEditor.hasClass("block-editor__container") == true)
			return(true);
		
		if(objEditor.hasClass("gutenberg__editor") == true)
			return(true);
		
		var objBody = jQuery("body");
		if(objBody.hasClass("gutenberg-editor-page") == true)
			return(true);
				
		return(false);
	}
	
	
	/**
	 * init gutenberg related
	 */
	function initGutenbergRelated(){
				
		if(g_temp.is_gutenberg == false)
			return(false);
		
		var objHeaderToolbar = jQuery(".edit-post-header-toolbar");
		
		var objHeaderToolbarLeft = jQuery(".edit-post-header");
		
		if(objHeaderToolbarLeft.length)
			objHeaderToolbar = objHeaderToolbarLeft;
					
		if(objHeaderToolbar.length == 0)
			trace("editor header toolber not found");
		
		//objHeaderToolbar.append("button");
		
		var objButton = jQuery("#uc_button_to_blox_page");
		
		//init buttons - put to header
		var objTemplateWrapper = jQuery("#uc_blox_buttons_template");
		
		var buttonsHTML = objTemplateWrapper.html();			
		buttonsHTML = jQuery.trim(buttonsHTML);
		
		objHeaderToolbar.append(buttonsHTML);
					
		objTemplateWrapper.remove();
					
		
		//init blox wrapper - detach from it's place and attach to right place
		var objWrapper = jQuery("#uc_edit_blox_page_wrapper");
		
		if(objWrapper.length == 0)
			trace("blox wrapper not found");
		
		objWrapper.detach();
		
		var objEditorLayout = jQuery(".editor-block-list__layout").parent();
		
		if(objEditorLayout.length == 0){
			objEditorLayout = jQuery(".block-editor-editor-skeleton__content");
			if(objEditorLayout.length == 0)
				objEditorLayout = jQuery(".interface-interface-skeleton__content");
			
			if(objEditorLayout.length == 0)
				trace("blox error - no layout found");
		}
		
		objEditorLayout.append(objWrapper);
		
		objWrapper.removeClass("uc-hidden");
		
	}
	
	
	/**
	 * init all objects
	 */
	function initAllObjects(){
		
		initPageTemplateSelect();
		
		initImportLayout();
		
		initEvents();
	}
	
	
	/**
	 * init the edit post page
	 */
	this.init = function(){
				
		g_temp.is_gutenberg = isGutenberg();
				
		//init post ID
		g_postID = jQuery("#post_ID").val();
		g_inputBloxPage = jQuery("#blox_page");
		if(g_inputBloxPage.length == 0)
			return(false);
		
		g_postType = jQuery("#uc_edit_blox_page_wrapper").data("posttype");
		
		g_objListAdmin = new UniteCreatorAdmin_LayoutsList();
		
		if(g_temp.is_gutenberg){
			
			setTimeout( function() {
				initGutenbergRelated();
				initAllObjects();
			}, 10 );
		}else
			initAllObjects();
		
	}
	
}


//init
jQuery(document).ready(function(){
	var objBloxEditAdmin = new BloxEditPostAdmin();
	objBloxEditAdmin.init();
});