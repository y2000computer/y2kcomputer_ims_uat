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
									<label class="">Caption:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[caption]"  required autocomplete="off" class="twelve" value="<?php echo htmlspecialchars($json_search_items['general']['caption']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>

							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Year:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[photo_year]"  autocomplete="off" class="three" value="<?php echo htmlspecialchars($json_search_items['general']['photo_year']);?>" />
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
									<textarea  class="seven" rows="6" cols="60" name="general[desc]"><?php echo htmlspecialchars($json_search_items['general']['desc']);?></textarea>
								</span>
							</span>							
							<span class="formRow">
							</span>

							

							<span class="formRow">
								<span class="formLabel">
									<label class="">RFID:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[rfid]"  autocomplete="off" class="five" value="<?php echo htmlspecialchars($json_search_items['general']['rfid']);?>" />
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
								<span class="formLabel" " style="min-width: 250px; max-width: 250px;">
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
		
											echo '<input id="'.$input_id.'" type="checkbox" name="general['.$input_id.']" value="1" ' . (($json_search_items['general'][$input_id])?'checked':'') . ' >';
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
									<button type="submit">Next to Upload</button>
								</span>
								<span class="formInput" >
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
		