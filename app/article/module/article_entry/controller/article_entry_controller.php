
<?php
require __DIR__.'/../../../../function/check_session_func.php';
require __DIR__.'/../../../../function/controller_func.php';
require __DIR__.'/../validation/general_validation.php';
require __DIR__.'/../validation/photos_validation.php';
require __DIR__.'/../validation/media_validation.php';
require __DIR__.'/../validation/document_validation.php';

$checkaccess =  new checkaccess_model(); 
list($allow,$level,$add,$change,$delete) = $checkaccess->checkRight('ARTICLE-TRAN-01-001');
if($allow == 0) require __DIR__.'/../../../../template/sorry_inc.php';

$dmGeneralModel = new article_entry_model();  //Open database connection
		
$arr_article_season_master = $dmGeneralModel->article_season_master_viewall(); 
$arr_article_category_master = $dmGeneralModel->article_category_master_viewall(); 
$arr_article_media_type_master = $dmGeneralModel->article_media_type_master_viewall(); 
		
switch($IS_action)
{
	case "/";
		require __DIR__.'/../view/index_inc.php';
		break;

	case "search";
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$page=1;
				$json_searchphrase = json_encode($_POST);	
				$lot_id=$dmGeneralModel->search($json_searchphrase);
		} else {
				$lot_id=$_GET["lot_id"];
				$page=$_GET["page"];		
				$json_searchphrase = $dmGeneralModel->searchphrase($lot_id);
				}
		$result_id=$dmGeneralModel->paging_config($lot_id);
		$paging = new PagingManger($result_id,SYSTEM_PAGE_ROW_LIMIT);
		$arr_general_model=array();
		if ($result_id != '') $arr_general_model=$dmGeneralModel->retreive_content($lot_id,$page);
		require __DIR__."'/../view/search_result_inc.php";				
		break;			
		
		
	case "new";
		require __DIR__.'/../view/new_inc.php';
		break;		
		
		
	case "create";

		$general = $_POST['general'] ;
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$vlValidation = new general_validation('create',$dmGeneralModel);
			if($vlValidation->ValidateFormActionCreate($general)) {
				$item_id=$dmGeneralModel->create($_POST['general']);
				$primary = $dmGeneralModel->select($item_id);
				$general = $dmGeneralModel->select($item_id);
				$general['article_date'] = YMDtoDMY($general['article_date']);
				//$arr_subcategory = $dmGeneralModel->subcategory_list($_GET["item_id"]);
				$tab  = 'general';
				require __DIR__.'/../view/edit_inc.php';		

			} else {
				require __DIR__.'/../view/new_inc.php';
			}	
		}
		
		break;		
		
	
	case "edit";

		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];
		$general = $dmGeneralModel->select($_GET["item_id"]);
		$general['article_date'] = YMDtoDMY($general['article_date']);
		
		if($tab=='general' ||  $tab=='') {
			require __DIR__.'/../view/edit_inc.php';
		}	
		
		if($tab=='media') {
			$primary = $dmGeneralModel->select($_GET["item_id"]);
			$arr_media = $dmGeneralModel->media_list($_GET["item_id"]);
			require __DIR__.'/../view/list_media_inc.php';
		}
		
		if($tab=='photos') {
			$primary = $dmGeneralModel->select($_GET["item_id"]);
			$arr_photos = $dmGeneralModel->photos_list($_GET["item_id"]);
			require __DIR__.'/../view/list_photos_inc.php';
		}

		
		if($tab=='document') {
			$primary = $dmGeneralModel->select($_GET["item_id"]);
			$arr_document = $dmGeneralModel->article_entry_document_listall($_GET["item_id"]);
			require __DIR__.'/../view/list_document_inc.php';

		}		
		
		break;
		
		
	case "update";
	
		require __DIR__.'/common_paging_inc.php';
		$item_id = $_GET["item_id"];	
		$general = $_POST['general'] ;
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$vlValidation = new general_validation('update',$dmGeneralModel);
				if($vlValidation->ValidateFormActionUpdate($item_id, $general)) {
					$void=$dmGeneralModel->update($item_id, $_POST['general']);
					$general = $dmGeneralModel->select($item_id);
					$general['article_date'] = YMDtoDMY($general['article_date']);
					require __DIR__.'/../view/edit_inc.php';
	
			} 
			else {
				$general = $_POST['general'] ;
				require __DIR__.'/../view/edit_inc.php';
			}
		}
		
		
		break;


	case "photos_new";		

		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];	
		$primary = $dmGeneralModel->select($_GET["item_id"]);
		require __DIR__.'/../view/new_photos_inc.php';
		break;
		
		
		
	case "photos_create";
	
		require __DIR__.'/common_paging_inc.php';
		$item_id = $_GET["item_id"];	
		$general = $_POST['general'] ;
		if ($_SERVER['REQUEST_METHOD'] == "POST" ) {
			$vlValidation = new photos_validation('update',$dmGeneralModel);
				if($vlValidation->ValidateFormActionUpdate($item_id, $general)) {
					$last_insert_id=$dmGeneralModel->photos_create($item_id, $_POST['general']);
					
					$article_id =$general['article_id'];

					/* handle large_jpeg upload file */
					//$target_dir = DIR_PUBLIC_UPLOAD."/cs_document/";
					$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
					//echo '<br>target_dir='.$target_dir.'<br>';

					//Create sub-folder if not exist
					//Generate YYMMDD9999 article_id
					$YYMMDD =date('ymd');
					$large_file_path = $YYMMDD;
					$photo_folder = $target_dir.'/'.$YYMMDD;
					//echo '<br>photo_folder='.$photo_folder.'<br>';
					if (!file_exists($photo_folder)) {
						mkdir($photo_folder, 0777, true);
					}					
					
					$target_dir = $photo_folder.'/';
					
					//$article_id = $item_id;
					$document_type = $_FILES["large_jpeg"]["type"];
					$file_size = $_FILES["large_jpeg"]["size"];
					$path = $_FILES['large_jpeg']['name'];
					$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
					//echo '<br>path='.$path.'<br>';
					//echo '<br>ext='.$ext.'<br>';
					
					//if($file_size >= 0){
					if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
						//$name_file = str_pad($last_insert_id, 5, '0', STR_PAD_LEFT);
						$rand_seeds = rand(100000,200000);
						
						//handle compress large to small jpeg file
						//$small_file_name = $article_id.'_small_'.$rand_seeds;
						$small_file_name = 'article_'.$article_id.'_small_'.$rand_seeds;
						error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
						$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
						
						//copy large file to small file (before compress)
						//$name_file = $article_id.'_small_'.$rand_seeds;
						$name_file = 'article_'.$article_id.'_small_'.$rand_seeds;
						$new_file_name_saved = $small_file_name.'.'.'jpeg';
						$void=$dmGeneralModel->small_jpeg_filename_update($last_insert_id,$new_file_name_saved,0,'jpeg',$YYMMDD,$_SESSION["sUserID"]);
						
						//<end>handle compress large to small jpeg file
						
						//$name_file = $article_id.'_large_'.$rand_seeds;
						$name_file = 'article_'.$article_id.'_large_'.$rand_seeds;
						$new_file_name_saved = $name_file.'.'.$ext;
						//move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
						move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
						
						$void=$dmGeneralModel->large_jpeg_filename_update($last_insert_id,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);

						
						$message = 'Upload Large Jpeg  Successfully.';	
						 } else{
							 $message_fail = 'Empty Upload #99 fail.';	 
						 }

					//handle small_jpeg
					if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
					}
					/* <END> handle large_jpeg upload file */

					
					$primary = $dmGeneralModel->select($item_id);
					$arr_photos = $dmGeneralModel->photos_list($_GET["item_id"]);
					require __DIR__.'/../view/list_photos_inc.php';
	
			} 
			else {
				$primary = $dmGeneralModel->select($item_id);
				$general = $_POST['general'] ;
				$arr_photos = $dmGeneralModel->photos_list($_GET["item_id"]);
				require __DIR__.'/../view/list_photos_inc.php';
			}
		}
		
		
		break;		
		
		
	case "photos_edit";		

		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];
		$photo_id=$_GET["photo_id"];
		$primary = $dmGeneralModel->select($_GET["item_id"]);
		$general = $dmGeneralModel->photos_select($photo_id);
		require __DIR__.'/../view/edit_photos_inc.php';

		break;		
		
		
	case "photos_update";
	
		require __DIR__.'/common_paging_inc.php';
		$item_id = $_GET["item_id"];	
		$general = $_POST['general'] ;
		$photo_id=$_GET["photo_id"];		
		if ($_SERVER['REQUEST_METHOD'] == "POST" ) {
			$vlValidation = new photos_validation('update',$dmGeneralModel);
				if($vlValidation->ValidateFormActionUpdate($item_id, $general)) {
					$void=$dmGeneralModel->photos_update($photo_id, $_POST['general']);
					
					$article_id =$item_id;

					/* handle large_jpeg upload file */
					//$target_dir = DIR_PUBLIC_UPLOAD."/cs_document/";
					$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
					//echo '<br>target_dir='.$target_dir.'<br>';

					//Create sub-folder if not exist
					//Generate YYMMDD9999 article_id
					$YYMMDD =date('ymd');
					$large_file_path = $YYMMDD;
					$photo_folder = $target_dir.'/'.$YYMMDD;
					//echo '<br>photo_folder='.$photo_folder.'<br>';
					if (!file_exists($photo_folder)) {
						mkdir($photo_folder, 0777, true);
					}					
					
					$target_dir = $photo_folder.'/';
					
					//$article_id = $item_id;
					$document_type = $_FILES["large_jpeg"]["type"];
					$file_size = $_FILES["large_jpeg"]["size"];
					$path = $_FILES['large_jpeg']['name'];
					$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
					//echo '<br>path='.$path.'<br>';
					//echo '<br>ext='.$ext.'<br>';
					
					//if($file_size >= 0){
					if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
						//$name_file = str_pad($last_insert_id, 5, '0', STR_PAD_LEFT);
						$rand_seeds = rand(100000,200000);
						
						//handle compress large to small jpeg file
						//$small_file_name = $article_id.'_small_'.$rand_seeds;
						$small_file_name = 'article_'.$article_id.'_small_'.$rand_seeds;
						error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
						$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
						
						//copy large file to small file (before compress)
						//$name_file = $article_id.'_small_'.$rand_seeds;
						$name_file = 'article_'.$article_id.'_small_'.$rand_seeds;
						$new_file_name_saved = $small_file_name.'.'.'jpeg';
						$void=$dmGeneralModel->small_jpeg_filename_update($photo_id,$new_file_name_saved,0,'jpeg',$YYMMDD,$_SESSION["sUserID"]);
						
						//<end>handle compress large to small jpeg file
						
						//$name_file = $article_id.'_large_'.$rand_seeds;
						$name_file = 'article_'.$article_id.'_large_'.$rand_seeds;
						$new_file_name_saved = $name_file.'.'.$ext;
						//move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
						move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
						
						$void=$dmGeneralModel->large_jpeg_filename_update($photo_id,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);

						
						$message = 'Upload Large Jpeg  Successfully.';	
						 } else{
							 $message_fail = 'Empty Upload #99 fail.';	 
						 }

					//handle small_jpeg
					if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
					}
					/* <END> handle large_jpeg upload file */
					
					
					$primary = $dmGeneralModel->select($_GET["item_id"]);
					$arr_photos = $dmGeneralModel->photos_list($_GET["item_id"]);
					require __DIR__.'/../view/list_photos_inc.php';
	
			} 
			else {
				$primary = $dmGeneralModel->select($_GET["item_id"]);
				$general = $dmGeneralModel->photos_select($photo_id);
				require __DIR__.'/../view/edit_photos_inc.php';

			}
		}
		
		
		break;		
		
		
	case "document_new";		

		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];	
		$primary = $dmGeneralModel->select($_GET["item_id"]);
		require __DIR__.'/../view/new_document_inc.php';
		break;
		
		
	case "document_create";
	
		require __DIR__.'/common_paging_inc.php';
		$item_id = $_GET["item_id"];	
		$general = $_POST['general'] ;
		if ($_SERVER['REQUEST_METHOD'] == "POST" ) {
			$vlValidation = new document_validation('update',$dmGeneralModel);
				if($vlValidation->ValidateFormActionUpdate($item_id, $general)) {
					$last_insert_id=$dmGeneralModel->document_create($item_id, $_POST['general']);
					
					/* handle  upload file */
					$primary = $dmGeneralModel->select($_GET["item_id"]);
					$article_id =$item_id;

					$target_dir = DIR_DOCUMENT_UPLOAD_FOLDER."/".'media_document'.'/';
					
					//echo '<br>target_dir='.$target_dir.'<br>';

					//Create sub-folder if not exist
					//Generate YYMMDD9999 article_id
					$YYMMDD =date('ymd');
					$file_path = $YYMMDD;
					$document_folder = $target_dir.'/'.$YYMMDD;
					//echo '<br>photo_folder='.$document_folder.'<br>';
					if (!file_exists($document_folder)) {
						mkdir($document_folder, 0777, true);
					}					
					
					$target_dir = $document_folder.'/';
					
					$original_file_name = $_FILES["uploadfile"]["name"];
					$document_type = $_FILES["uploadfile"]["type"];
					$file_size = $_FILES["uploadfile"]["size"];
					$path = $_FILES['uploadfile']['name'];
					$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
					//echo '<br>path='.$path.'<br>';
					//echo '<br>ext='.$ext.'<br>';
					
					if($original_file_name<>'') {						
						//$name_file = str_pad($last_insert_id, 5, '0', STR_PAD_LEFT);
						$rand_seeds = rand(100000,200000);
						
						$name_file = 'article_'.$article_id.'_document_'.$rand_seeds;
						$new_file_name_saved = $name_file.'.'.$ext;
						move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_dir.$new_file_name_saved);
						$file_path = 'media_document/'.$YYMMDD;
						$void=$dmGeneralModel->document_filename_update($last_insert_id,$new_file_name_saved,$file_size,$ext,$file_path,$original_file_name,$general['file_desc'], $_SESSION["sUserID"]);

						$message = 'Upload Document Successfully.';	
						 } else{
							 $message_fail = 'Empty Upload .';	 
						 }

					/* <END> handle document upload file */

					
					$primary = $dmGeneralModel->select($item_id);
					$arr_document = $dmGeneralModel->article_entry_document_listall($_GET["item_id"]);
					require __DIR__.'/../view/list_document_inc.php';
	
			} 
			else {
				$general = $_POST['general'] ;
				$primary = $dmGeneralModel->select($item_id);
				$arr_document = $dmGeneralModel->article_entry_document_listall($_GET["item_id"]);
				require __DIR__.'/../view/list_document_inc.php';
			}
		}
		
		
		break;		
		
		
		
	case "document_edit";		

		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];
		$doc_id=$_GET["doc_id"];
		$primary = $dmGeneralModel->select($_GET["item_id"]);
		$general = $dmGeneralModel->document_select($_GET["doc_id"]);
		require __DIR__.'/../view/edit_document_inc.php';

		break;		
		
		
		
	case "document_update";
	
		require __DIR__.'/common_paging_inc.php';
		$item_id = $_GET["item_id"];	
		$general = $_POST['general'] ;
		$doc_id=$_GET["doc_id"];		
		if ($_SERVER['REQUEST_METHOD'] == "POST" ) {
			$vlValidation = new document_validation('update',$dmGeneralModel);
				if($vlValidation->ValidateFormActionUpdate($item_id, $general)) {
					$void=$dmGeneralModel->document_update($doc_id, $_POST['general']);
					$primary = $dmGeneralModel->select($_GET["item_id"]);
					$arr_document = $dmGeneralModel->article_entry_document_listall($_GET["item_id"]);
					require __DIR__.'/../view/list_document_inc.php';
	
			} 
			else {
				$primary = $dmGeneralModel->select($_GET["item_id"]);
				$general = $dmGeneralModel->document_select($_GET["doc_id"]);
				require __DIR__.'/../view/edit_document_inc.php';

			}
		}
		
		
		break;		
		
		
		
	case "media_new_step_one";		

		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];	
		$primary = $dmGeneralModel->select($_GET["item_id"]);
		require __DIR__.'/../view/new_media_step_one_inc.php';
		break;
		
		
	case "media_new_step_two";  
	
		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];	
		$general = $_POST['general'] ;
		$general['display_priority'] = 0;
		$primary = $dmGeneralModel->select($_GET["item_id"]);
		require __DIR__.'/../view/new_media_step_two_inc.php';
		break;
	
		
	case "media_create";
	
		require __DIR__.'/common_paging_inc.php';
		$item_id = $_GET["item_id"];	
		$general = $_POST['general'] ;
		if ($_SERVER['REQUEST_METHOD'] == "POST" ) {
			$vlValidation = new media_validation('update',$dmGeneralModel);
				if($vlValidation->ValidateFormActionUpdate($item_id, $general)) {
					$last_insert_id=$dmGeneralModel->media_create($item_id, $_POST['general']);
					$article_id =$general['article_id'];
					$media_id = $last_insert_id;
					
					switch($general['media_type_id']) {

						case "1": // Photo
							/* handle large_jpeg upload file */
							$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
							$YYMMDD =date('ymd');
							$large_file_path = $YYMMDD;
							$photo_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($photo_folder)) {
								mkdir($photo_folder, 0777, true);
							}					
							$target_dir = $photo_folder.'/';
							$document_type = $_FILES["large_jpeg"]["type"];
							$file_size = $_FILES["large_jpeg"]["size"];
							$path = $_FILES['large_jpeg']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
								$rand_seeds = rand(100000,200000);
								//handle compress large to small jpeg file
								$small_file_name = 'article_'.$article_id.'_small_'.$rand_seeds;
								error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
								$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
								//copy large file to small file (before compress)
								$name_file = 'article_'.$article_id.'_small_'.$rand_seeds;
								$new_file_name_saved = $small_file_name.'.'.'jpeg';
								$void=$dmGeneralModel->media_photo_small_update($media_id,$general,$new_file_name_saved,0,'jpeg',$YYMMDD);
								//<end>handle compress large to small jpeg file
								
								//$name_file = $article_id.'_large_'.$rand_seeds;
								$name_file = 'article_'.$article_id.'_large_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
								$void=$dmGeneralModel->media_photo_large_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);
								$message = 'Upload Large Jpeg  Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload #99 fail.';	 
								 }

							/* <END> handle large_jpeg upload file */

							break;

						case "2": // Youtube Video
							$void=$dmGeneralModel->media_youtube_url_update($media_id,$general);
							/* handle large_jpeg upload file */
							$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
							$YYMMDD =date('ymd');
							$large_file_path = $YYMMDD;
							$photo_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($photo_folder)) {
								mkdir($photo_folder, 0777, true);
							}					
							$target_dir = $photo_folder.'/';
							$document_type = $_FILES["large_jpeg"]["type"];
							$file_size = $_FILES["large_jpeg"]["size"];
							$path = $_FILES['large_jpeg']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
								$rand_seeds = rand(100000,200000);
								//handle compress large to small jpeg file
								$small_file_name = 'article_'.$article_id.'_small_'.$rand_seeds;
								error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
								$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
								//copy large file to small file (before compress)
								$name_file = 'article_'.$article_id.'_small_'.$rand_seeds;
								$new_file_name_saved = $small_file_name.'.'.'jpeg';
								$void=$dmGeneralModel->media_photo_small_update($media_id,$general,$new_file_name_saved,0,'jpeg',$YYMMDD);
								//<end>handle compress large to small jpeg file
								
								//$name_file = $article_id.'_large_'.$rand_seeds;
								$name_file = 'article_'.$article_id.'_large_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
								$void=$dmGeneralModel->media_photo_large_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);
								$message = 'Upload Large Jpeg  Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload #99 fail.';	 
								 }

							/* <END> handle large_jpeg upload file */

							break;

							
						case "3": //Self Hosted Video
							/* handle large_jpeg upload file */
							$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
							$YYMMDD =date('ymd');
							$large_file_path = $YYMMDD;
							$photo_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($photo_folder)) {
								mkdir($photo_folder, 0777, true);
							}					
							$target_dir = $photo_folder.'/';
							$document_type = $_FILES["large_jpeg"]["type"];
							$file_size = $_FILES["large_jpeg"]["size"];
							$path = $_FILES['large_jpeg']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
								$rand_seeds = rand(100000,200000);
								//handle compress large to small jpeg file
								$small_file_name = 'article_'.$article_id.'_small_'.$rand_seeds;
								error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
								$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
								//copy large file to small file (before compress)
								$name_file = 'article_'.$article_id.'_small_'.$rand_seeds;
								$new_file_name_saved = $small_file_name.'.'.'jpeg';
								$void=$dmGeneralModel->media_photo_small_update($media_id,$general,$new_file_name_saved,0,'jpeg',$YYMMDD);
								//<end>handle compress large to small jpeg file
								
								//$name_file = $article_id.'_large_'.$rand_seeds;
								$name_file = 'article_'.$article_id.'_large_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
								$void=$dmGeneralModel->media_photo_large_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);
								$message = 'Upload Large Jpeg  Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload #99 fail.';	 
								 }

							/* <END> handle large_jpeg upload file */
						
							/* handle  video upload file */
							$primary = $dmGeneralModel->select($_GET["item_id"]);
							$article_id =$item_id;
							$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
							$YYMMDD =date('ymd');
							$file_path = $YYMMDD;
							$document_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($document_folder)) {
								mkdir($document_folder, 0777, true);
							}					
							$target_dir = $document_folder.'/';
							$original_file_name = $_FILES["videofile"]["name"];
							$document_type = $_FILES["videofile"]["type"];
							$file_size = $_FILES["videofile"]["size"];
							$path = $_FILES['videofile']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if($original_file_name<>'') {						
								$rand_seeds = rand(100000,200000);
								$name_file = 'article_'.$article_id.'_video_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['videofile']['tmp_name'], $target_dir.$new_file_name_saved);
								$file_path = $YYMMDD;
								$void=$dmGeneralModel->media_self_hosted_video_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$file_path,$original_file_name);
								$message = 'Upload Video Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload .';	 
								 }
							/* <end> handle  video upload file */
						
							break;						
							
							
							
						case "4":  //Reference URL
							$void=$dmGeneralModel->media_reference_url_update($media_id, $_POST['general']);
							break;						

							
						case "5":  //Upload Document
							/* handle  document upload file */
							$primary = $dmGeneralModel->select($_GET["item_id"]);
							$article_id =$item_id;
							$target_dir = DIR_DOCUMENT_UPLOAD_FOLDER."/".'media_document'.'/';
							$YYMMDD =date('ymd');
							$file_path = $YYMMDD;
							$document_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($document_folder)) {
								mkdir($document_folder, 0777, true);
							}					
							$target_dir = $document_folder.'/';
							$original_file_name = $_FILES["uploadfile"]["name"];
							$document_type = $_FILES["uploadfile"]["type"];
							$file_size = $_FILES["uploadfile"]["size"];
							$path = $_FILES['uploadfile']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if($original_file_name<>'') {						
								$rand_seeds = rand(100000,200000);
								$name_file = 'article_'.$article_id.'_document_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_dir.$new_file_name_saved);
								$file_path = 'media_document/'.$YYMMDD;
								$void=$dmGeneralModel->media_document_upload_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$file_path,$original_file_name);
								$message = 'Upload Document Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload .';	 
								 }
							/* <end> handle  document upload file */
							break;

					};
						
					
					$primary = $dmGeneralModel->select($item_id);
					$arr_media = $dmGeneralModel->media_list($_GET["item_id"]);
					require __DIR__.'/../view/list_media_inc.php';
	
			} 
			else {
				$primary = $dmGeneralModel->select($item_id);
				$general = $_POST['general'] ;
				$arr_media = $dmGeneralModel->media_list($_GET["item_id"]);
				require __DIR__.'/../view/list_media_inc.php';
			}
		}
		
		
		break;		
		
		
	case "media_edit";		

		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];
		$media_id=$_GET["media_id"];
		$primary = $dmGeneralModel->select($_GET["item_id"]);
		$general = $dmGeneralModel->media_select($media_id);
		require __DIR__.'/../view/edit_media_inc.php';

		break;		
		
		
	case "media_update";
	
		require __DIR__.'/common_paging_inc.php';
		$item_id = $_GET["item_id"];	
		$general = $_POST['general'] ;
		$media_id=$_GET["media_id"];		
		if ($_SERVER['REQUEST_METHOD'] == "POST" ) {
			$vlValidation = new media_validation('update',$dmGeneralModel);
				if($vlValidation->ValidateFormActionUpdate($item_id, $general)) {
					$void=$dmGeneralModel->media_update($media_id, $_POST['general']);

					switch($general['media_type_id']) {
						case "1": //Photo
							/* handle large_jpeg upload file */
							$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
							$YYMMDD =date('ymd');
							$large_file_path = $YYMMDD;
							$photo_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($photo_folder)) {
								mkdir($photo_folder, 0777, true);
							}					
							$target_dir = $photo_folder.'/';
							$document_type = $_FILES["large_jpeg"]["type"];
							$file_size = $_FILES["large_jpeg"]["size"];
							$path = $_FILES['large_jpeg']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
								$rand_seeds = rand(100000,200000);
								//handle compress large to small jpeg file
								$small_file_name = 'article_'.$article_id.'_small_'.$rand_seeds;
								error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
								$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
								//copy large file to small file (before compress)
								$name_file = 'article_'.$article_id.'_small_'.$rand_seeds;
								$new_file_name_saved = $small_file_name.'.'.'jpeg';
								$void=$dmGeneralModel->media_photo_small_update($media_id,$general,$new_file_name_saved,0,'jpeg',$YYMMDD);
								//<end>handle compress large to small jpeg file
								
								//$name_file = $article_id.'_large_'.$rand_seeds;
								$name_file = 'article_'.$article_id.'_large_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
								$void=$dmGeneralModel->media_photo_large_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);
								$message = 'Upload Large Jpeg  Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload #99 fail.';	 
								 }

							/* <END> handle large_jpeg upload file */
						
							break;
							
						case "2": //Youtube video
							$void=$dmGeneralModel->media_youtube_url_update($media_id,$general);
							/* handle large_jpeg upload file */
							$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
							$YYMMDD =date('ymd');
							$large_file_path = $YYMMDD;
							$photo_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($photo_folder)) {
								mkdir($photo_folder, 0777, true);
							}					
							$target_dir = $photo_folder.'/';
							$document_type = $_FILES["large_jpeg"]["type"];
							$file_size = $_FILES["large_jpeg"]["size"];
							$path = $_FILES['large_jpeg']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
								$rand_seeds = rand(100000,200000);
								//handle compress large to small jpeg file
								$small_file_name = 'article_'.$article_id.'_small_'.$rand_seeds;
								error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
								$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
								//copy large file to small file (before compress)
								$name_file = 'article_'.$article_id.'_small_'.$rand_seeds;
								$new_file_name_saved = $small_file_name.'.'.'jpeg';
								$void=$dmGeneralModel->media_photo_small_update($media_id,$general,$new_file_name_saved,0,'jpeg',$YYMMDD);
								//<end>handle compress large to small jpeg file
								
								//$name_file = $article_id.'_large_'.$rand_seeds;
								$name_file = 'article_'.$article_id.'_large_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
								$void=$dmGeneralModel->media_photo_large_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);
								$message = 'Upload Large Jpeg  Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload #99 fail.';	 
								 }

							/* <END> handle large_jpeg upload file */
						
							break;						
							
						case "3": //Self Hosted Video
							/* handle large_jpeg upload file */
							$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
							$YYMMDD =date('ymd');
							$large_file_path = $YYMMDD;
							$photo_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($photo_folder)) {
								mkdir($photo_folder, 0777, true);
							}					
							$target_dir = $photo_folder.'/';
							$document_type = $_FILES["large_jpeg"]["type"];
							$file_size = $_FILES["large_jpeg"]["size"];
							$path = $_FILES['large_jpeg']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
								$rand_seeds = rand(100000,200000);
								//handle compress large to small jpeg file
								$small_file_name = 'article_'.$article_id.'_small_'.$rand_seeds;
								error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
								$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
								//copy large file to small file (before compress)
								$name_file = 'article_'.$article_id.'_small_'.$rand_seeds;
								$new_file_name_saved = $small_file_name.'.'.'jpeg';
								$void=$dmGeneralModel->media_photo_small_update($media_id,$general,$new_file_name_saved,0,'jpeg',$YYMMDD);
								//<end>handle compress large to small jpeg file
								
								//$name_file = $article_id.'_large_'.$rand_seeds;
								$name_file = 'article_'.$article_id.'_large_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
								$void=$dmGeneralModel->media_photo_large_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);
								$message = 'Upload Large Jpeg  Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload #99 fail.';	 
								 }

							/* <END> handle large_jpeg upload file */
						
							/* handle  video upload file */
							$primary = $dmGeneralModel->select($_GET["item_id"]);
							$article_id =$item_id;
							$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
							$YYMMDD =date('ymd');
							$file_path = $YYMMDD;
							$document_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($document_folder)) {
								mkdir($document_folder, 0777, true);
							}					
							$target_dir = $document_folder.'/';
							$original_file_name = $_FILES["videofile"]["name"];
							$document_type = $_FILES["videofile"]["type"];
							$file_size = $_FILES["videofile"]["size"];
							$path = $_FILES['videofile']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if($original_file_name<>'') {						
								$rand_seeds = rand(100000,200000);
								$name_file = 'article_'.$article_id.'_video_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['videofile']['tmp_name'], $target_dir.$new_file_name_saved);
								$file_path = $YYMMDD;
								$void=$dmGeneralModel->media_self_hosted_video_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$file_path,$original_file_name);
								$message = 'Upload Video Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload .';	 
								 }
							/* <end> handle  video upload file */
						
							break;						
							
							
						case "4":  //Reference URL
							$void=$dmGeneralModel->media_reference_url_update($media_id, $_POST['general']);
							break;						
							
						case "5":  //Upload Document
							/* handle  document upload file */
							$primary = $dmGeneralModel->select($_GET["item_id"]);
							$article_id =$item_id;
							$target_dir = DIR_DOCUMENT_UPLOAD_FOLDER."/".'media_document'.'/';
							$YYMMDD =date('ymd');
							$file_path = $YYMMDD;
							$document_folder = $target_dir.'/'.$YYMMDD;
							if (!file_exists($document_folder)) {
								mkdir($document_folder, 0777, true);
							}					
							$target_dir = $document_folder.'/';
							$original_file_name = $_FILES["uploadfile"]["name"];
							$document_type = $_FILES["uploadfile"]["type"];
							$file_size = $_FILES["uploadfile"]["size"];
							$path = $_FILES['uploadfile']['name'];
							$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
							if($original_file_name<>'') {						
								$rand_seeds = rand(100000,200000);
								$name_file = 'article_'.$article_id.'_document_'.$rand_seeds;
								$new_file_name_saved = $name_file.'.'.$ext;
								move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_dir.$new_file_name_saved);
								$file_path = 'media_document/'.$YYMMDD;
								$void=$dmGeneralModel->media_document_upload_update($media_id,$general,$new_file_name_saved,$file_size,$ext,$file_path,$original_file_name);
								$message = 'Upload Document Successfully.';	
								 } else{
									 $message_fail = 'Empty Upload .';	 
								 }
							/* <end> handle  document upload file */
							break;

					};
						
					
					$primary = $dmGeneralModel->select($item_id);
					$arr_media = $dmGeneralModel->media_list($_GET["item_id"]);
					require __DIR__.'/../view/list_media_inc.php';
	
			} 
			else {
					$primary = $dmGeneralModel->select($item_id);
					$arr_media = $dmGeneralModel->media_list($_GET["item_id"]);
					require __DIR__.'/../view/list_media_inc.php';

			}
		}
		
		
		break;		
		
		
		
	default:
		header('Status: 404 Not Found');
		echo '<html><body><h1>Page Not Found, Please contact System Support</h1></body></html>';
		break;
}

$dmGeneralModel = $dmGeneralModel->close();  
?>