<?php


defined('BLOXBUILDER_INC') or die('Restricted access');


	class UniteFunctionsWPUC{

		public static $urlSite;
		public static $urlAdmin;
		private static $db;
		
		private static $arrTaxCache;
		
		const SORTBY_NONE = "none";
		const SORTBY_ID = "ID";
		const SORTBY_AUTHOR = "author";
		const SORTBY_TITLE = "title";
		const SORTBY_SLUC = "name";
		const SORTBY_DATE = "date";
		const SORTBY_LAST_MODIFIED = "modified";
		const SORTBY_RAND = "rand";
		const SORTBY_COMMENT_COUNT = "comment_count";
		const SORTBY_MENU_ORDER = "menu_order";
		
		const ORDER_DIRECTION_ASC = "ASC";
		const ORDER_DIRECTION_DESC = "DESC";
		
		const THUMB_SMALL = "thumbnail";
		const THUMB_MEDIUM = "medium";
		const THUMB_LARGE = "large";
		const THUMB_FULL = "full";
		
		const STATE_PUBLISHED = "publish";
		const STATE_DRAFT = "draft";
		
		/**
		 * 
		 * init the static variables
		 */
		public static function initStaticVars(){
			//UniteFunctionsUC::printDefinedConstants();
			
			self::$urlSite = site_url();
			
			if(substr(self::$urlSite, -1) != "/")
				self::$urlSite .= "/";
			
			self::$urlAdmin = admin_url();			
			if(substr(self::$urlAdmin, -1) != "/")
				self::$urlAdmin .= "/";
				
		}
		
		
		/**
		 * get DB
		 */
		public static function getDB(){
			
			if(empty(self::$db))
				self::$db = new UniteCreatorDB();
				
			return(self::$db);
		}
		
		
		public static function a_____________POSTS_TYPES___________(){}
		
		/**
		 * 
		 * return post type title from the post type
		 */
		public static function getPostTypeTitle($postType){
			
			$objType = get_post_type_object($postType);
						
			if(empty($objType))
				return($postType);

			$title = $objType->labels->singular_name;
			
			return($title);
		}
		
		
		/**
		 * 
		 * get post type taxomonies
		 */
		public static function getPostTypeTaxomonies($postType){
			$arrTaxonomies = get_object_taxonomies(array( 'post_type' => $postType ), 'objects');
			
			$arrNames = array();
			foreach($arrTaxonomies as $key=>$objTax){
				$arrNames[$objTax->name] = $objTax->labels->name;
			}
			
			return($arrNames);
		}
		
		/**
		 * 
		 * get post types taxonomies as string
		 */
		public static function getPostTypeTaxonomiesString($postType){
			$arrTax = self::getPostTypeTaxomonies($postType);
			$strTax = "";
			foreach($arrTax as $name=>$title){
				if(!empty($strTax))
					$strTax .= ",";
				$strTax .= $name;
			}
			
			return($strTax);
		}
		
		/**
		 *
		 * get post types array with taxomonies
		 */
		public static function getPostTypesWithTaxomonies(){
			$arrPostTypes = self::getPostTypesAssoc();
		
			foreach($arrPostTypes as $postType=>$title){
				
				$arrTaxomonies = self::getPostTypeTaxomonies($postType);
				
				$arrPostTypes[$postType] = $arrTaxomonies;
			}
			
			$page = UniteFunctionsUC::getVal($arrPostTypes, "page");
			if(empty($page)){
				$page["category"] = "Categories";
				$arrPostTypes["page"] = $page;
			}
						
			return($arrPostTypes);
		}
		
		
		/**
		 *
		 * get array of post types with categories (the taxonomies is between).
		 * get only those taxomonies that have some categories in it.
		 */
		public static function getPostTypesWithCats(){
			
			$arrPostTypes = self::getPostTypesWithTaxomonies();
			
			
			$arrOutput = array();
			foreach($arrPostTypes as $name=>$arrTax){

				//collect categories
				$arrCats = array();
				foreach($arrTax as $taxName=>$taxTitle){
					
					$cats = self::getCategoriesAssoc($taxName, false, $name);
					
					if(!empty($cats))
					foreach($cats as $catID=>$catTitle)
						$arrCats[$catID] = $catTitle;
										
				}
								
				$arrPostType = array();
				$arrPostType["name"] = $name;
				$arrPostType["title"] = self::getPostTypeTitle($name);
				$arrPostType["cats"] = $arrCats;
				
				$arrOutput[$name] = $arrPostType;
			}
			
			return($arrOutput);
		}
		
		
		/**
		 *
		 * get array of post types with categories (the taxonomies is between).
		 * get only those taxomonies that have some categories in it.
		 */
		public static function getPostTypesWithCatIDs(){
			
			$arrTypes = self::getPostTypesWithCats();
			
			$arrOutput = array();
			
			foreach($arrTypes as $typeName => $arrType){
				
				$output = array();
				$output["name"] = $typeName;
				
				$typeTitle = self::getPostTypeTitle($typeName);
				
				//collect categories
				$arrCatsTotal = array();
				
				foreach($arrType as $arr){
					$cats = UniteFunctionsUC::getVal($arr, "cats");
					$catsIDs = array_keys($cats);
					$arrCatsTotal = array_merge($arrCatsTotal, $catsIDs);
				}
				
				$output["title"] = $typeTitle;
				$output["catids"] = $arrCatsTotal;
				
				$arrOutput[$typeName] = $output;
			}
			
			
			return($arrOutput);
		}
		
		
		
		/**
		 * 
		 * get all the post types including custom ones
		 * the put to top items will be always in top (they must be in the list)
		 */
		public static function getPostTypesAssoc($arrPutToTop = array()){
			 $arrBuiltIn = array(
			 	"post"=>"post",
			 	"page"=>"page",
			 );
			 
			 $arrCustomTypes = get_post_types(array('_builtin' => false));
			 
			 
			 //top items validation - add only items that in the customtypes list
			 $arrPutToTopUpdated = array();
			 foreach($arrPutToTop as $topItem){
			 	if(in_array($topItem, $arrCustomTypes) == true){
			 		$arrPutToTopUpdated[$topItem] = $topItem;
			 		unset($arrCustomTypes[$topItem]);
			 	}
			 }
			 
			 $arrPostTypes = array_merge($arrPutToTopUpdated,$arrBuiltIn,$arrCustomTypes);
			 
			 //update label
			 foreach($arrPostTypes as $key=>$type){
				$arrPostTypes[$key] = self::getPostTypeTitle($type);			 		
			 }
			 
			 return($arrPostTypes);
		}
		
		
		
		public static function a_____________TAXANOMIES___________(){}
		
		/**
		 *
		 * get assoc list of the taxonomies
		 */
		public static function getTaxonomiesAssoc(){
			$arr = get_taxonomies();
			
			unset($arr["post_tag"]);
			unset($arr["nav_menu"]);
			unset($arr["link_category"]);
			unset($arr["post_format"]);
		
			return($arr);
		}
		
		
		
		/**
		 *
		 * get array of all taxonomies with categories.
		 */
		public static function getTaxonomiesWithCats(){
			
			if(!empty(self::$arrTaxCache))
				return(self::$arrTaxCache);
			
			$arrTax = self::getTaxonomiesAssoc();
			$arrTaxNew = array();
			foreach($arrTax as $key=>$value){
				$arrItem = array();
				$arrItem["name"] = $key;
				$arrItem["title"] = $value;
				$arrItem["cats"] = self::getCategoriesAssoc($key);
				$arrTaxNew[$key] = $arrItem;
			}
			
			self::$arrTaxCache = $arrTaxNew;
			
			return($arrTaxNew);
		}
		
		
		public static function a__________CATEGORIES_AND_TAGS__________(){}

		
		/**
		 * check if category not exists and add it, return catID anyway
		 */
		public static function addCategory($catName){
			
			$catID = self::getCatIDByTitle($catName);
			if(!empty($catID))
				return($catID);
			
			$arrCat = array(
			  'cat_name' => $catName
			);
						
			$catID = wp_insert_category($arrCat);			
			if($catID == false)
				UniteFunctionsUC::throwError("category: $catName don't created");
			
			return($catID);
		}
		
		
		/**
		 * 
		 * get the category data
		 */
		public static function getCategoryData($catID){
			$catData = get_category($catID);
			if(empty($catData))
				return($catData);
				
			$catData = (array)$catData;			
			return($catData);
		}
		
		
		
		/**
		 * 
		 * get post categories by postID and taxonomies
		 * the postID can be post object or array too
		 */
		public static function getPostCategories($postID,$arrTax){
			
			if(!is_numeric($postID)){
				$postID = (array)$postID;
				$postID = $postID["ID"];
			}
				
			$arrCats = wp_get_post_terms( $postID, $arrTax);
			$arrCats = UniteFunctionsUC::convertStdClassToArray($arrCats);
			return($arrCats);
		}

		
		/**
		 *
		 * get post categories list assoc - id / title
		 */
		public static function getCategoriesAssoc($taxonomy = "category", $addNotSelected = false, $forPostType = null){
			
			if($taxonomy === null)
				$taxonomy = "category";
			
			$arrCats = array();
			
			if($addNotSelected == true)
				$arrCats[""] = __("[All Categories]", BLOXBUILDER_TEXTDOMAIN);
			
			
			if(strpos($taxonomy,",") !== false){
				$arrTax = explode(",", $taxonomy);
				foreach($arrTax as $tax){
					$cats = self::getCategoriesAssoc($tax);
					$arrCats = array_merge($arrCats,$cats);
				}
		
				return($arrCats);
			}
			
			//$cats = get_terms("category");
			$args = array("taxonomy"=>$taxonomy);
			$args["hide_empty"] = false;
			$args["number"] = 100;
			
			$cats = get_categories($args);
			
			foreach($cats as $cat){
				
				//dmp($cat);exit();
					
				$numItems = $cat->count;
				$itemsName = "items";
				if($numItems == 1)
					$itemsName = "item";
		
				$title = $cat->name . " ($numItems $itemsName)";
		
				$id = $cat->cat_ID;
				$arrCats[$id] = $title;
			}
			return($arrCats);
		}
		
		/**
		 *
		 * get categories by id's
		 */
		public static function getCategoriesByIDs($arrIDs,$strTax = null){
		
			if(empty($arrIDs))
				return(array());
		
			if(is_string($arrIDs))
				$strIDs = $arrIDs;
			else
				$strIDs = implode(",", $arrIDs);
		
			$args = array();
			$args["include"] = $strIDs;
		
			if(!empty($strTax)){
				if(is_string($strTax))
					$strTax = explode(",",$strTax);
		
				$args["taxonomy"] = $strTax;
			}
		
			$arrCats = get_categories( $args );
		
			if(!empty($arrCats))
				$arrCats = UniteFunctionsUC::convertStdClassToArray($arrCats);
		
			return($arrCats);
		}
		
		
		/**
		 *
		 * get categories short
		 */
		public static function getCategoriesByIDsShort($arrIDs,$strTax = null){
			$arrCats = self::getCategoriesByIDs($arrIDs,$strTax);
			$arrNew = array();
			foreach($arrCats as $cat){
				$catID = $cat["term_id"];
				$catName = $cat["name"];
				$arrNew[$catID] =  $catName;
			}
		
			return($arrNew);
		}
		
		
		
		
		/**
		 *
		 * get post tags html list
		 */
		public static function getTagsHtmlList($postID,$before="",$sap=",",$after=""){
			
			$tagList = get_the_tag_list($before,",",$after,$postID);
			
			return($tagList);
		}

		
		/**
		 * get category by slug name
		 */
		public static function getCatIDBySlug($slug, $type = "slug"){
			
			$arrCats = get_categories(array("hide_empty"=>false));
			
			foreach($arrCats as $cat){
				$cat = (array)$cat;
				
				switch($type){
					case "slug":
						$catSlug = $cat["slug"];
					break;
					case "title":
						$catSlug = $cat["name"];
					break;
					default:
						UniteFunctionsUC::throwError("Wrong cat name");
					break;
				}
				
				$catID = $cat["term_id"];
				
				if($catSlug == $slug)
					return($catID);
			}
			
			return(null);
		}
		
		/**
		 * get category by title (name)
		 */
		public static function getCatIDByTitle($title){
			
			$catID = self::getCatIDBySlug($title,"title");
			
			return($catID);
		}
		
		public static function a_______________GENERAL_GETTERS____________(){}
		
		
		/**
		 *
		 * get sort by with the names
		 */
		public static function getArrSortBy(){
			$arr = array();
			$arr[self::SORTBY_ID] = "Post ID";
			$arr[self::SORTBY_DATE] = "Date";
			$arr[self::SORTBY_TITLE] = "Title";
			$arr[self::SORTBY_SLUC] = "Slug";
			$arr[self::SORTBY_AUTHOR] = "Author";
			$arr[self::SORTBY_LAST_MODIFIED] = "Last Modified";
			$arr[self::SORTBY_COMMENT_COUNT] = "Number Of Comments";
			$arr[self::SORTBY_RAND] = "Random";
			$arr[self::SORTBY_NONE] = "Unsorted";
			$arr[self::SORTBY_MENU_ORDER] = "Custom Order";
			return($arr);
		}
		
		
		/**
		 *
		 * get array of sort direction
		 */
		public static function getArrSortDirection(){
			$arr = array();
			$arr[self::ORDER_DIRECTION_DESC] = "Descending";
			$arr[self::ORDER_DIRECTION_ASC] = "Ascending";
			return($arr);
		}
		
		public static function a_____________POST_GETTERS____________(){}
		
		
		/**
		 *
		 * get single post
		 */
		public static function getPost($postID, $addAttachmentImage = false, $getMeta = false){
			
			$post = get_post($postID);
			if(empty($post))
				UniteFunctionsUC::throwError("Post with id: $postID not found");
		
			$arrPost = $post->to_array();
		
			if($addAttachmentImage == true){
				$arrImage = self::getPostAttachmentImage($postID);
				if(!empty($arrImage))
					$arrPost["image"] = $arrImage;
			}
		
			if($getMeta == true)
				$arrPost["meta"] = self::getPostMeta($postID);
		
			return($arrPost);
		}
		
		/**
		 * get post by name
		 */
		public static function getPostByName($name, $postType = null){
			
			if(!empty($postType)){
				$query = array(
					'name'=>$name,
					'post_type'=>$postType
				);			
				
				$arrPosts = get_posts($query);
				$post = $arrPosts[0];
				return($post);
			}
			
			//get only by name
			$postID = self::getPostIDByPostName($name);
			if(empty($postID))
				return(null);
			
			$post = get_post($postID);
						
			return($post);
		}
		
		
		/**
		 * get post id by post name
		 */
		public static function getPostIDByPostName($postName){
			
			$tablePosts = UniteProviderFunctionsUC::$tablePosts;
			
			$db = self::getDB();
			$response = $db->fetch($tablePosts, array("post_name"=>$postName));
			
			if(empty($response))
				return(null);
			
			$postID = $response[0]["ID"];
			
			return($postID);
		}
		
		
		/**
		 * get post id by name, using DB
		 */
		public static function isPostNameExists($postName){
			
			$tablePosts = UniteProviderFunctionsUC::$tablePosts;
			
			$db = self::getDB();
			$response = $db->fetch($tablePosts, array("post_name"=>$postName));
			
			$isExists = !empty($response);
			
			return($isExists);
		}
		
		
		/**
		 * get post meta data
		 */
		public static function getPostMeta($postID){
		
			$arrMeta = get_post_meta($postID);
			foreach($arrMeta as $key=>$item){
				if(is_array($item) && count($item) == 1)
					$arrMeta[$key] = $item[0];
			}
		
		
			return($arrMeta);
		}
		
		
		/**
		 *
		 * get posts post type
		 */
		public static function getPostsByType($postType, $sortBy = self::SORTBY_TITLE, $addParams = array(),$returnPure = false){
			
			if(empty($postType))
				$postType = "any";
			
			$query = array(
					'post_type'=>$postType,
					'orderby'=>$sortBy
			);
			
			if($sortBy == self::SORTBY_MENU_ORDER)
				$query["order"] = self::ORDER_DIRECTION_ASC;
			
			$query["posts_per_page"] = 2000;	//no limit
			
			if(!empty($addParams))
				$query = array_merge($query, $addParams);	
						
			$arrPosts = get_posts($query);
			
			if($returnPure == true)
				return($arrPosts);
				
			foreach($arrPosts as $key=>$post){
				
				if(method_exists($post, "to_array"))
					$arrPost = $post->to_array();
				else
					$arrPost = (array)$post;
				
				$arrPosts[$key] = $arrPost;
			}
			
			return($arrPosts);
		}


		/**
		 * get posts post type
		 */
		public static function getPosts($filters){
			
			$args = array();
			
			$args["post_type"] = UniteFunctionsUC::getVal($filters, "posttype");
			$args["category"] = UniteFunctionsUC::getVal($filters, "category");
			$args["orderby"] = UniteFunctionsUC::getVal($filters, "orderby");
			$args["order"] = UniteFunctionsUC::getVal($filters, "orderdir");
			$args["posts_per_page"] = UniteFunctionsUC::getVal($filters, "limit");
			
			$arrPosts = get_posts($args);
			
			return($arrPosts);
		}

		/**
		 * get post thumb id from post id
		 */
		public static function getFeaturedImageID($postID){
			$thumbID = get_post_thumbnail_id( $postID );
			return($thumbID);
		}
		
		/**
		 * get page template
		 */
		public static function getPostPageTemplate($post){
			
			if(empty($post))
				return("");
			
			$arrPost = $post->to_array();
			$pageTemplate = UniteFunctionsUC::getVal($arrPost, "page_template");
			
			return($pageTemplate);
		}
		
		/**
		 * get edit post url
		 */
		public static function getUrlEditPost($postID, $encodeForJS = false){
			
			$context = "display";
			if($encodeForJS == false)
				$context = "normal";
			
			$urlEditPost = get_edit_post_link( $postID, $context); 
			
			return($urlEditPost);
		}
		
		
		/**
		 * check if current user can edit post
		 */
		public static function isUserCanEditPost($postID){
			
			$post = get_post($postID);
			
			if(empty($post))
				return(false);

			$postStatus = $post->post_status;
			if($postStatus == "trash")
				return(false);
			
			$postType = $post->post_type;
			
			$objPostType = get_post_type_object($postType);
			if(empty($objPostType))
				return(false);
			
			if(isset($objPostType->cap->edit_post) == false ){
				return false;
			}
			
			$editCap = $objPostType->cap->edit_post;
			
			$isCanEdit = current_user_can( $editCap, $postID );
			if($isCanEdit == false)
				return(false);
			
			$postsPageID = get_option( 'page_for_posts' );
			if($postsPageID === $postID)
				return(false);

			
			return(true);
		}
		
		public static function a_____________POST_ACTIONS____________(){}
		
		
		
		/**
		 * update post type
		 */
		public static function updatePost($postID, $arrUpdate){
			
			if(empty($arrUpdate))
				UniteFunctionsUC::throwError("nothing to update post");
				
			$arrUpdate["ID"] = $postID;
			
			$wpError = wp_update_post( $arrUpdate ,true);
			
			if (is_wp_error($wpError)) {
    			UniteFunctionsUC::throwError("Error updating post: $postID");
			}
			
		}

		
		/**
		 * update post ordering
		 */
		public static function updatePostOrdering($postID, $ordering){
			
			$arrUpdate = array(
			      'menu_order' => $ordering,
			 );		
			
			self::updatePost($postID, $arrUpdate);
		}
		
		/**
		 * update post content
		 */
		public static function updatePostContent($postID, $content){
			
			$arrUpdate = array("post_content"=>$content);
			self::updatePost($postID, $arrUpdate);
		}
		
		/**
		 * update post page template attribute in meta
		 */
		public static function updatePageTemplateAttribute($pageID, $pageTemplate){
			
			update_post_meta($pageID, "_wp_page_template", $pageTemplate);
		}
		
		
		/**
		 * insert post
		 * params: [cat_slug, content]
		 */
		public static function insertPost($title, $alias, $params = array()){
			
			$catSlug = UniteFunctionsUC::getVal($params, "cat_slug");
			$content = UniteFunctionsUC::getVal($params, "content");
			$isPage = UniteFunctionsUC::getVal($params, "ispage");
			$isPage = UniteFunctionsUC::strToBool($isPage);
			
			$catID = null;
			if(!empty($catSlug)){
				$catID = self::getCatIDBySlug($catSlug);
				if(empty($catID))
					UniteFunctionsUC::throwError("Category id not found by slug: $slug");
			}
			
			$isPostExists = self::isPostNameExists($alias);
			
			if($isPostExists == true)
				UniteFunctionsUC::throwError("Post with name: <b> {$alias} </b> already exists");
			
			
			$arguments = array();
			$arguments["post_title"] = $title;
			$arguments["post_name"] = $alias;
			$arguments["post_status"] = "publish";
			
			if(!empty($content))
				$arguments["post_content"] = $content;
			
			if(!empty($catID))
				$arguments["post_category"] = array($catID);
			
			if($isPage == true)
				$arguments["post_type"] = "page";
			
			$postType = UniteFunctionsUC::getVal($params, "post_type");
			if(!empty($postType))
				$arguments["post_type"] = $postType;
			
			$newPostID = wp_insert_post($arguments, true);
			
			if(is_wp_error($newPostID)){
				$errorMessage = $newPostID->get_error_message();
				UniteFunctionsUC::throwError($errorMessage);
			}
			
			
			return($newPostID);
		}
		
		
		/**
		 * insert new page
		 */
		public static function insertPage($title, $alias, $params = array()){
			
			$params["ispage"] = true;
			
			$pageID = self::insertPost($title, $alias, $params);
			
			return($pageID);
		}
		
		
		/**
		 * delete all post metadata
		 */
		public static function deletePostMetadata($postID){
			
			$postID = (int)$postID;
			
			$tablePostMeta = UniteProviderFunctionsUC::$tablePostMeta;
			
			$db = self::getDB();
			$db->delete($tablePostMeta, "post_id=$postID");
		}
		
		/**
		 * duplicate post
		 */
		public static function duplicatePost($postID, $newTitle = null){
			
			$post = get_post($postID);
			if(empty($post))
				UniteFunctionsUC::throwError("Post now found");
			
			$current_user = wp_get_current_user();
			$new_post_author = $current_user->ID;			
			
			$postTitle = $post->post_title;
			if(!empty($newTitle))
				$postTitle = $newTitle;
			
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => $post->post_status,
				'post_title'     => $postTitle,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);				
			
			
			$newPostID = wp_insert_post( $args );
			
			if(empty($newPostID))
				UniteFunctionsUC::throwError("Can't duplicate post: $postID");
			
			
			//set all taxanomies to the new post (category, tags)
			$taxonomies = get_object_taxonomies($post->post_type);
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($postID, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($newPostID, $post_terms, $taxonomy, false);
			}

			//duplicate meta
			global $wpdb;
			
			//duplicate all post meta just in two SQL queries
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$postID");
			if (count($post_meta_infos)!=0) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;
					if( $meta_key == '_wp_old_slug' ) continue;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $newPostID, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode(" UNION ALL ", $sql_query_sel);
				$wpdb->query($sql_query);
			}
			
			
			return($newPostID);
		}
		
		/**
		 * delete multiple posts
		 */
		public static function deleteMultiplePosts($arrPostIDs){
			
			if(empty($arrPostIDs))
				return(false);
			
			if(is_array($arrPostIDs) == false)
				return(false);
			
			foreach($arrPostIDs as $postID)
				self::deletePost($postID);
			
		}
		
		
		/**
		 * delete post
		 */
		public static function deletePost($postID){
			
			wp_delete_post($postID, true);
			
		}
		
		public static function a__________ATTACHMENT__________(){}
		
		/**
		 *
		 * get attachment image url
		 */
		public static function getUrlAttachmentImage($thumbID, $size = self::THUMB_FULL){
			
			$arrImage = wp_get_attachment_image_src($thumbID, $size);
			if(empty($arrImage))
				return(false);
			
			$url = UniteFunctionsUC::getVal($arrImage, 0);
			return($url);
		}
		
		
		
		
		/**
		 * get attachment data
		 */
		public static function getAttachmentData($thumbID){
			
			if(is_numeric($thumbID) == false)
				return(null);
			
			$post = get_post($thumbID);
			if(empty($post))
				return(null);
			
			$title = wp_get_attachment_caption($thumbID);
				
			$item = array();
			$item["image_id"] = $post->ID;
			$item["image"] = $post->guid;
			
			if(empty($title))
				$title = $post->post_title;
			
			$urlThumb = self::getUrlAttachmentImage($thumbID,self::THUMB_MEDIUM);
			if(empty($urlThumb))
				$urlThumb = $post->guid;
			
			$item["thumb"] = $urlThumb;
			
			$item["title"] = $title;
			$item["description"] = $post->post_content;
			$item["alt"] = $altText;
			$item["caption"] = $caption;
			
			return($item);
		}
		
		
		/**
		 * get thumbnail sizes array
		 * mode: null, "small_only", "big_only"
		 */
		public static function getArrThumbSizes($mode = null){
			global $_wp_additional_image_sizes;
			
			$arrWPSizes = get_intermediate_image_sizes();
		
			$arrSizes = array();
		
			if($mode != "big_only"){
				$arrSizes[self::THUMB_SMALL] = "Thumbnail (150x150)";
				$arrSizes[self::THUMB_MEDIUM] = "Medium (max width 300)";
			}
		
			if($mode == "small_only")
				return($arrSizes);
		
			foreach($arrWPSizes as $size){
				$title = ucfirst($size);
				switch($size){
					case self::THUMB_LARGE:
					case self::THUMB_MEDIUM:
					case self::THUMB_FULL:
					case self::THUMB_SMALL:
						continue(2);
						break;
					case "ug_big":
						$title = __("Big", BLOXBUILDER_TEXTDOMAIN);
						break;
				}
		
				$arrSize = UniteFunctionsUC::getVal($_wp_additional_image_sizes, $size);
				$maxWidth = UniteFunctionsUC::getVal($arrSize, "width");
		
				if(!empty($maxWidth))
					$title .= " (max width $maxWidth)";
		
				$arrSizes[$size] = $title;
			}
		
			$arrSizes[self::THUMB_LARGE] = __("Large (max width 1024)", BLOXBUILDER_TEXTDOMAIN);
			$arrSizes[self::THUMB_FULL] = __("Full", BLOXBUILDER_TEXTDOMAIN);
		
			return($arrSizes);
		}
		
		
		/**
		 * Get an attachment ID given a URL.
		*
		* @param string $url
		*
		* @return int Attachment ID on success, 0 on failure
		*/
		public static function getAttachmentIDFromImageUrl( $url ) {
		
			$attachment_id = 0;
		
			$dir = wp_upload_dir();
		
			if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
		
				$file = basename( $url );
		
				$query_args = array(
						'post_type'   => 'attachment',
						'post_status' => 'inherit',
						'fields'      => 'ids',
						'meta_query'  => array(
								array(
										'value'   => $file,
										'compare' => 'LIKE',
										'key'     => '_wp_attachment_metadata',
								),
						)
				);
				
				$query = new WP_Query( $query_args );
		
				if ( $query->have_posts() ) {
		
					foreach ( $query->posts as $post_id ) {
		
						$meta = wp_get_attachment_metadata( $post_id );
		
						$original_file       = basename( $meta['file'] );
						$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
		
						if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
							$attachment_id = $post_id;
							break;
						}
		
					}
		
				}
		
			}
		
			return $attachment_id;
		}		
		
		
		public static function a__________OTHER_FUNCTIONS__________(){}
		
		
		/**
		 * get max menu order
		 */
		public static function getMaxMenuOrder($postType){
			
			$tablePosts = UniteProviderFunctionsUC::$tablePosts;
			
			$db = self::getDB();
			
			$query = "select MAX(menu_order) as maxorder from {$tablePosts} where post_type='$postType'";
			
			$rows = $db->fetchSql($query);
		
			$maxOrder = 0;
			if(count($rows)>0)
				$maxOrder = $rows[0]["maxorder"];
		
			if(!is_numeric($maxOrder))
				$maxOrder = 0;
			
			return($maxOrder);
		}
		
		
		/**
		 *
		 * get wp-content path
		 */
		public static function getPathUploads(){
			
			if(is_multisite()){
				if(!defined("BLOGUPLOADDIR")){
					$pathBase = self::getPathBase();
					$pathContent = $pathBase."wp-content/uploads/";
				}else
					$pathContent = BLOGUPLOADDIR;
			}else{
				$pathContent = WP_CONTENT_DIR;
				if(!empty($pathContent)){
					$pathContent .= "/";
				}
				else{
					$pathBase = self::getPathBase();
					$pathContent = $pathBase."wp-content/uploads/";
				}
			}
		
			return($pathContent);
		}
		
		
		
		
		
		/**
		 *
		 * simple enqueue script
		 */
		public static function addWPScript($scriptName){
			wp_enqueue_script($scriptName);
		}
		
		/**
		 *
		 * simple enqueue style
		 */
		public static function addWPStyle($styleName){
			wp_enqueue_style($styleName);
		}
		
		
		/**
		 *
		 * check if some db table exists
		 */
		public static function isDBTableExists($tableName){
			global $wpdb;
		
			if(empty($tableName))
				UniteFunctionsUC::throwError("Empty table name!!!");
		
			$sql = "show tables like '$tableName'";
		
			$table = $wpdb->get_var($sql);
		
			if($table == $tableName)
				return(true);
		
			return(false);
		}
		
		/**
		 *
		 * validate permission that the user is admin, and can manage options.
		 */
		public static function isAdminPermissions(){
		
			if( is_admin() &&  current_user_can("manage_options") )
				return(true);
		
			return(false);
		}
		
		
		/**
		 * add shortcode
		 */
		public static function addShortcode($shortcode, $function){
		
			add_shortcode($shortcode, $function);
		
		}
		
		/**
		 *
		 * add all js and css needed for media upload
		 */
		public static function addMediaUploadIncludes(){
		
			self::addWPScript("thickbox");
			self::addWPStyle("thickbox");
			self::addWPScript("media-upload");
		
		}
		
		
		
		
		/**
		 * check if post exists by title
		 */
		public static function isPostExistsByTitle($title, $postType="page"){
			
			$post = get_page_by_title( $title, ARRAY_A, $postType );
			
			return !empty($post);
		}
		
		
		
		
		/**
		 * tells if the page is posts of pages page
		 */
		public static function isAdminPostsPage(){
			
			$screen = get_current_screen();
			$screenID = $screen->base;
			if(empty($screenID))
				$screenID = $screen->id;
			
			
			if($screenID != "page" && $screenID != "post")
				return(false);
			
			
			return(true);
		}
		
		
		/**
		 *
		 * register widget (must be class)
		 */
		public static function registerWidget($widgetName){
			add_action('widgets_init', create_function('', 'return register_widget("'.$widgetName.'");'));
		}
		
		
		/**
		 * get admin title
		 */
		public static function getAdminTitle($customTitle){
			
			global $title;
			
			if(!empty($customTitle))
				$title = $customTitle;
			else
				get_admin_page_title();
			
			$title = esc_html( strip_tags( $title ) );
			
			if ( is_network_admin() ) {
				/* translators: Network admin screen title. 1: Network name */
				$admin_title = sprintf( __( 'Network Admin: %s' ), esc_html( get_network()->site_name ) );
			} elseif ( is_user_admin() ) {
				/* translators: User dashboard screen title. 1: Network name */
				$admin_title = sprintf( __( 'User Dashboard: %s' ), esc_html( get_network()->site_name ) );
			} else {
				$admin_title = get_bloginfo( 'name' );
			}
			
			if ( $admin_title == $title ) {
				/* translators: Admin screen title. 1: Admin screen name */
				$admin_title = sprintf( __( '%1$s &#8212; WordPress' ), $title );
			} else {
				/* translators: Admin screen title. 1: Admin screen name, 2: Network or site name */
				$admin_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), $title, $admin_title );
			}
			
			return($admin_title);
		}
		
	/**
	 * get action functions of some tag
	 */
	public static function getActionFunctionsKeys($tag){
		
		global $wp_filter;
		if(isset($wp_filter[$tag]) == false)
			return(array());
		
		$objFilter = $wp_filter[$tag];
		
		$arrFunctions = array();
		$arrCallbacks = $objFilter->callbacks;
		if(empty($arrCallbacks))
			return(array());
		
		foreach($arrCallbacks as $priority=>$callbacks){
			$arrKeys = array_keys($callbacks);
			
			foreach($arrKeys as $key){
				$arrFunctions[$key]	= true;
			}
			
		}
		
		return($arrFunctions);
	}
	
	/**
	 * clear filters from functions
	 */
	public static function clearFiltersFromFunctions($tag, $arrFunctionsAssoc){
		global $wp_filter;
		if(isset($wp_filter[$tag]) == false)
			return(false);
		
		if(empty($arrFunctionsAssoc))
			return(false);
			
		$objFilter = $wp_filter[$tag];
		
		$arrFunctions = array();
		$arrCallbacks = $objFilter->callbacks;
		if(empty($arrCallbacks))
			return(array());
		
		foreach($arrCallbacks as $priority=>$callbacks){
			$arrKeys = array_keys($callbacks);
			
			foreach($arrKeys as $key){
				if(isset($arrFunctionsAssoc[$key]))				
					unset($wp_filter[$tag]->callbacks[$priority][$key]);
			}
			
		}
			
	}
	
	/**
	 * get blog url
	 */
	public static function getUrlBlog(){
		
		//home page:
		
		$showOnFront = get_option( 'show_on_front' );
		if($showOnFront != "page"){
			$urlBlog = home_url();
			return($urlBlog);
		}
		
		//page is missing:
		
		$pageForPosts = get_option( 'page_for_posts' );
		if(empty($pageForPosts)){
			$urlBlog = home_url( '/?post_type=post' );
			return($urlBlog);
		}
			
		//some page:
		$urlBlog = self::getPermalink( $pageForPosts );
		
		return($urlBlog);  
	}
	
	
	/**
	 * get permalist with check of https
	 */
	public static function getPermalink($post){
		
		$url = get_permalink($post);
		if(GlobalsUC::$is_ssl == true)
			$url = UniteFunctionsUC::urlToSsl($url);
		
		return($url);
	}
	
	
	/**
	 * tell wp plugins do not cache the page
	 */
	public static function preventCachingPage(){
		
		$arrNotCacheTags = array("DONOTCACHEPAGE","DONOTCACHEDB","DONOTMINIFY","DONOTCDN");
		
		foreach($arrNotCacheTags as $tag){
			if(defined( $tag ))
				continue;
				
			define($tag, true);			
		}
		
		nocache_headers();
	}
		
	
	
}	//end of the class
	
	//init the static vars
	UniteFunctionsWPUC::initStaticVars();
	
?>