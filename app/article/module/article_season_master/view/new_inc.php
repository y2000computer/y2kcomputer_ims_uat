<?php
require __DIR__.'/../../../../template/header_inc.php';
?>
<?php
if ($IS_action=='new') {
	$general['status'] = 1;
}
?>
		<div class="bodyContent breadcrumbExist" id="BodyDiv">
			<div class="contentWrapper" id="BodyWrapDiv">
				<div class="headerNavigation">
					<?php require __DIR__.'/navigation_menu_inc.php'; ?>
					Add 
				</div>
				<div class="sidebarContent">
					<div class="sidebarContentCol">
						<?php echo '<form class="fullWidthForm fullWidthForm-2col" action="'.actionURL('create','').'" method="post" >';?>
						
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
								<span class="formInput">
									<input type="text"  name="general[name]"  autocomplete="off" class="eight" required value="<?php echo f_html_escape($json_search_items['general']['name']);?>" />
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
									<textarea  class="fifteen" rows="16"  name="general[content]"><?php echo f_html_escape($general['content']);?></textarea>
								</span>
							</span>							


							<span class="formRow">
								<span class="formLabel">
									<label class="">Publish:</label>
								</span>
								<span class="formInput">
									<label class="">Pending</label>
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
		