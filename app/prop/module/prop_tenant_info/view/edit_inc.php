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
								<input type="hidden" name="general[build_id]"  value="<?php echo f_html_escape($general['build_id']);?>" />
									<?php echo $general['build_eng_name']; ?>

									</select>
								</span>
							</span>
							<span class="formRow">
							</span>					
						
						
						
							<span class="formRow">
								<span class="formLabel">
									<label class="">Tenant Code :</label>
								</span>
								<span class="formInput">
									<input type="hidden" name="general[tenant_code]"  value="<?php echo f_html_escape($general['tenant_code']);?>" />
										<?php echo $general['tenant_code']; ?>
								</span>
							</span>
							<span class="formRow">
							</span>
							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Name :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[eng_name]"  size="100" required class="fourteen" value="<?php echo f_html_escape($general['eng_name']);?>" />
								</span>
							</span>
							<span class="formRow">
							</span>
							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Add(1) :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[add_1]"  size="100" class="thirteen" value="<?php echo f_html_escape($general['add_1']);?>" />
								</span>
							</span>
							<span class="formRow">
							</span>

							<span class="formRow">
								<span class="formLabel">
									<label class="">Add(2) :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[add_2]"  size="100"  class="thirteen" value="<?php echo f_html_escape($general['add_2']);?>" />
								</span>
							</span>
							<span class="formRow">
							</span>

							<span class="formRow">
								<span class="formLabel">
									<label class="">Add(3) :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[add_3]"  size="100"  class="thirteen" value="<?php echo f_html_escape($general['add_3']);?>" />
								</span>
							</span>
							<span class="formRow">
							</span>


							<span class="formRow">
								<span class="formLabel">
									<label class="">Ref No. :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[ref_no]"  size="100"  class="ten" value="<?php echo f_html_escape($general['ref_no']);?>" />
								</span>
							</span>
							<span class="formRow">
							</span>


							<span class="formRow">
								<span class="formLabel">
									<label class="">Shop No. :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[shop_no]"  size="100"  class="ten" value="<?php echo f_html_escape($general['shop_no']);?>" />
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
									<label class="">Rent Bill Date :</label>
								</span>
								<span class="formInput" data-remarks="(dd/mm/yyyy)">
									<input id="rent_date" class="datepicker" required style="width: 140px" type="text" name="general[rent_date]" autocomplete="off" value="<?php echo $general['rent_date'];?>" placeholder="dd/mm/yyyy" maxlength="10">
								</span>
							</span>
							<span class="formRow">
							</span>							


							<span class="formRow">
								<span class="formLabel">
									<label class="">Rent Amount :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[rent_amount]"  size="30" required class="four" value="<?php echo f_html_escape($general['rent_amount']);?>" />
								</span>
							</span>
							<span class="formRow">
							</span>



							<span class="formRow">
								<span class="formLabel">
									<label class="">Maint. Bill Date :</label>
								</span>
								<span class="formInput" data-remarks="(dd/mm/yyyy)">
									<input id="maint_date" class="datepicker" required style="width: 140px" type="text" name="general[maint_date]" autocomplete="off" value="<?php echo $general['maint_date'];?>" placeholder="dd/mm/yyyy" maxlength="10">
								</span>
							</span>
							<span class="formRow">
							</span>							


							<span class="formRow">
								<span class="formLabel">
									<label class="">Maint. Amount :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[maint_amount]"  size="30" required class="four" value="<?php echo f_html_escape($general['maint_amount']);?>" />
								</span>
							</span>
							<span class="formRow">
							</span>


							<span class="formRow">
								<span class="formLabel">
									<label class="">Print Type :</label>
								</span>
								<span class="formInput">
										<input type="text" name="general[ptype]"  size="30" required class="two" value="<?php echo f_html_escape($general['ptype']);?>" />
										(Either 0 (KongOn) or 1(YeeLim))
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
		