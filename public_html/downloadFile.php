<?php
session_start();
//require_once __DIR__.'/../env.php';  
DEFINE('DIR_PUBLIC_HTML', __DIR__.'/../document');



if(!$_SESSION["sUserID"]) {
	echo 'no session<br>';
	exit;
}
?>
<?php
		//$filename ='012345678_1.jpg';
		//$folder ='driver_upload/2016';
		$filename =$_GET['filename'];
		$path =$_GET['path'];
		$full_path =DIR_PUBLIC_HTML.'/'.$path.'/'.$filename;
		//echo 'full_path ='.$full_path.'<br>';
		//die();
		if(file_exists($full_path) && is_file($full_path)) {	
			header('Content-Description: File Transfer');
			header("Content-Type: application/octet-stream");
			//header("Content-Type: application/image/jpeg");
			header('Content-Length: '.filesize($full_path));
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Content-Disposition: attachment; filename=".$filename);
			readfile($full_path);
			exit;
		}
		else {
			die('Error: The file '.$full_path.' does not exist!');
		}
?>