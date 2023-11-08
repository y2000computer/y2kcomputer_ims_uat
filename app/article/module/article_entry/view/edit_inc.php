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
										Headline :<?php echo f_html_escape($general['headline']);?>									
										<?php
										echo '&nbsp;&nbsp;&nbsp;';
										$url = '/'.IS_PORTAL.'/'.IS_LANG.'/www/view?article_id='.$general['article_id'].'&preview=yes&season_id='.$general['season_id'];

										echo '<a class="commonTextBtn" href="'.$url.'" target="_blank">
										Preview Front-End</a>'; 																				
										

										?>
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
		