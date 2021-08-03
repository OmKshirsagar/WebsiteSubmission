<?php 

try{
	
	//-------------------------------------------------------------
	
	//load core plugins
	
	$pathCorePlugins = dirname(__FILE__)."/plugins/";
			
	$pathPageBuilderPlugin = $pathCorePlugins."page_builder/plugin.php";
	require_once $pathPageBuilderPlugin;

	//$pathUnlimitedElementsPlugin = $pathCorePlugins."unlimited_elements/plugin.php";
	//require_once $pathUnlimitedElementsPlugin;
	
	
	//$pathCreateAddonsPlugin = $pathCorePlugins."create_addons/plugin.php";
	//require_once $pathCreateAddonsPlugin;
	
	
	if(is_admin()){		//load admin part
		
		do_action(GlobalsProviderUC::ACTION_RUN_ADMIN);
		
		
		/*
		require_once $currentFolder."/unitecreator_admin.php";
		require_once GlobalsUC::$pathProvider . "provider_admin.class.php";
		require_once GlobalsUC::$pathProvider . "core/provider_core_admin.class.php";
		
		new UniteProviderCoreAdminUC_Blox($mainFilepath);
		
		*/
	}else{		//load front part
		
		/*
		require_once GlobalsUC::$pathProvider . "provider_front.class.php";
		require_once GlobalsUC::$pathProvider . "core/provider_core_front.class.php";
		
		new UniteProviderCoreFrontUC_Blox($mainFilepath);
		*/
		do_action(GlobalsProviderUC::ACTION_RUN_FRONT);
		
	}

	
	}catch(Exception $e){
		$message = $e->getMessage();
		$trace = $e->getTraceAsString();
		echo "Blox Page Builder Error: <b>".$message."</b>";
	
		if(GlobalsUC::SHOW_TRACE == true)
			dmp($trace);
	}
	
	
?>