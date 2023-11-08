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
									<label class="">Season:</label>
								</span>
								<span class="formInput">
									<select name="general[season_id]" required >
									<?php
									echo '<option value=""'.' '.($general['season_id']  ==''?'selected':'').'>'.'Please select'.'</option>';
									foreach ($arr_article_season_master  as $article_season_master) { 
									  echo '<option value="'.$article_season_master['season_id'].'"'.' '.($general['season_id']  == $article_season_master['season_id']?'selected':'').'>'.$article_season_master['name'].'</option>';
									}
									?>
									</select>
								</span>
							</span>							
							<span class="formRow">
							</span>

						

							<span class="formRow">
								<span class="formLabel">
									<label class="">Category:</label>
								</span>
								<span class="formInput">
									<select name="general[cate_id]" required >
									<?php
									echo '<option value=""'.' '.($general['cate_id']  ==''?'selected':'').'>'.'Please select'.'</option>';
									foreach ($arr_article_category_master  as $article_category_master) { 
									  echo '<option value="'.$article_category_master['cate_id'].'"'.' '.($general['cate_id']  == $article_category_master['cate_id']?'selected':'').'>'.$article_category_master['name'].'</option>';
									}
									?>
									</select>
								</span>
							</span>							
							<span class="formRow">
							</span>
						

						
						
							<span class="formRow">
								<span class="formLabel">
									<label class="">Date:</label>
								</span>
								<span class="formInput" data-remarks="(dd/mm/yyyy)">
									<input id="article_date" class="datepicker" required style="width: 140px" type="text" name="general[article_date]" autocomplete="off" value="<?php echo $general['article_date'];?>" placeholder="dd/mm/yyyy" maxlength="10">
								</span>
							</span>							
							<span class="formRow">
							</span>

						
							
							<span class="formRow">
								<span class="formLabel">
									<label class="">Headlline:</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[headline]"  autocomplete="off" class="thirteen" required value="<?php echo f_html_escape($general['headline']);?>" />
								</span>
							</span>							
							<span class="formRow">
							</span>

							
							
							<span class="formRow formRow-2col" >
								<span class="formLabel">
									<label class="">Content :</label>
								</span>
								<span class="formInput"  >
									<textarea  class="fifteen" required rows="16"  name="general[content]"><?php echo f_html_escape($general['content']);?></textarea>
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
		