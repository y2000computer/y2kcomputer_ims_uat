<?php
function actionURL($action='index',$para='',$protocol='http',$module=IS_MODULE){
	if ($protocol=='')$protocol=='http';
	if ($protocol=='http') $url_protocol ='http://';
	if ($protocol=='https') $url_protocol ='https://';
	
	$url_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	
	$url_protocol =''; //cloudflare
	
	if ($action=='index') {
		//$actionURL = $url_protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/'.IS_PORTAL.'/'.IS_LANG.'/'.$module;
					
		$actionURL = '/'.IS_PORTAL.'/'.IS_LANG.'/'.$module; //cloudflare
					
	}else {
		//$actionURL = $url_protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/'.	IS_PORTAL.'/'.IS_LANG.'/'.$module.'/'.$action.'/'.$para;
		$actionURL = '/'.	IS_PORTAL.'/'.IS_LANG.'/'.$module.'/'.$action.'/'.$para; //cloudflare
		
	}	
	return $actionURL;
}

function actionURLWithModule($action='index',$para='',$module=IS_MODULE,$protocol='http'){
	if ($protocol=='')$protocol=='http';
	if ($protocol=='http') $url_protocol ='http://';
	if ($protocol=='https') $url_protocol ='https://';

	$url_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	$url_protocol =''; //cloudflare
	
	if ($action=='index') {
		//$actionURL = $url_protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/'.	IS_PORTAL.'/'.IS_LANG.'/'.$module;
		$actionURL = '/'.	IS_PORTAL.'/'.IS_LANG.'/'.$module;  //cloudflare
		
		if ($para)$actionURL.=$para;
	}else {
		//$actionURL = $url_protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/'.	IS_PORTAL.'/'.IS_LANG.'/'.$module.'/'.$action.'/'.$para;
		$actionURL = '/'.	IS_PORTAL.'/'.IS_LANG.'/'.$module.'/'.$action.'/'.$para;	//cloudflare	
	}	
	return $actionURL;
}


function actionURLWithApplication($action='index',$para='',$app=IS_PORTAL, $module=IS_MODULE,$protocol='http'){
	if ($protocol=='')$protocol=='http';
	if ($protocol=='http') $url_protocol ='http://';
	if ($protocol=='https') $url_protocol ='https://';

	$url_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	$url_protocol =''; //cloudflare
	
	if ($action=='index') {
		//$actionURL = $url_protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/'.	IS_PORTAL.'/'.IS_LANG.'/'.$module;
		$actionURL = '/'. $app .'/'.IS_LANG.'/'.$module;  //cloudflare
		
		if ($para)$actionURL.=$para;
	}else {
		//$actionURL = $url_protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/'.	IS_PORTAL.'/'.IS_LANG.'/'.$module.'/'.$action.'/'.$para;
		$actionURL = '/'. $app .'/'.IS_LANG.'/'.$module.'/'.$action.'/'.$para;	//cloudflare	
	}	
	return $actionURL;
}



function moduleURL($module='',$protocol='http'){
	if ($protocol=='http') $url_protocol ='http://';
	if ($protocol=='https') $url_protocol ='https://';

	//$url_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	$url_protocol =''; //cloudflare
	
	//$moduleURL = $url_protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/'.IS_PORTAL.'/'.IS_LANG.'/'.$module.'';
	$moduleURL = '/'.IS_PORTAL.'/'.IS_LANG.'/'.$module.''; //cloudflare
	
	return $moduleURL;
}

function domain_URL($protocol='http'){
	$http_https = (empty($_SERVER['HTTPS']) ? "http://" : "https://");
	$actionURL = $http_https.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/';
	return $actionURL;
}

function loginURL($protocol='http'){
	if ($protocol=='http') $url_protocol ='http://';
	if ($protocol=='https') $url_protocol ='https://';

	$url_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url_protocol =''; //cloudflare
	
	//$loginURL = $url_protocol.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/'.IS_PORTAL.'/'.IS_LANG.'/sys_security/login';
	$loginURL = '/'.IS_PORTAL.'/'.IS_LANG.'/sys_security/login'; //cloudflare
	
	return $loginURL;
}

function _encode($text) 
{ 
	$debug = 'off';
	if(isset($_SESSION['debug'])) $debug = $_SESSION['debug'];

    if (!DEBUG_MODE && $debug == 'off') {
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
    } else {
        return $text;
     }
} 

function _decode($text) 
{ 
	$debug = 'off';
	if(isset($_SESSION['debug'])) $debug = $_SESSION['debug'];

    if (!DEBUG_MODE && $debug == 'off') {
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
    } else {
        return $text;
     }
} 


function myEach(&$arr) {
	$key = key($arr);
	$result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
	next($arr);
	return $result;
}

function get_client_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { // check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { // to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}



function getMainMenu($user_policy_module_grant)
{
	$module_code_array = array();
	$moudule_array = array();
	$policy_module = $user_policy_module_grant;
	$module = explode(",", $policy_module);
	
	
	$module_count = count($module);
	$order_num = 0;
	$init_num = 100;
	$section_set = 0;
	
	for($x=0; $x<$module_count; $x++)
	{
		$result = $module[$x];
		$module_result = explode("-", $result);
		$section = $module_result[0];
	
	
		
		if($section == 'PHOTO'){
			$order_num = 1;
			$section_set = 'photo_home';
			}
	
	
		if($section == 'ARTICLE'){
			$order_num = 2;
			$section_set = 'article_home';
			}
	
	
		if($section == 'GL'){
			$order_num = 3;
			$section_set = 'gl_home';
			}
	
			
		if($section == 'SYS'){
			$order_num = 4;
			$section_set = 'sys_home';
			} 
	
	
					
		if ($order_num < $init_num ){
			$init_num = $order_num;
			$first_item = $init_num;
			$first_menu = $section_set;
		}
			
		//$first_menu = 'gl_home';
		//$first_menu = 'sys_home';
			
	} //for($x=0; $x<$module_count; $x++)				
	
	$goto_app ='';
	$goto_module ='';
	switch($first_menu)
	{
		case "photo_home";
			$goto_app ='photolibrary';
			$goto_module ='photo_home';
			
			break;
		case "article_home";
			$goto_app ='article';
			$goto_module ='article_home';
			break;
		case "gl_home";
			$goto_app ='erp';
			$goto_module ='gl_home';
			break;
		case "SYS";
			$goto_app ='erp';
			$sys_home ='gl_home';
			break;
	}
	
    return array($goto_app,$goto_module);
}
?>