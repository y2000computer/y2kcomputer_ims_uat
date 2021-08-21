<?php
require __DIR__.'/../../../../function/check_session_func.php';
require __DIR__.'/../../../../function/controller_func.php';
require __DIR__.'/../validation/general_validation.php';

$checkaccess =  new checkaccess_model(); 
list($allow,$level,$add,$change,$delete) = $checkaccess->checkRight('SYS-MAINT-01-001');
if($allow == 0) require __DIR__.'/../../../../template/sorry_inc.php';

$dmGeneralModel = new sys_company_master_model();  //Open database connection

		
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
				$general = $dmGeneralModel->select($item_id);
				require __DIR__.'/../view/edit_inc.php';
			} else {
				require __DIR__.'/../view/new_inc.php';
			}	
		}
		
		break;		
		
	
	case "edit";

		require __DIR__.'/common_paging_inc.php';
		$item_id=$_GET["item_id"];
		$general = $dmGeneralModel->select($item_id);
		require __DIR__.'/../view/edit_inc.php';
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
					require __DIR__.'/../view/edit_inc.php';
	
			} 
			else {
				$general = $_POST['general'] ;
				require __DIR__.'/../view/edit_inc.php';
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


