<?php
$body_photo_preview_is = true;
require __DIR__.'/../../../../template/header_inc.php';
?>
<?php
$json_search_items = json_decode($json_searchphrase, true);
?>
		<div class="bodyContent sideBarExist breadcrumbExist photoGallery" id="BodyDiv">
			<div class="contentWrapper" id="BodyWrapDiv">
				<div class="headerNavigation">
					<?php require __DIR__.'/navigation_menu_inc.php'; ?>
				</div>
				<div class="sidebar" id="MainMenuDiv">
				<?php require __DIR__.'/left_menu_inc.php'; ?>
				</div>
				
					<?php
					//Handle keyword and high light the keyword in red color 
					$keyword_input_is =false;
					if(f_html_escape($json_search_items['general']['keyword'])<>'' ) $keyword_input_is = true;

					//if( $keyword_input_is == true )  echo '<br> keyword_input_is is true<br>';
					//if( $keyword_input_is == false)  echo '<br> keyword_input_is is false<br>';
										
					//verify keyword input, filter error_get_last
					$pattern_cks  = explode(',', f_html_escape($json_search_items['general']['keyword']));
					$pattern_checkeds =array();
					$i = 0;
					$p = 0;
					foreach($pattern_cks  as $pattern_ck){
						//echo '<br> pattern_ck['.$i.'] = '. $pattern_ck.' ->len = '.strlen($pattern_ck).'<br>';
						$pattern_ck=mb_trim($pattern_ck) ;
							
						if($pattern_ck != '') {
								$pattern_checkeds[$p]=$pattern_ck;
								$p += 1;
						}
						$i += 1;
					}
					
					foreach($pattern_checkeds  as $pattern_checked){
						//echo '<br> pattern_checked = '. $pattern_checked.'<br>';
					}
					
					if( count($pattern_checkeds) >0 )  $keyword_input_is =true;
					
					
					$replacements = array();
					//$patterns  = explode(',', f_html_escape($json_search_items['general']['keyword']));
					//$pure_patterns  = explode(',', f_html_escape($json_search_items['general']['keyword']));
					
					$patterns  = $pattern_checkeds;
					$pure_patterns  = $pattern_checkeds;
					$pure_pattern_first = f_str_replace("/", "", $pure_patterns[0]);

					//echo '<br> count = '. count($patterns).'<br>';
					$i = 0;
					foreach($patterns  as $pattern){
						//echo '<br> pattern['.$i.'] = '. $pattern[$i].'<br>';
						$i += 1;
					}

					
					$i = 0;
					foreach($patterns  as $pattern){
						$patterns[$i] = '/'.$pattern.'/';
						$i += 1;
					}
					$i = 0;
					foreach($patterns  as $pattern){
						//echo $pattern.'<br>';  
						$replacements[$i] = '<span style="color: #f00;">'.$pattern.'</span>';
						//echo $replacements[$i].'<br>';  
						$i += 1;
					}
					
					
					foreach($replacements  as $replacement){
						$replacement = '<span style="color: #f00;">'.$replacement.'</span>';
						//echo $replacement.'<br>';  
					}
					
					?>				
				
				<?php 
				$arr_count_general_model = count($arr_general_model);
				$ifound_record = 0;
				if ($arr_count_general_model >0) $ifound_record = $paging->getRecordCount();
				?>
				
				<div class="sidebarContent" id="SubMenuDiv">
					<div class="sidebarContentCol" id="TransactionsDiv">
						<span class="menu_group_headers">
							<span>Total Record Found: <?php echo $ifound_record;?></span>
							<span class="actionPanel">
								<?php
									$url =  actionURL('search','?lot_id='.$lot_id.'&page='.$page.'&panelsize='.'small');
								?>
								<a class="commonTextBtn" href="<?php echo $url;?>" style="panelsize-top: 0; padding-bottom: 0;"><span style="font-size: 14px; line-height: 26px; vertical-align: middle;">&#9640;</span></a>
								<?php
									$url =  actionURL('search','?lot_id='.$lot_id.'&page='.$page.'&panelsize='.'medium');
								?>
								<a class="commonTextBtn" href="<?php echo $url;?>" style="padding-top: 0; padding-bottom: 0;"><span style="font-size: 20px; line-height: 26px; vertical-align: middle;">&#9640;</span></a>
								<?php
									$url =  actionURL('search','?lot_id='.$lot_id.'&page='.$page.'&panelsize='.'large');
								?>
								<a class="commonTextBtn" href="<?php echo $url;?>" style="padding-top: 0; padding-bottom: 0;"><span style="font-size: 26px; line-height: 26px; vertical-align: middle;">&#9640;</span></a>
							</span>
						</span>
						<span class="imageList <?php echo $panelsize; ?>">
						
						
								<?php
								$photo_id_focus = 1; //should by get 
								$photo_id_selected = ''; 
								if ($arr_count_general_model >0){
									$i_count=1+($page-1)*SYSTEM_PAGE_ROW_LIMIT;
									foreach ($arr_general_model as $general_model): 
										if ($photo_id_focus == $general_model['photo_id'])  $photo_id_selected='selected';
										
										//echo '<a href="../../../../downloadFile.php?filename='.$document['file_name'].'&path='.$document['file_path'].'" target="_blank">';
										//$photo_url = 'images/test/test.jpg';
										//$photo_url ='http://local-digital-image.kwah.com/images/test/test.jpg';
										//$photo_url ='http://local-digital-image.kwah.com/images/test/test_sample.jpg';
										//$photo_url ='../../../../images/test/test.jpg';
										//$photo_url ='../../../../images/test/'. $general_model['small_file_name'];
										//$photo_url ='../../../../photos/'. $general_model['small_file_name'];
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general_model['small_file_path']. '/'. $general_model['small_file_name'];
										
										echo '<a class="imageItem ' . '' .' " href="#" onClick="update_preview_window('.$general_model['photo_id'].')" >';
										echo PHP_EOL;
										echo '<span class="itemImage" style="background-image: url(\'' . $photo_url . '\');" ></span>';
										echo PHP_EOL;
										
										$caption_show = $general_model['caption'];
										if( $keyword_input_is == true )  {
											$caption =  preg_replace($patterns, $replacements, $general_model['caption']);
											$caption_show = f_str_replace("/", "", $caption);
										}
									    
										echo '<span class="itemCaption">'. $caption_show . '</span>';
										echo PHP_EOL;
										$desc_show = $general_model['desc'];
										$desc_show_len =mb_strlen($desc_show, 'UTF-8');

										/*
										if( $keyword_input_is == true )  {
											$desc_show =  preg_replace($patterns, $replacements, $desc_show);
											$desc_show_len =mb_strlen($desc_show, 'UTF-8');
											//$desc_show_pattern_pos = mb_strpos($desc_show, $pure_pattern_first, 0,'UTF-8');
											$desc_show = f_str_replace("/", "", $desc_show);

										}
										

										$desc_show = mb_f_str_replace("\r", "<br>\r", $desc_show);
										echo '<span class="itemDesc">'. $desc_show . '</span>';
										*/
										
										echo PHP_EOL;
										$meta_tag ='';
										$arr_photo_entry_meta_category_sub_list = $dmGeneralModel->photo_entry_meta_category_sub_list($general_model["photo_id"]);
										foreach ($arr_photo_entry_meta_category_sub_list as $row): 
													//if($meta_tag<>'') $meta_tag .='<br>&nbsp;';
													if($meta_tag<>'') $meta_tag .='<br>';
													$input_id='sub_id_'.$row['sub_id'];
													if ($json_search_items['general'][$input_id]==1) {
														$meta_tag .= '<span style="color: #f00;">'.'['.$row['category_sub_name'].']'.'</span>';
													} else {
														$meta_tag .= '['.$row['category_sub_name'].']';
														}
													//echo '<span class="itemDesc">'. '['.$row['category_sub_name'].']' . '</span>';
												
										endforeach; 	
										

										$meta_tag_show = $meta_tag;
										
										
										//Handle search RFID
										$rfid_input_is = false;
										if(f_html_escape($json_search_items['general']['rfid'])<>'' ) $rfid_input_is = true;
										
										if($rfid_input_is==true){
												$meta_tag_show = '<span style="color: #f00;">'.'RFID#'.$general_model['rfid'].''.'</span>';
										}
										
										
										if( $keyword_input_is == true )  {
											//$meta_tag_show =  preg_replace($patterns, $replacements, $meta_tag);
											//$meta_tag_show = f_str_replace("/", "", $meta_tag_show);
										}
										
										if($meta_tag_show=='') {
											echo '<span class="itemDesc">&nbsp;</span>'; 
											}
											else  {
													//echo '<span class="itemDesc">'. '['.$meta_tag_show.']' . '</span>';
													echo '<span class="itemDesc">'. $meta_tag_show . '</span>';
										}	
										
										echo '</a>';
										echo PHP_EOL;										

									endforeach; 	
								}
								?>
								
							
							
	

						</span>
						<span class="pagination">

						<?php 
						if ($arr_count_general_model >0){
							
							if($paging->getTotalPages()>1) { 
								if($page >1 ){
									echo '<a href="'.actionURL('search','?lot_id='.$lot_id.'&page='.($page-1)). '&panelsize='. $panelsize  .'" class="pageResults">'.'&lt;&lt;</a>';
								}
							}
							echo PHP_EOL;
							for($x = 1; $x <= $paging->getTotalPages(); $x++): 
								echo PHP_EOL;
								if($x == $page ){
									echo '<span class="pageResults pageResultsActive">'.$x.'</span>';
									}else {
										echo '<a href="'.actionURL('search','?lot_id='.$lot_id.'&page='.$x). '&panelsize='. $panelsize .'" class="pageResults">'.$x.'</a>';
										}
								endfor; 


							if($paging->getTotalPages()>1) { 
								if($page < $paging->getTotalPages() ){
									echo '<a  href="'.actionURL('search','?lot_id='.$lot_id.'&page='.($page+1)). '&panelsize='. $panelsize  .'" class="pageResults">'.'&gt;&gt;</a>';
								}
							}
							
							echo PHP_EOL;
							
						}	//if ($arr_count_general_model >0){
						?>
						
						</span>
					</div>
				</div>
				<div id="previewPanel" class="previewPanel" style="display:none">
				</div>
			</div>
		</div>

		
	
	
	
   <script type="text/javascript">
      function update_preview_window(photo_id){
		   var option = photo_id;
		   var request = $.ajax({
				url: '<?php echo ("/".IS_PORTAL."/".IS_LANG."/photo_entry/ajax_photo_preview_window"); ?>',
				timeout:30000,
				type: "GET",
				data: { 
					"option": option,
					"tab": "general",
					"lot_id": "<?php echo $lot_id;?>",
					"page": "<?php echo $page;?>",
					"panelsize": "<?php echo $panelsize;?>"
				},
				success: function(response){
					$("#previewPanel").html(response);
					$("#previewPanel").show();				
                    }
			});
			
	   }	
	</script>
	
<?php		
require __DIR__.'/../../../../template/footer_inc.php';
?>
				