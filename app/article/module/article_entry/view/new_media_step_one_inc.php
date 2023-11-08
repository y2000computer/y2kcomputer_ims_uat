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
										Headline :<?php echo f_html_escape($primary['headline']); 
										?>									
									</span>
								</span>
							</span>
						</div>
						
						<?php 
						   $form_url = actionURL('media_new_step_two','?tab=media&mode='.$mode.'&item_id='.$item_id.'&lot_id='.$lot_id);
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
									<label class="">Media Type:</label>
								</span>
								<span class="formInput">
									<select name="general[media_type_id]" required >
									<?php
									echo '<option value=""'.' '.($general['media_type_id']  ==''?'selected':'').'>'.'Please select'.'</option>';
									foreach ($arr_article_media_type_master  as $media_type_master) { 
									  echo '<option value="'.$media_type_master['media_type_id'].'"'.' '.($general['media_type_id']  == $media_type_master['media_type_id']?'selected':'').'>'.$media_type_master['name'].'</option>';
									}
									?>
									</select>
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
									<button type="submit" >Next</button>
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
		
				
 		
<?php
require __DIR__.'/../../../../template/footer_inc.php';
?>
		