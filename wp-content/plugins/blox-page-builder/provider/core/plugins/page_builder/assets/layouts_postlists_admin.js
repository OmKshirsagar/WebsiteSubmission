
function LayoutsPostsListPageAdmin(postType){
	
	var t = this;
	var g_postType = postType, g_singleTitle;
	
	
	/**
	 * add import button
	 */
	function addImportButton(){
		
		var objPageTitle = jQuery(".wrap .page-title-action");
		if(objPageTitle.length == 0)
			return(false);
		
		var html = "<a id='uc_button_import_layout' class='page-title-action uc-button-import' href='javascript:void(0)'>Import "+g_singleTitle+" Page</a>";
		html += "<a id='uc_button_import_layout_from_catalog' class='page-title-action uc-button-catalog' href='javascript:void(0)'>Import From Catalog</a>";
		
		objPageTitle.after(html);
	}
	
	
	/**
	 * init the page
	 */
	this.init = function(singleTitle){
		
		g_singleTitle = "Blox";
		if(singleTitle)
			g_singleTitle = singleTitle;
				
		addImportButton();
		
		var objListAdmin = new UniteCreatorAdmin_LayoutsList();
		
		var addParams = {};
		addParams["post_type"] = g_postType;
		
		objListAdmin.initImportLayoutDialog(addParams);
		objListAdmin.initImportPageCatalog(addParams);
				
		//init layout exporter
		jQuery(".uc_button_export").click(objListAdmin.onExportClick);
		jQuery(".uc_button_duplicate").click(objListAdmin.onDuplicateClick);
	}
	
	
}