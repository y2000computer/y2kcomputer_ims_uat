<?php
require __DIR__.'/../../../template/header_www_inc.php';
?>

<!--<body onload="checkCookie()">-->
<body>


    <header>
    	<div class="container text-center">
    		<a class="navbar-brand" href="#"><img src="/assets/img/logo.png" alt="logo" height="56" /></a>
    	</div>
    </header>
	

    <main role="main">
		<?php require __DIR__.'/navigation_menu_inc.php'; ?>
        
        
        <div class="page-holder">
        	<div  class="container" >
        		<div class="article-detail">
        			<table>
        				<tr>
        					<td class="pr-3" valign="top">
							<?php //echo '['.YMDHMSto_D_M($article['article_date']).']'.'&nbsp;&nbsp;';?>
							<?php //echo YMDHMStoY_M_D($article['article_date']).'&nbsp;';?>
							<?php echo date("n",strtotime($article['article_date'])).'月'.date("j",strtotime($article['article_date'])).'日'.'&nbsp;';?>

							<?php echo $article['headline'];?>
							</td>
        				</tr>
        			</table>
        		
        		<hr/>
				
				
				
        		<p>
				<?php //echo print_r_html_v3($article['content']);?>
				<?php //echo print_r_html_v2($article['content']);?>
				<?php //print_paragraph_html_v1($article['content']);?>
				
				<?php //cho $article['content'];?>
				</p>

				<div class="row">
				
				
				<!------------Show Main inner first Handle photo, video     --------------------------->
				<?php
					foreach ($arr_media_main_inner_is as $media): 
				?>
				
				<?php
					if($media['media_type_id']==1)  //photo

					{ ?>
						<div class="col-sm-6 img-toggle">
								<?php
								if($media['photo_small_file_path']<>'') {
								$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $media['photo_small_file_path']. '/'. $media['photo_small_file_name'];
								echo '<a href="'.$photo_url.'" target="_blank">';
								//echo '<a  class="text-body fancybox" rel="group" href="' . $photo_url . '" title="'.$media['caption'].'">';
								echo '<img src="' . $photo_url . '" class="img-fluid" />';
								echo '</a>';
								} else {
										echo 'No Photo';
								}
								//echo '</a>';
								?>							
							 <p class="small">
								 <span class="text-secondary "><?php echo $media['caption'];?>
							 </p>
						</div>
						
				<?php	
					} //if($media['media_type_id']==1)  //photo
				?>				
				
				<?php
					endforeach; 	
				?>	
				
				<!------------Handle photo, video     --------------------------->
				

				<?php
					foreach ($arr_media as $media): 
					
					if($media['media_type_id']==1)  //photo

					{ ?>
						<div class="col-sm-6 img-toggle">
								<?php
								if($media['photo_small_file_path']<>'') {
								$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $media['photo_small_file_path']. '/'. $media['photo_small_file_name'];
								echo '<a href="'.$photo_url.'" target="_blank">';
								//echo '<a  class="text-body fancybox" rel="group" href="' . $photo_url . '" title="'.$media['caption'].'">';
								echo '<img src="' . $photo_url . '" class="img-fluid" />';
								echo '</a>';
								} else {
										echo 'No Photo';
								}
								//echo '</a>';
								?>							
							 <p class="small">
								 <span class="text-secondary "><?php echo $media['caption'];?>
							 </p>
						</div>
						
				<?php	
					} //if($media['media_type_id']==1)  //photo
				?>

				
				<?php
					if($media['media_type_id']==2)  //Youtube
					{ 
				?>
					
						<div class="col-sm-6 img-toggle">
							<div class="video-container">
								<iframe class="video" src="<?php echo $media['youtube_video_url'];?>" allowfullscreen></iframe>								
							</div>
							 
							 <p class="small">
								 <span class="text-secondary"><?php echo $media['caption'];?>
							 </p>
						</div>
 
							
				<?php		
					} //if($media['media_type_id']==2)  //Youtube
				?>
				
				<?php
					if($media['media_type_id']==3)  //Self Hosted Video
					{ ?>

                    <div class="col-sm-6 img-toggle">
        	             <div class="video-container">
							<?php
								$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $media['photo_small_file_path']. '/'. $media['photo_small_file_name'];
								$video_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $media['video_file_path']. '/'. $media['video_file_name'];
							?>	
        				    <video class="video" controls poster="<?php echo $photo_url ;?>">
        					  <source src="<?php echo $video_url ;?>" type="video/mp4">
        					Your browser does not support the video tag.
        					</video>
        				</div>
        	             
        	             <p class="small">
        	                 <span class="text-secondary"><?php echo $media['caption'];?>
                         </p>					
						
				<?php		
					} //if($media['media_type_id']==3)  //Self Hosted Video
				?>
				
				
				<?php
					endforeach; 	
				?>	
				
					
        	</div>
        	
			<!--//////////////Handle content /////////////////////////////////////////////////////-->
        		<p>

			<?php print_paragraph_html_v1($article['content']);?>
        		<p>

			
			<!--//////////////Handle content /////////////////////////////////////////////////////-->
			
			
			<!--///////////////////////////////////////////////////////////////////////////////////-->

				<!------------Handle reference and upload document     --------------------------->
				
				<div class="row">
						<div class="col-sm-10 img-toggle">
						<ul>

				<?php
					foreach ($arr_media as $media): 
				?>	

				<?php
					if($media['media_type_id']==4)  //Reference URL
					{ ?>
							<li>
								<a href="<?php echo $media['reference_url'] ;?>" target="_blank" ><?php echo $media['caption'];?></a>
							</li>
							
						<br>
		
					
						
				<?php		
					} //if($media['media_type_id']==4)  //Reference URL
				?>
				
				
				<?php
					if($media['media_type_id']==5)  //Upload Document
					{ ?>

	
							<li>
							<?php
							echo '<a href="../../../../downloadFile_ws.php?filename='.$media['upload_file_name'].'&path='.$media['upload_file_path'].'" target="_blank">';
							echo $media['caption'];
							echo '</a>';
							?>
							</li>
					
						<br>
						
				<?php		
					} //if($media['media_type_id']==5)  //Upload Document
				?>				
				
				<?php
					endforeach; 	
				?>	
					</ul>
					</div>
				</div>		
				

			
        	
        </div>
    </main>

<script>
function setCookie(cname,cvalue,exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires=" + d.toGMTString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkCookie() {
  var s=getCookie("kfontsize");
  if (s != "") {
    //alert("Welcome kfontsize " + s);
  } else {
	 s = "medium";
     if (s != "" && s != null) {
       setCookie("kfontsize", s, 2);
     }
  }
}
</script>
	
<?php
require __DIR__.'/../../../template/footer_www_inc.php';
?>
		