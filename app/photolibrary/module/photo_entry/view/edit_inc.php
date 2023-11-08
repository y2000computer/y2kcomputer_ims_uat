<?php
require __DIR__.'/../../../../template/header_inc.php';
?>
<?php
if(isset($paging)) $page =$paging->CalcuatePageNo($item_id,SYSTEM_PAGE_ROW_LIMIT);
?>
		<div class="bodyContent breadcrumbExist" id="BodyDiv">
			<div class="contentWrapper" id="BodyWrapDiv">
				<div class="headerNavigation">
					<?php require __DIR__.'/navigation_menu_inc.php'; ?>
					<?php
					if(isset($page)) echo '<a href="'.actionURL('search','?lot_id='.$lot_id.'&page='.$page.'&panelsize='.$panelsize).'">Search Result</a>';
					echo ' &raquo; ';
					echo 'Edit ';
					?>				
					<div class="pagingNavigation">
						<?php
						if(isset($paging)) {
							if($paging->getPrev_ID($item_id)<>""){
								echo '<a href= "'.actionURL('edit','?item_id='.$paging->getPrev_ID($item_id).'&lot_id='.$lot_id.'&panelsize='.$panelsize).'" class="commonTextBtn">&lt;&lt;</a>';
							}
								echo '<span class="pageMessage">';
								if (isset($paging)) echo "Record: ".$paging->getCurrentRow($item_id)." of ".$paging->getRecordCount(); 
								echo '</span>';
							
							if($paging->getNext_ID($item_id)<>""){
								echo '<a href= "'.actionURL('edit','?item_id='.$paging->getNext_ID($item_id).'&lot_id='.$lot_id.'&panelsize='.$panelsize).'" class="commonTextBtn">&gt;&gt;</a>';
							}
						} //if(isset($page))  
						?>
					</div>
				</div>
				<div class="sidebarContent">
					<div class="sidebarContentCol">
						<div class="fullWidthContent" style="padding-bottom: 0;">
							<span class="contentRow">
							<?php require 'edit_tab_inc.php'; ?>
							</span>
							<span class="contentRow">
								<span class="menu_group_headers">
									<span>
										Photo ID:<?php echo f_html_escape($general['photo_code']);?>									
										&nbsp;&nbsp;
										Caption :<?php echo f_html_escape($general['caption']);?>									
									</span>
								</span>
							</span>
						</div>
						<form class="fullWidthForm fullWidthForm-2col" action="<?php echo actionURL('update','?item_id='.$item_id.'&lot_id='.$lot_id.'&panelsize='.$panelsize);?>" method="post" enctype="multipart/form-data" style="padding-top: 0;">
						
						<?php
						if(isset($vlValidation)) {
							if($vlValidation->getProblemMsg()<>'') {
								echo '<span class="alertMsg errorMsg">';
								echo $vlValidation->getProblemMsg();
								echo 'Record not save !';
								echo '</span>';
							} else {
								echo '<span class="alertMsg successMsg">';
								echo 'Update successfully.';
								echo '</span>';
								}
						}	
						?>						
						
						<?php if($general['large_file_name']<>'') { ?>
							<span class="formRow formRow-2col">
								<span class="formLabel">
									<label for="" class="">Large JPEG:</label>
								</span>
								<span class="formPreviewImage">
									<?php
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general['large_file_path']. '/'. $general['large_file_name'];										
										echo '<img src="'.$photo_url.'" width="30%"  />';
										echo '&nbsp;&nbsp;';
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general['large_file_path']. '/'. $general['large_file_name'];										
										echo '<a href="' . $photo_url . '" download >[Download Large JPEG]</a>';
									
									?>
								</span>
							</span>
							

							<span class="formRow">
							</span>
							<span class="formRow">
							</span>
							
							
						<?php } ?>			
				
						<?php if($general['small_file_name']<>'') { ?>
							<span class="formRow formRow-2col">
								<span class="formLabel">
									<label for="" class="">Thumbnail JPEG:</label>
								</span>
								<span class="formPreviewImage">
									<?php
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general['small_file_path']. '/'. $general['small_file_name'];										
										echo '<img src="'.$photo_url.'" width="20%"  />';
										echo '&nbsp;&nbsp;';
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general['small_file_path']. '/'. $general['small_file_name'];										
										echo '<a href="' . $photo_url . '" download >[Download Thumbnail JPEG]</a>';
									
									?>
								</span>
							</span>
							

							<span class="formRow">
							</span>
							<span class="formRow">
							</span>
						<?php } ?>			
							

							<span class="formRow formRow-2col">
								<span class="formLabel">
									<label for="" class="">Photoshop PSD:</label>
								</span>
								<span class="formPreviewImage">
									<?php
										 if($general['psd_file_name']<>'') { 
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general['psd_file_path']. '/'. $general['psd_file_name'];										
										echo '&nbsp;&nbsp;';
										echo '<a href="' . $photo_url . '" download >[Download Photoshop PSD]</a>';
										 } else {
											 echo '&nbsp;&nbsp;';
											 echo 'N/A';
										 }
									?>
								</span>
							</span>
							

							<span class="formRow">
							</span>
							<span class="formRow">
							</span>
						
							
							
							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Upload Large JPEG:</label>
								</span>
								<span class="formInput">
								    <input type="file" class="commonTextBtn" name="large_jpeg" id="fileToUpload" ></label>
									&nbsp;(Maximum file size: 100Mb)
								</span>
							</span>							
							<span class="formRow">
							</span>
						

							<span class="formRow">
								<span class="formLabel">
									<label class="">Upload Photoshop PSD:</label>
								</span>
								<span class="formInput">
								    <input type="file" class="commonTextBtn" name="photo_psd" id="fileToUpload"></label>
									&nbsp;(Maximum file size: 500Mb)
								</span>
							</span>							
							<span class="formRow">
							</span>
						
						
						
							<span class="formRow">
								<span class="formLabel">
									<label class="">Caption:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[caption]"  required autocomplete="off" class="twelve" value="<?php echo f_html_escape($general['caption']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>
						
						
							<span class="formRow">
								<span class="formLabel">
									<label class="">Year:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[photo_year]"  autocomplete="off" class="three" value="<?php echo f_html_escape($general['photo_year']);?>" />
									(e.g: 1950 , 2000)			
								</span>
							</span>							
							<span class="formRow">
							</span>
						
						
						
							<span class="formRow">
								<span class="formLabel">
									<label class="">Description:</label>
								</span>
								<span class="formInput">
									<textarea  class="seven" rows="6" cols="60" name="general[desc]"><?php echo f_html_escape($general['desc']);?></textarea>
								</span>
							</span>							
							<span class="formRow">
							</span>
						
						
							<span class="formRow">
								<span class="formLabel">
									<label class="">RFID:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[rfid]"  autocomplete="off" class="five" value="<?php echo f_html_escape($general['rfid']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>
						
						
						
							<span class="formRow">
								<span class="formLabel">
									<label class="">Status:</label>
								</span>
								<span class="formInput">
									<select name="general[status]" class="three" required="required"/>
									<option value="1" <?php if ($general['status'] == 1){echo "selected";}?>>Active</option>
									<option value="0" <?php if ($general['status'] == 0){echo "selected";}?>>De-active</option>
									</select>
								</span>
							</span>
							<span class="formRow">
							</span>
							
							<span class="formRow">
							</span>
							<span class="formRow">
							</span>
							
							
							
							
							
							<span class="formRow formRow-2col">
								<span class="inner_group_headers"><span>Meta Tag</span></span>
							</span>
							
							<?php		
							foreach ($arr_photo_meta_category as $row): 
								$arr_photo_category_sub = $dmGeneralModel->photo_category_sub_list($row['meta_id']);
							?>							
							<span class="formRow formRow-2col" >
								<span class="formLabel" style="min-width: 250px; max-width: 250px;">
									<?php
										$input_id='meta_id_'.$row['meta_id'];
										$name = $input_id;
										echo '<label for="'.$input_id.'" class="" style="width: 500px;" >'.$row['name'].':'.'</label>';
									?>
								</span>
								<span class="formInput" style="">
								<?php		
										foreach ($arr_photo_category_sub as $sub): 
											echo PHP_EOL;
											$input_id='sub_id_'.$sub['sub_id'];
											$name = $input_id;
		
											$meta_tab_include_is = 0;
											foreach ($arr_entry_meta_category_sub as $entry_meta_category_sub): 
												if($entry_meta_category_sub['sub_id'] == $sub['sub_id']) $meta_tab_include_is = 1;
											endforeach; 	
		
											echo '<input id="'.$input_id.'" type="checkbox" name="general['.$input_id.']" value="1" ' . (($meta_tab_include_is)?'checked':'') . ' >';
											$name_show =$sub['name'];;
											echo PHP_EOL;
											echo '<label for="'.$input_id.'" class=""  >'.$name_show.'</label>';
											echo '<br>';
											echo PHP_EOL;
											
								endforeach; 	
								?>

								</span>
							</span>
							
							<?php
								echo PHP_EOL;
							endforeach; 	
							?>
							
							
							
							
							
							
							

							
							<span class="formRow">
							</span>
							<span class="formRow">
							</span>
							
							
							<span class="formRow">
								<span class="formLabel">
									<div id="formsubmitbutton">
										<button type="submit" onclick="ButtonClicked()">Confirm</button>
									</div>	
									<div id="buttonreplacement" style="margin-left:30px; display:none;">
										<img src="/images/icons/preload.gif" alt="loading...">
									</div>								  								
								</span>
								<span class="formInput">

								</span>
							</span>

							
							<span class="formRow">
							</span>
							<span class="formRow">
							</span>
							<span class="formRow">
							</span>


							
							<span class="formRow formRow-2col" style="margin-top: 10px;">
							</span>
							<span class="formRow">
								<span class="formLabel">
									<span class="label">Create User:</span>
								</span>
								<span class="formInput">
									<span class="message"><?php echo $general['create_user'];?></span>
								</span>
							</span>
							<span class="formRow">
								<span class="formLabel">
									<span class="label">Create Date:</span>
								</span>
								<span class="formInput">
									<span class="message"><?php echo  $general['create_datetime'];?></span>
								</span>
							</span>
							<span class="formRow">
								<span class="formLabel">
									<span class="label">Last Update By:</span>
								</span>
								<span class="formInput">
									<span class="message"><?php echo $general['modify_user'];?></span>
								</span>
							</span>
							<span class="formRow">
								<span class="formLabel">
									<span class="label">Last Update Date:</span>
								</span>
								<span class="formInput">
									<span class="message"><?php echo  $general['modify_datetime']; ?></span>
								</span>
							</span>							
							
							
						</form>
					</div>
				</div>
			</div>
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



		<script type="text/javascript">
		/*
		   Replacing Submit Button with 'Loading' Image
		   Version 2.0
		   December 18, 2012

		   Will Bontrager Software, LLC
		   https://www.willmaster.com/
		   Copyright 2012 Will Bontrager Software, LLC

		   This software is provided "AS IS," without 
		   any warranty of any kind, without even any 
		   implied warranty such as merchantability 
		   or fitness for a particular purpose.
		   Will Bontrager Software, LLC grants 
		   you a royalty free license to use or 
		   modify this software provided this 
		   notice appears on all copies. 
		*/
		function ButtonClicked()
		{
		   document.getElementById("formsubmitbutton").style.display = "none"; // to undisplay
		   document.getElementById("buttonreplacement").style.display = ""; // to display
		   return true;
		}
		var FirstLoading = true;
		function RestoreSubmitButton()
		{
		   if( FirstLoading )
		   {
			  FirstLoading = false;
			  return;
		   }
		   document.getElementById("formsubmitbutton").style.display = ""; // to display
		   document.getElementById("buttonreplacement").style.display = "none"; // to undisplay
		}
		// To disable restoring submit button, disable or delete next line.
		document.onfocus = RestoreSubmitButton;
		</script>



		
<?php
require __DIR__.'/../../../../template/footer_inc.php';
?>
		