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
										Name: <?php echo htmlspecialchars($general['name']);?>									
									</span>
								</span>
							</span>
						</div>
						<form class="fullWidthForm fullWidthForm-2col" action="<?php echo actionURL('update','?item_id='.$item_id.'&lot_id='.$lot_id);?>" method="post" style="padding-top: 0;">
						
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
									<label class="">Name :</label>
								</span>
								<span class="formInput" >
									<input type="text"  name="general[name]"  autocomplete="off" class="eight" required value="<?php echo htmlspecialchars($general['name']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>						
						

							<span class="formRow">
								<span class="formLabel">
									<label class="">Date From :</label>
								</span>
								<span class="formInput" data-remarks="(dd/mm/yyyy)">
									<input id="date_from" class="datepicker" required style="width: 140px" type="text" name="general[date_from]" autocomplete="off" value="<?php echo $general['date_from'];?>" placeholder="dd/mm/yyyy" maxlength="10">
								</span>
							</span>							
							<span class="formRow">
							</span>


							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Date To :</label>
								</span>
								<span class="formInput" data-remarks="(dd/mm/yyyy)">
									<input id="date_to" class="datepicker" required style="width: 140px" type="text" name="general[date_to]" autocomplete="off" value="<?php echo $general['date_to'];?>" placeholder="dd/mm/yyyy" maxlength="10">
								</span>
							</span>							
							<span class="formRow">
							</span>						
						
						
	


							<span class="formRow formRow-2col" >
								<span class="formLabel">
									<label class="">Content :</label>
								</span>
								<span class="formInput"  >
									<textarea  class="fifteen" rows="16"  name="general[content]"><?php echo htmlspecialchars($general['content']);?></textarea>
								</span>
							</span>							


							

							<span class="formRow">
								<span class="formLabel">
									<label class="">Publish:</label>
								</span>
								<span class="formInput">
									<input id="sample_radio_enabled1" type="radio" name="general[publish_is]" value="1" <?php if ($general['publish_is']==1) echo 'checked="yes"';?>>
									<label for="sample_radio_enabled1" class="">Published</label>
									<input id="sample_radio_disabled1" type="radio" name="general[publish_is]" value="0" <?php if ($general['publish_is']==0) echo 'checked="yes"';?>>
									<label for="sample_radio_disabled1" class="">Pending</label>
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
<?php
require __DIR__.'/../../../../template/footer_inc.php';
?>
		