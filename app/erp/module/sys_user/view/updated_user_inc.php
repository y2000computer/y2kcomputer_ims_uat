<?php
require __DIR__.'/../../../../template/header_inc.php';
?>
<?
phpforeach ($arr_user as $user): 
endforeach;
?>
		<div class="bodyContent breadcrumbExist" id="BodyDiv">
			<div class="contentWrapper" id="BodyWrapDiv">
				<div class="headerNavigation">
					<?php require __DIR__.'/navigation_menu_inc.php'; ?>
					Edit 
				</div>
				<div class="sidebarContent">
					<div class="sidebarContentCol">
						<?php echo '<form class="fullWidthForm fullWidthForm-2col" action="'.actionURL('search','?lot_id='.$lot_id.'&page='.$page).'" method="post" >';?>
						
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
									<label class="">Email :</label>
								</span>
								<span class="formInput">
										<span class="message"><?php echo f_html_escape($user['email']);?></span>
								</span>
							</span>
							<span class="formRow">
							</span>
							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Password:</label>
								</span>
								<span class="formInput">
										<span class="message"><?php echo f_html_escape($user['password']);?></span>
								</span>
							</span>							
							<span class="formRow">
							</span>

							<span class="formRow">
								<span class="formLabel">
									<label class="">Last Name:</label>
								</span>
								<span class="formInput">
										<span class="message"><?php echo f_html_escape($user['last_name']);?></span>
								</span>
							</span>							
							<span class="formRow">
							</span>

							<span class="formRow">
								<span class="formLabel">
									<label class="">First Name:</label>
								</span>
								<span class="formInput">
									<span class="message"><?php echo f_html_escape($user['first_name']);?></span>
								</span>
							</span>							
							<span class="formRow">
							</span>

							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Department:</label>
								</span>
								<span class="formInput">
									<span class="message">
										<?php
										foreach ($arr_department_all as $dc): 
											if($dc['depart_code'] == $user['depart_code']) echo $dc['eng_name'];
										endforeach; 
										?>
									</span>
								</span>
							</span>							
							<span class="formRow">
							</span>

							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Status:</label>
								</span>
								<span class="formInput">
									<span class="message">
										<?php
										if($user['status'] == 1){
										echo 'Active';
										} else {
										echo 'De-active';
										}
										?>
									</span>
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
									<button type="submit">Close</button>
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
		