<?php
require __DIR__.'/../../../../function/check_session_func.php';
require __DIR__.'/../../../../function/controller_func.php';
require __DIR__.'/../validation/general_validation.php';
require __DIR__.'/../validation/document_validation.php';


$checkaccess =  new checkaccess_model(); 
list($allow,$level,$add,$change,$delete) = $checkaccess->checkRight('PHOTO-TRAN-01-001');
if($allow == 0) require __DIR__.'/../../../../template/sorry_inc.php';

$dmGeneralModel = new photo_entry_model();  //Open database connection
$arr_photo_meta_category = $dmGeneralModel->photo_meta_category_viewall(); 
		
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
				$panelsize = 'small';		//default for photo entry different panel view only 
		} else {
				$lot_id=$_GET["lot_id"];
				$page=$_GET["page"];		
				$panelsize = $_GET["panelsize"]; 		//for photo entry different panel view only 
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
				//$primary = $dmGeneralModel->select($item_id);
				$general = $dmGeneralModel->select($item_id);
				$arr_entry_meta_category_sub = $dmGeneralModel->entry_meta_category_sub($item_id);
				//$tab  = 'subcategory';
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

		if($tab=='general' ||  $tab=='') {
			$arr_entry_meta_category_sub = $dmGeneralModel->entry_meta_category_sub($_GET["item_id"]);
			require __DIR__.'/../view/edit_inc.php';
		}	
		if($tab=='document') {
			$primary = $dmGeneralModel->select($_GET["item_id"]);
			$arr_document = $dmGeneralModel->photo_entry_document_listall($_GET["item_id"]);
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
					$photo_code =$general['photo_code'];

					/* handle large_jpeg upload file */
					//$target_dir = DIR_PUBLIC_UPLOAD."/cs_document/";
					$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
					//echo '<br>target_dir='.$target_dir.'<br>';

					//Create sub-folder if not exist
					//Generate YYMMDD9999 photo_code
					$YYMMDD =date('ymd');
					$large_file_path = $YYMMDD;
					$photo_folder = $target_dir.'/'.$YYMMDD;
					//echo '<br>photo_folder='.$photo_folder.'<br>';
					if (!file_exists($photo_folder)) {
						mkdir($photo_folder, 0777, true);
					}					
					
					$target_dir = $photo_folder.'/';
					
					$doc_id = $item_id;
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
						$small_file_name = $photo_code.'_small_'.$rand_seeds;
						error_reporting(E_ERROR | E_PARSE ); //avoid image warning preg_match
						$void = resizeImg($_FILES['large_jpeg'],$small_file_name,$photo_folder,1024,false);
						
						//copy large file to small file (before compress)
						$name_file = $photo_code.'_small_'.$rand_seeds;
						$new_file_name_saved = $small_file_name.'.'.'jpeg';
						$void=$dmGeneralModel->small_jpeg_filename_update($item_id,$new_file_name_saved,0,'jpeg',$YYMMDD,$_SESSION["sUserID"]);
						
						//<end>handle compress large to small jpeg file
						
						$name_file = $photo_code.'_large_'.$rand_seeds;
						$new_file_name_saved = $name_file.'.'.$ext;
						//move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
						move_uploaded_file($_FILES['large_jpeg']['tmp_name'], $target_dir.$new_file_name_saved);
						
						$void=$dmGeneralModel->large_jpeg_filename_update($item_id,$new_file_name_saved,$file_size,$ext,$large_file_path,$_SESSION["sUserID"]);

						
						
						$message = 'Upload Large Jpeg  Successfully.';	
						 } else{
							 $message_fail = 'Empty Upload #99 fail.';	 
						 }

					//handle small_jpeg
					if(strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {						
					}
					/* <END> handle large_jpeg upload file */

					
					/* handle photo_psd upload file */

					$target_dir = DIR_PHOTOS_UPLOAD_FOLDER."/";
					//echo '<br>target_dir='.$target_dir.'<br>';

					//Create sub-folder if not exist
					//Generate YYMMDD9999 photo_code
					$YYMMDD =date('ymd');
					$photo_psd_file_path = $YYMMDD;
					$photo_psd_folder = $target_dir.'/'.$YYMMDD;
					//echo '<br>photo_psd_folder='.$photo_psd_folder.'<br>';
					if (!file_exists($photo_psd_folder)) {
						mkdir($photo_psd_folder, 0777, true);
					}					
					
					$target_dir = $photo_psd_folder.'/';
					
					$doc_id = $item_id;
					$document_type = $_FILES["photo_psd"]["type"];
					$file_size = $_FILES["photo_psd"]["size"];
					$path = $_FILES['photo_psd']['name'];
					$ext = pathinfo($path, PATHINFO_EXTENSION);
					
					//if($file_size >= 0){
					if(strtolower($ext) == 'psd' ){
						//$name_file = str_pad($last_insert_id, 5, '0', STR_PAD_LEFT);
						$rand_seeds = rand(100000,200000);
						$name_file = $photo_code.'_psd_'.$rand_seeds;
						$new_file_name_saved = $name_file.'.'.$ext;
						//move_uploaded_file($_FILES['photo_psd']['tmp_name'], $target_dir.$new_file_name_saved);
						move_uploaded_file($_FILES['photo_psd']['tmp_name'], $target_dir.$new_file_name_saved);
						$void=$dmGeneralModel->photo_psd_filename_update($item_id,$new_file_name_saved,$file_size,$ext,$photo_psd_file_path,$_SESSION["sUserID"]);
						$message = 'Upload Photo PSD  Successfully.';	
						 } else{
							 $message_fail = 'Empty Upload #99 fail.';	 
						 }

					//handle small_jpeg
					if($file_size >= 0){

					}
					
					

					/* <END> handle photo_psd upload file */
					
					$general = $dmGeneralModel->select($item_id);
					$arr_entry_meta_category_sub = $dmGeneralModel->entry_meta_category_sub($_GET["item_id"]);
					require __DIR__.'/../view/edit_inc.php';
	
			} 
			else {
				$general = $_POST['general'] ;
				$arr_entry_meta_category_sub = $dmGeneralModel->entry_meta_category_sub($_GET["item_id"]);
				require __DIR__.'/../view/edit_inc.php';
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
					$photo_code =$primary['photo_code'];

					$target_dir = DIR_DOCUMENT_UPLOAD_FOLDER."/".'photos_document'.'/';
					
					//echo '<br>target_dir='.$target_dir.'<br>';

					//Create sub-folder if not exist
					//Generate YYMMDD9999 photo_code
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
						
						$name_file = $photo_code.'_document_'.$rand_seeds;
						$new_file_name_saved = $name_file.'.'.$ext;
						move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_dir.$new_file_name_saved);
						$file_path = 'photos_document/'.$YYMMDD;
						$void=$dmGeneralModel->document_filename_update($last_insert_id,$new_file_name_saved,$file_size,$ext,$file_path,$original_file_name,$general['file_desc'], $_SESSION["sUserID"]);

						$message = 'Upload Document Successfully.';	
						 } else{
							 $message_fail = 'Empty Upload .';	 
						 }

					/* <END> handle document upload file */

					
					$primary = $dmGeneralModel->select($item_id);
					$arr_document = $dmGeneralModel->photo_entry_document_list($_GET["item_id"]);
					require __DIR__.'/../view/list_document_inc.php';
	
			} 
			else {
				$general = $_POST['general'] ;
				$subcategory = $_POST['subcategory'] ;
				$primary = $dmGeneralModel->select($item_id);
				$arr_document = $dmGeneralModel->photo_entry_document_list($_GET["item_id"]);				
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
					$arr_document = $dmGeneralModel->photo_entry_document_listall($_GET["item_id"]);
					require __DIR__.'/../view/list_document_inc.php';
	
			} 
			else {
				$primary = $dmGeneralModel->select($_GET["item_id"]);
				$general = $dmGeneralModel->document_select($_GET["doc_id"]);
				require __DIR__.'/../view/edit_document_inc.php';

			}
		}
		
		
		break;		
		

	case "ajax_photo_preview_window";
	
		require __DIR__.'/common_paging_inc.php';
	
		$photo_id = $_GET["option"];
		$general = $dmGeneralModel->select($_GET["option"]);
		$general_model = $general;
		$arr_photo_entry_meta_category_distinct_list = $dmGeneralModel->photo_entry_meta_category_distinct_list($_GET["option"]);
		$arr_photo_document_list = $dmGeneralModel->photo_entry_document_list($_GET["option"]);
		require __DIR__.'/../view/photo_preview_window_inc.php';
	
		break;		
		

		
	default:
		header('Status: 404 Not Found');
		echo '<html><body><h1>Page Not Found, Please contact System Support</h1></body></html>';
		break;
}

$dmGeneralModel = $dmGeneralModel->close();  
?>