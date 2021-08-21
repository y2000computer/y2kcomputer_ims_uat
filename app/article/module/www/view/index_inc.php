<?php
require __DIR__.'/../../../../template/header_www_inc.php';
?>

<!--<body onload="checkCookie()">-->
<body>

    <header>
    	<div class="container text-center">
    		<!--<a class="navbar-brand" href="#"><img src="/assets/img/logo.png" alt="logo" height="56" /></a>-->
    	</div>
    </header>
	

    <main role="main">

		<?php //require __DIR__.'/navigation_menu_inc.php'; ?>
        
        
        <div class="page-holder">
        	<div  class="container" >
        			<!-- Button trigger modal -->
        					<div class="article-heading p-0">
        					<a  class="btn  btn-small p-1 btn-block note" data-toggle="modal" data-target="#noteModal" >
        					 摘要
        					</a>
        					</div>
        						<!-- Modal -->
        						<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
        						  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        						    <div class="modal-content">
        						      <div class="modal-header border">
        						        <h6 class="modal-title" id="noteModalLabel">摘要<?php
										foreach ($arr_season_last_three as $last_three): 
											if($last_three['season_id'] == $season_id) {
												$show_year = YMDHMStoY($last_three['date_to']);
												$show_month = YMDHMSton($last_three['date_from']).'-'.YMDHMSton($last_three['date_to']);
												echo '('.$show_year.'年'.$show_month.'月)';
											}
										?>
										  <?php
										  endforeach; 	
										  ?>										
										</h6>
        						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        						          <span aria-hidden="true">&times;</span>
        						        </button>
        						      </div>
        						      <div class="modal-body p-0">
        						         <table class="table table-striped table-bordered m-0">
											<?php
											 echo $arr_season['content'];
											 ?>
        						         </table>
        						      </div>
        						      
        						    </div>
        						  </div>
        						</div>	

								
					<?php
					foreach ($arr_category as $category): 
						$arr_season_headline = $dmWwwModel->category_season_headline_select($season_id, $category['cate_id']); 
						$season_headline ='';
						foreach ($arr_season_headline as $season_headline): 
							$season_headline = $season_headline['headline'];
						endforeach; 	
					?>

					<div class="article-heading highlight mb-0">
        				<?php echo '<b>'.$category['category_name'].'</b>';?>
        			</div>					
					
        				<?php 
							if($season_headline<>'') {
								echo '<div class="article-heading">';
								echo $season_headline;
								echo '</div>';
							}
						?>
        			
        			<div class="article-list">
					<?php
					$arr_category_article = $dmWwwModel->category_article_view($season_id, $category['cate_id'], $preview); 
					foreach ($arr_category_article as $article): 

					?>
        	            <div>
							<?php
							$url =  actionURL('view','?article_id='.$article['article_id'].'&preview='.$preview.'&season_id='.$season_id);
							?>
        	                <a href="<?php echo $url;?>">
        		                <div class="row">
        			                <div class="col-sm-3 col-lg-2 img-toggle">
										<?php
										$media = $dmWwwModel->media_main_select($article['article_id']); 
										if($media['photo_small_file_path']<>'') {
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $media['photo_small_file_path']. '/'. $media['photo_small_file_name'];
										echo '<img src="' . $photo_url . '" class="img-fluid img-toggle  w-100" />';
										} else {
												echo 'No Photo';
										}
										?>

        			                </div>
        			                <div class="col">
										<?php //echo '['.YMDHMSto_D_M($article['article_date']).']'.'&nbsp;&nbsp;';?>
										<?php //echo YMDHMStoY_M_D($article['article_date']).'&nbsp;';?>
										<?php //echo YMDHMStoY_M_D($article['article_date']).'&nbsp;';?>
										
										<?php //echo date("n",strtotime($article['article_date'])).'月'.date("j",strtotime($article['article_date'])).'日'.'&nbsp;';?>
										
										<?php //echo $article['headline'];?>
										
										
										<table>
											<tr>
												<td class="pr-3" valign="top">
												<?php echo date("n",strtotime($article['article_date'])).'月'.date("j",strtotime($article['article_date'])).'日'.'&nbsp;';?>

												<?php echo $article['headline'];?>
												</td>
											</tr>
										</table>
																			
										
        			                </div>
        		                </div>
        		            </a>
        	                <hr/>
        	            </div>
						
					  <?php
					  endforeach; 	//arr_category_article
					  ?>
		
        			</div>

					  <?php
					  endforeach; 	//arr_category
					  ?>
						

						
					
					
					
					
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



<script type="text/javascript">
	$(window).on('load',function(){
		console.log("Loading!");
		
		if (typeof(localStorage) !== "undefined") {
		   console.log('Code for localStorage/sessionStorage.');
		} else {
			console.log('Sorry! No Web Storage support..');
		}

		var  listSeasonID = [];
		var seasonid_now = <?php echo $season_id;?>;
		var seasonid_match_is = false;
		var storedSeasonID = JSON.parse(localStorage.getItem("storedSeasonID")); //get them back		

		console.log('seasonid_now =' + seasonid_now );

		if(storedSeasonID !=  null ){
			console.log('storedSeasonID is NOT null ');
		} else {
			console.log('storedSeasonID is null ');
		}

		
		if(storedSeasonID !=  null ){
			console.log('execute storedSeasonID is NOT null ');
			var listSeasonID = JSON.parse(localStorage.getItem("storedSeasonID")); //get them back				
			if(listSeasonID !=  null ){
				console.log('listSeasonID is NOT null ');
			} else {
				console.log('listSeasonID is null ');
			}

			for (var key in listSeasonID) {
					console.log('listSeasonID [' + key + '] =' + listSeasonID[key]);
					if(listSeasonID[key] == seasonid_now) {
						seasonid_match_is = true;
					}
			}
		}	

		if(seasonid_match_is ==true ) {
			console.log('seasonid_match_is is true');
		} else {
			console.log('seasonid_match_is is false');
		}
			
			
		if(seasonid_match_is ==false ) {
			listSeasonID.push(seasonid_now) ;
			//listSeasonID[0] = 5;
			for (var key in listSeasonID) {
					console.log('pending to store listSeasonID [' + key + '] =' + listSeasonID[key]);
			}			
			$('#noteModal').modal('show');
			localStorage.setItem("storedSeasonID", JSON.stringify(listSeasonID)); 
			console.log('store storedSeasonID');
		}
			

		
	});
</script>
        			


	
<?php
require __DIR__.'/../../../../template/footer_www_inc.php';
?>
		