<?php
require __DIR__.'/../../../template/header_inc.php';
?>
<?php
if(isset($paging)) $page =$paging->CalcuatePageNo($item_id,SYSTEM_PAGE_ROW_LIMIT);
?>
		<div class="bodyContent breadcrumbExist" id="BodyDiv">
			<div class="contentWrapper" id="BodyWrapDiv">
				<div class="headerNavigation">
					<?php require __DIR__.'/navigation_menu_inc.php'; ?>
					<?php
					if(isset($page)) echo '<a href="'.actionURL('search','?lot_id='.$lot_id.'&page='.$page).'">Search Result</a>';
					echo ' &raquo; ';
					echo 'Edit ';
					?>				
					<div class="pagingNavigation">
						<?php
						if(isset($paging)) {
							if($paging->getPrev_ID($item_id)<>""){
								echo '<a href= "'.actionURL('edit','?item_id='.$paging->getPrev_ID($item_id).'&lot_id='.$lot_id).'" class="commonTextBtn">&lt;&lt;</a>';
							}
								echo '<span class="pageMessage">';
								if (isset($paging)) echo "Record: ".$paging->getCurrentRow($item_id)." of ".$paging->getRecordCount(); 
								echo '</span>';
							
							if($paging->getNext_ID($item_id)<>""){
								echo '<a href= "'.actionURL('edit','?item_id='.$paging->getNext_ID($item_id).'&lot_id='.$lot_id).'" class="commonTextBtn">&gt;&gt;</a>';
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
										Headline :<?php echo htmlspecialchars($primary['headline']); 
										?>									
									</span>
								</span>
							</span>
						</div>
						
						<?php 
						   $form_url = actionURL('photos_create','?tab=photos&mode='.$mode.'&item_id='.$item_id.'&lot_id='.$lot_id);
						?>

						<form class="fullWidthForm fullWidthForm-2col" action="<?php echo $form_url;?>" method="post"  enctype="multipart/form-data" style="padding-top: 0;">

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
									<label class="">Caption:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[caption]"  autocomplete="off" class="twelve"  required value="<?php echo htmlspecialchars($json_search_items['general']['caption']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>


							<span class="formRow">
								<span class="formLabel">
									<label class="">Video URL:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[video_url]"  autocomplete="off" class="sixteen"  value="<?php echo htmlspecialchars($json_search_items['general']['video_url']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>

							<span class="formRow">
								<span class="formLabel">
									<label class="">Other URL:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[other_url]"  autocomplete="off" class="fifteen"  value="<?php echo htmlspecialchars($json_search_items['general']['other_url']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>

							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Main Display:</label>
								</span>
								<span class="formInput">
									<input id="sample_radio_enabled1" type="radio" name="general[main_photo_is]" value="1" <?php if ($general['main_photo_is']==1) echo 'checked="yes"';?>>
									<label for="sample_radio_enabled1" class="">Yes</label>
									<input id="sample_radio_disabled1" type="radio" name="general[main_photo_is]" value="0" <?php if ($general['main_photo_is']==0) echo 'checked="yes"';?>>
									<label for="sample_radio_disabled1" class="">No</label>
								</span>
							</span>
							<span class="formRow">
							</span>
							
							
							

							<span class="formRow">
							</span>
							<span class="formRow">
							</span>
							
							<span class="formRow">
								<span class="formLabel">
								</span>
								<span class="formInput">
									<div id="formsubmitbutton">
									<button type="submit" onclick="ButtonClicked()">Confirm</button>
									</div>
									<div id="buttonreplacement" style="margin-left:30px; display:none;">
										<img src="/images/icons/preload.gif" alt="loading...">
									</div>								  																	
								</span>
							</span>

							
							
						</form>
					</div>
				</div>
			</div>
		</div>
		
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
require __DIR__.'/../../../template/footer_inc.php';
?>
		