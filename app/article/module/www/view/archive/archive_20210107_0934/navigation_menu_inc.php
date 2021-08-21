<?php
//Handle navigator bar
$season_ar=array();
$i = 0;
foreach ($arr_season_master as $season_master): 
$season_ar[$i++] = $season_master['season_id'];
endforeach; 	

//determine last three season_id
$i = 0;
$last_three_season_ar = array();
foreach ($season_ar as $focus): 
	//echo 'focus ='.$focus.'<br>';
	if($season_id == $focus) {
		$last_three_season_ar[$i++] = $focus;
		//echo 'store season ='.$focus.'.. into last_three_season_ar['.$i.']<br>';
	} else {
		if (!empty($last_three_season_ar) && $i < 3 ) {
			$last_three_season_ar[$i++] = $focus;
		}
	}
endforeach; 	

foreach ($last_three_season_ar as $season): 
	//echo 'last_three_season current ='.$season.'<br>';
endforeach; 	


//determine had previous season id 
$i = 0;
$previous_season_id = 0;
foreach ($season_ar as $focus): 
	//echo 'focus='.$focus. '..last_three_season_ar[0]='.$last_three_season_ar[0].'<br>';
	if($focus == $last_three_season_ar[0] ) {
		if($i==0) $previous_season_id = 0;
		if($i>0) $previous_season_id = $season_ar[$i-1];
	} else {
	}
	$i += 1;
endforeach; 	
//echo '$previous_season_id='.$previous_season_id.'<br>';


//determine had next season id 
$i = 0;
$next_season_id = 0;
foreach ($season_ar as $focus): 
	//echo 'focus='.$focus. '..last_three_season_ar[2]='.$last_three_season_ar[2].'<br>';
	if($focus == $last_three_season_ar[2] ) {
		if($i==0) $next_season_id = 0;
		if($i>0) $next_season_id = $season_ar[$i+1];
	} else {
	}
	$i += 1;
endforeach; 	
if($next_season_id=='') $next_season_id = 0;
//echo '$next_season_id='.$next_season_id.'<br>';



?>
    	 <div class="nav-date-select">
    		<div  class="container" >
    			<div class="row no-gutters">
    				<div class="col-auto">
						<?php
							if($previous_season_id<>0) {
								$url = '/'.IS_PORTAL.'/'.IS_LANG.'/www?preview='.$preview.'&season_id='.$previous_season_id;
								echo '<a href="'.$url.'" class="arrow"><img src="/assets/img/arrow-left.png"/></a>';
							}
						?>
    				</div>
    				<div class="col">
    					<ul class="nav nav-pills nav-fill ">
						<?php
						foreach ($arr_season_last_three as $last_three): 
						    echo '<li class="nav-item">';
							$focus ='';
							if($last_three['season_id'] == $season_id) $focus ='active';
							$show_year = YMDHMStoY($last_three['date_to']);
							$show_month = YMDHMSton($last_three['date_from']).'-'.YMDHMSton($last_three['date_to']);
							$url = '/'.IS_PORTAL.'/'.IS_LANG.'/www?preview='.$preview.'&season_id='.$last_three['season_id'];
    					    echo '<a class="nav-link '.$focus.'" href="'.$url.'"><span class="small">'.$show_year.'年</span><br/>'.$show_month.'月</a>';
							echo '</li>';
						?>
						  <?php
						  endforeach; 	
						  ?>
    					</ul>
    				</div>
    				<div class="col-auto">
    					<!--<a href="#" class="arrow"><img src="/assets/img/arrow-right.png" /></a>-->
						<?php
							if($next_season_id<>0) {
								$url = '/'.IS_PORTAL.'/'.IS_LANG.'/www?preview='.$preview.'&season_id='.$next_season_id;
								echo '<a href="'.$url.'" class="arrow"><img src="/assets/img/arrow-right.png"/></a>';
							}
						?>						
    				</div>
    			</div>
    		</div>
    	</div>
    	<div class="control">
    		<div  class="container" >
    			<div class="row">
    		            <div class="col-auto text-left ">
    			            <div >
    				            <a href="#" class="img-txt-toggle">
								<span class="show-txt">[純文字版]</span>										
								<span class="show-img">[顯示照片]</span>
								<span class="label" style="color: #f00;">
								<?php if(ENV=='UAT') echo '(UAT)';?>
								</span>								
								</a>
    			            </div>
    		            </div>
    		            <div class="col text-right text-control">
							<!--
    			            <a href="#" onClick="javascript:setCookie('kfontsize','small',2);" id="small"     <?php echo ($font=='small' ? 'class="active"' : '' ) ?>>A</a>
    					    <a href="#" onClick="javascript:setCookie('kfontsize','medium',2);" id="medium" <?php echo ($font=='medium' ? 'class="active"' : '' ) ?>>A</a>
    					    <a href="#" onClick="javascript:setCookie('kfontsize','large',2);" id="large"      <?php echo ($font=='large' ? 'class="active"' : '' ) ?>>A</a>
							-->
							
    			            <a href="#"  id="small"     >A</a>
    					    <a href="#"  id="medium" class="active">A</a>
    					    <a href="#"  id="large"      >A</a>

							
    		            </div>

    		    </div>
    		</div>
    	</div>        
