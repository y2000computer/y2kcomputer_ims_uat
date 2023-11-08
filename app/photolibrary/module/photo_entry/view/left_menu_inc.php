					<ul>
						<li class="main_menu_selected"><span>Photo Entry</span></li>
						<li class="main_menu_unselected">
						<?php echo '<a href="/'.IS_PORTAL.'/'.IS_LANG.'/photo_entry/new">'.'Upload'.'</a>'?></li>
					</ul>

					<div class="cardWrapper">
						<?php echo '<form action="'.actionURL('search','').'" method="post" >'; ?>
							<span class="formRow">
								<span class="menu_group_headers searchUser"><span>Search</span></span>
							</span>

					
							
							<span class="formRow">
								<span class="formLabel">
									<label for="userModule_email" class="">Keyword&nbsp; (Oneword, Twoword) </label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[keyword]"  autocomplete="off" class="five" value="<?php echo f_html_escape($json_search_items['general']['keyword']);?>" />
									
								</span>
							
								<span class="formLabel">
									<label for="userModule_email" class="">RFID&nbsp;</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[rfid]"  autocomplete="off" class="three" value="<?php echo f_html_escape($json_search_items['general']['rfid']);?>" />
									
								</span>
							
							
							<span class="formRow">
								<button type="submit">Search</button>
							</span>
							
							<span class="formRow" style="margin-bottom: 20px;">
							</span>

							<span class="formRow">
								<span class="menu_group_headers noIcon"><span>Meta tag</span></span>
							</span>


							<?php		
							foreach ($arr_photo_meta_category as $row): 
								$arr_phpto_category_sub = $dmGeneralModel->photo_category_sub_list($row['meta_id']);
							?>
							<span class="formRow">
								<span class="categorySelection">
									<span class="categorySelector">
										   <span class="contentLabel">
											   <span class="labelTitle">
													<?php
													echo '<a href="#"  onclick="'.'show_meta_id_'.$row['meta_id'].'();">'.$row['name'].' &nbsp; &raquo; </a>';											   
													echo PHP_EOL;
													?>
											   </span>
										   </span>		
									   </span>		

										<?php
										$id='ajax_show_meta_id_'.$row['meta_id'];
										echo PHP_EOL;
										?>
										<?php
										//handle div hide/unhide
										$div_style = "display:none";
											foreach ($arr_phpto_category_sub as $sub): 
												$input_id='sub_id_'.$sub['sub_id'];
												if ($json_search_items['general'][$input_id]==1) $div_style = 'display:block';
											endforeach; 	
										?>
										<div id="<?php echo $id;?>" style="<?php echo $div_style;?>"> 
										<span class="categoryItems">
											<?php		
												foreach ($arr_phpto_category_sub as $sub): 
													echo PHP_EOL;
													$input_id='sub_id_'.$sub['sub_id'];
													$name = $input_id;
													echo '<input id="'.$input_id.'" type="checkbox" name="general['.$input_id.']" value="1" ' . (($json_search_items['general'][$input_id])?'checked':'') . ' >';
													$name_show =$sub['name'];;
													if($json_search_items['general'][$input_id]) $name_show = '<span style="color: #f00;">'.$sub['name'].'</span>';;
													echo PHP_EOL;
													//echo '<label for="'.$input_id.'" class="">'.$sub['name'].'</label>';
													echo '<label for="'.$input_id.'" class="">'.$name_show.'</label>';
													echo PHP_EOL;
											endforeach; 	
											?>
										</span>
										
										</div> 
										   
									</span>
							<?php
								echo PHP_EOL;
							endforeach; 	
							?>

							<!--
							<span class="formRow">
								<span class="categorySelection">
									<span class="categorySelector">
										   <span class="contentLabel">
											   <span class="labelTitle">
													<a href="#"  onclick="show_01();">人物 &nbsp; &raquo; </a>											   
											   </span>
										   </span>									
									</span>
									
									<div id="ajax_show01" style="display:none"> 
									
									<span class="categoryItems">
										<input id="gallery_person_111" type="checkbox" name="gallery_person_111" value="0" >
										<label for="gallery_person_111" class="">呂志和</label>
										<input id="gallery_person_22" type="checkbox" name="gallery_person" value="1">
										<label for="gallery_person_22" class="">呂耀東</label>
										<input id="gallery_person_33" type="checkbox" name="gallery_person" value="2">
										<label for="gallery_person_33" class="">鄧呂慧</label>
										<input id="gallery_person_44" type="checkbox" name="gallery_person" value="3">
										<label for="gallery_person_44" class="">呂耀華</label>
									</span>
									
									</div> 

								</span>
							</span>

							-->
							
							
	


							
							
							<span class="formRow">
								<button type="submit">Search</button>
							</span>							

							
						</form>
					</div>

		<script>
			$(document).ready
			(
				function ()
				{
					$(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
				}
			);
		</script>		



		<script>

		<?php		
		foreach ($arr_photo_meta_category as $row): 
			$func ='show_meta_id_'.$row['meta_id'];
			$target_div ='ajax_show_meta_id_'.$row['meta_id'];
		?>
				function <?php echo $func;?>(){
					  var x = document.getElementById("<?php echo $target_div;?>");
					  if (x.style.display === "none") {
						x.style.display = "block";
					  } else {
						x.style.display = "none";
					  }
				}	
		
		<?php
			echo PHP_EOL;
		endforeach; 	
		?>


		</script>		
		
	