<?php
require __DIR__.'/../../../../function/controller_func.php';
require __DIR__.'/../validation/general_validation.php';


$dmApi_Article_Model = new api_article_model();  //Open database connection
$arr_articles =  $dmApi_Article_Model->article_viewall();

$pcode = '';
if(isset($_GET["pcode"])) {
	$pcode = $_GET["pcode"]; 
		} else {
		$pcode = 'DEFAULT';
	}		
		
switch($IS_action)
{
	case "/";
		require __DIR__.'/../view/index_inc.php';
		break;

	default:
		header('Status: 404 Not Found');
		echo '<html><body><h1>Page Not Found, Please contact System Support</h1></body></html>';
		break;
}

$dmApi_Article_Model = $dmApi_Article_Model->close();  
?>