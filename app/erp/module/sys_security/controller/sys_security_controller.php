<?php
require __DIR__.'/../../sys_security/validation/security_validation.php';
require __DIR__.'/../../../../function/controller_func.php';

$dmSecurity = new sys_log_login_model();  //Open datatabase connection
$dmURLlog = new sys_log_url_model();  //Open datatabase connection


switch($IS_action)
{
	case "/";
		$security = $dmSecurity->login_newset();
		require __DIR__.'/../view/login_inc.php';
		break;
		
	case "login";
		$security = $dmSecurity->login_newset();
		require __DIR__.'/../view/login_inc.php';
		break;
		
	case "checklogin";
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$vlValidation = new security_validation('checklogin',$dmSecurity);
			if($vlValidation->ValidateForm($_POST['security'])) {
				$dmSecurity->updateLastVisitDate($_POST['security']);
				$security = $_POST['security'];
				$_SESSION['security']['sUserID'] = $security['sUserID'];
				//SUCCESS
				$time = date("Y-m-d H:i:s");
				$source_ip = $_SESSION["source_ip"];
				$browser_type = $_SESSION["browser_type"];
				$url = $_SESSION["url"];
				$dmSecurity->writesuccess($security['sUserID'], $security['sPassword'], $source_ip, $browser_type, $url, $time);							

				//default page redirection, base on menu order and module order
				list($goto_app,$goto_module) = getMainMenu($_SESSION["policy_module"]);

				//$redirectURL = moduleURL($first_menu,'http');
				//$redirectURL = actionURLWithModule('index','',$goto_module);
				$redirectURL = actionURLWithApplication('index','',$goto_app, $goto_module,'http');

				header('Location: '.$redirectURL); 
				exit();
				}
				else {
					$security = $_POST['security'];
					require __DIR__.'/../view/login_inc.php';
			}
		}
		break;

	case "logout";
		$security = $dmSecurity->login_newset();
		require __DIR__.'/../view/logout_inc.php';
		break;
		
		
	case "edit_password";
		require __DIR__.'/../view/edit_password_inc.php';
		break;
		

	case "password_update";
	
		//$lot_id = $_GET["lot_id"];
		//$item_id = $_GET["item_id"];	
			
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$vlValidation = new security_validation('update',$dmSecurity);
				if($vlValidation->Validate_Change_Password_Form($_POST['general'])) {
					$void=$dmSecurity->password_update($_POST['general'],$_SESSION["sUserID"]);
		
			require __DIR__.'/../view/password_updated_inc.php';
	
			} 
			else {
				$general = $_POST['general'];
				require __DIR__.'/../view/edit_password_inc.php';
			}
		} else{
				$general = $_POST['general'];
				require __DIR__.'/../view/edit_password_inc.php';
		
		}
		break;
		
	
	case "view_company":
		$arr_company = array();
		$arr_company=$dmSecurity->company_select($_SESSION["sUserID"]);
		require __DIR__."'/../view/view_company_inc.php";				
	
		break;
		

	case "target_company":
		$_SESSION["target_comp_id"] = $_GET['comp_id'];
		$_SESSION["target_comp_name"] = $_GET['name_eng'];
		$arr_company = array();
		$arr_company=$dmSecurity->company_select($_SESSION["sUserID"]);
		require __DIR__."'/../view/target_company_inc.php";				
	
		break;
		
		
	default:
		header('Status: 404 Not Found');
		echo '<html><body><h1>Page Not Found, Please contact System Support</h1></body></html>';
		break;
}

	
		$time = date("Y-m-d H:i:s");
		$source_ip = $_SESSION["source_ip"];
		$browser_type = $_SESSION["browser_type"];
		$url = $_SESSION["url"];
		$session_id = session_id();
		$dmURLlog->url_log($security['sUserID'], $url, $browser_type, $source_ip, $session_id, $time);	

		$dmSecurity = $dmSecurity->close();  //Close datatabase connection
		$dmURLlog = $dmURLlog->close();  //Close datatabase connection




?>