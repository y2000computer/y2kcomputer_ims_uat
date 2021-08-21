<?php
require __DIR__.'/../../../../function/controller_func.php';
require __DIR__.'/../validation/general_validation.php';


$dmWwwModel = new www_model();  //Open database connection
		
$preview = 'no';
if(isset($_GET["preview"]))  $preview = $_GET["preview"];

if(isset($_COOKIE['kfontsize'])) $font = $_COOKIE['kfontsize'];
//echo '<br> font cookie = '.$font.'<br>';


//$season_id = 0;
if(isset($_GET["season_id"]))  $season_id = $_GET["season_id"];
//echo 'para season_id  = '. $season_id .'<br>';

$arr_season_master = $dmWwwModel->season_viewall($preview); 

if($season_id <> 0) {
	$arr_season = $dmWwwModel->season_select($season_id); 
}
if($season_id == 0) {
	$arr_season = $dmWwwModel->season_last_select($preview); 
	$season_id =$arr_season['season_id'];
}

$arr_season_last_three = $dmWwwModel->season_last_three_view($season_id, $preview); 

$arr_category = $dmWwwModel->category_view($season_id, $preview); 

		
		
switch($IS_action)
{
	case "/";
		require __DIR__.'/../view/index_inc.php';
		break;

	case "view";
		$article_id = $_GET["article_id"];
		$preview = $_GET["preview"];
		$season_id = $_GET["season_id"];
		$article = $dmWwwModel->media_article_select($article_id); 
	    $arr_media_main_inner_is = $dmWwwModel->article_media_viewall($article_id, $main_inner_is=1);
	    $arr_media = $dmWwwModel->article_media_viewall($article_id, $main_inner_is=0);
	
		require __DIR__.'/../view/view_inc.php';
		break;

		
		
	default:
		header('Status: 404 Not Found');
		echo '<html><body><h1>Page Not Found, Please contact System Support</h1></body></html>';
		break;
}

$dmWwwModel = $dmWwwModel->close();  
?>