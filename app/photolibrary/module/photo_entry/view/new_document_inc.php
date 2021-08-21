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
										Photo ID:<?php echo htmlspecialchars($primary['photo_code']);?>									
										&nbsp;&nbsp;
										Caption :<?php echo htmlspecialchars($primary['caption']);?>																		
									</span>
								</span>
							</span>
						</div>
						
						<?php 
						   $form_url = actionURL('document_create','?tab=document&mode='.$mode.'&item_id='.$item_id.'&lot_id='.$lot_id.'&panelsize='.$panelsize);
						?>

						<form class="fullWidthForm fullWidthForm-2col" action="<?php echo $form_url;?>" method="post" enctype="multipart/form-data"  style="padding-top: 0;">

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
									<label class="">Description:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[file_desc]"  autocomplete="off" class="twelve"  required value="<?php echo htmlspecialchars($json_search_items['general']['file_desc']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>

							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Upload File:</label>
								</span>
								<span class="formInput">
								    <input type="file" class="commonTextBtn" name="uploadfile" id="fileToUpload" ></label>
									&nbsp;(Maximum file size: 100Mb)
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
									<button type="submit">Confirm</button>
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
		