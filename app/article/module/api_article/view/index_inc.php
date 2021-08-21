<?php
// 设置允许其他域名访问
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: '.ALLOW_ORIGIN_DOMAIN);
// 设置允许的响应类型 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
// 设置允许的响应头 
header('Access-Control-Allow-Headers:x-requested-with,content-type'); 

foreach ($arr_articles as $arr_article): 
	//echo 'title'.':'.$arr_article['title'].'<br>';
endforeach; 
					
//$map_folder_link ='/map_folder_link';
//create an array
$emparray = array();
foreach ($arr_articles as $arr_article): 
	{
	$media = $dmApi_Article_Model->media_main_select($arr_article['article_id']); 
	//$photo_url =$map_folder_link.'/'. $media['photo_large_file_path']. '/'. $media['photo_large_file_name'];
	//$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $media['photo_large_file_path']. '/'. $media['photo_large_file_name'];
	$photo_url =API_DOMAIN.'/'.DIR_PHOTOS_PUBLIC_HTML.'/'. $media['photo_small_file_path']. '/'. $media['photo_small_file_name'];

	$arr_article['imgs'] = $photo_url;

	//$arr_article['imgs'] = '['.'"'.$photo_url.'"'.']';
	

	
	$arr_article['link'] .= '?utm_source=bloompon_portal&utm_medium=bloompon_coupon_channel';
	$arr_article['link'] .= '&utm_campaign=' . $arr_article['seasonName']; 
	$arr_article['link'] .= '&utm_content=' . $pcode; 
	
	$emparray[] = $arr_article;
	}
endforeach; 

header('Content-Type: application/json');
echo json_encode($emparray);
//$json = json_encode($emparray);	

//var_dump(json_decode($json));
//var_dump(json_decode($json, true));


?>
