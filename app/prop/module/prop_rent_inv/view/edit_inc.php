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
									<label class="">Building :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape($general['build_eng_name']);?>
								</span>
							</span>
							<span class="formRow">
							</span>					
						
							<span class="formRow">
								<span class="formLabel">
									<label class="">Tenant Code :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape($general['tenant_code']);?>
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
									<label class="">Invoice No. :</label>
								</span>
								<span class="formInput">
										<?php echo f_html_escape($general['inv_code']);?> 
								</span>
							</span>
							<span class="formRow">
							</span>

							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Invoice Date :</label>
								</span>
								<span class="formInput" data-remarks="(dd/mm/yyyy)">
									<?php echo f_html_escape($general['inv_date']);?> 
								</span>
							</span>
							<span class="formRow">
							</span>		

						


							<span class="formRow">
								<span class="formLabel">
									<label class="">Name :</label>
								</span>
								<span class="formInput">
									<?php echo f_html_escape($general['eng_name']);?>
								</span>
							</span>
							<span class="formRow">
							</span>

							<span class="formRow">
								<span class="formLabel">
									<label class="">Add(1) :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape($general['add_1']);?>
								</span>
							</span>
							<span class="formRow">
							</span>


							<span class="formRow">
								<span class="formLabel">
									<label class="">Add(2) :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape($general['add_2']);?>
								</span>
							</span>
							<span class="formRow">
							</span>


							<span class="formRow">
								<span class="formLabel">
									<label class="">Add(3) :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape($general['add_3']);?>
								</span>
							</span>
							<span class="formRow">
							</span>



							<span class="formRow">
								<span class="formLabel">
									<label class="">Ref No. :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape($general['ref_no']);?>
								</span>
							</span>
							<span class="formRow">
							</span>


							<span class="formRow">
								<span class="formLabel">
									<label class="">Shop No. :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape($general['shop_no']);?>
								</span>
							</span>
							<span class="formRow">
							</span>



							<span class="formRow">
								<span class="formLabel">
									<label class="">Description :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape($general['description']);?>
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
									<label class="">Period From :</label>
								</span>
								<span class="formInput" data-remarks="(dd/mm/yyyy)">
								<?php echo f_html_escape($general['period_date_from']);?>
								</span>
							</span>
							<span class="formRow">
							</span>							


							<span class="formRow">
								<span class="formLabel">
									<label class="">Period To :</label>
								</span>
								<span class="formInput" data-remarks="(dd/mm/yyyy)">
								<?php echo f_html_escape($general['period_date_to']);?>
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
									<label class="">Amount :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape(number_format($general['amount'],2));?>									
								</span>
							</span>
							<span class="formRow">
							</span>

							<span class="formRow">
								<span class="formLabel">
									<label class="">Balance :</label>
								</span>
								<span class="formInput">
								<?php echo f_html_escape(number_format($general['balance'],2));?>									
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
								<span class="formLabel">
								</span>
								<span class="formInput">
									<button type="submit">Confirm</button>
								</span>
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
		