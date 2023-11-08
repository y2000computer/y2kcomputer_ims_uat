					<ul>
						<li class="main_menu_selected"><span>Article Entry</span></li>
						<li class="main_menu_unselected">
						<?php echo '<a href="/'.IS_PORTAL.'/'.IS_LANG.'/article_entry/new">'.'Add Article'.'</a>'?></li>
					</ul>

					<div class="cardWrapper">
						<?php echo '<form action="'.actionURL('search','').'" method="post" >'; ?>
							<span class="formRow">
								<span class="menu_group_headers searchUser"><span>Search</span></span>
							</span>


							<span class="formRow">
								<span class="formLabel">
									<label for="userModule_email" class="">Season</label>
								</span>
								<span class="formInput">
									<select name="general[season_id]" class="four">
									<?php
									echo '<option value=""'.' '.($general['season_id']  ==''?'selected':'').'>'.'Please select'.'</option>';
									foreach ($arr_article_season_master  as $article_season_master) { 
									  echo '<option value="'.$article_season_master['season_id'].'"'.' '.($json_search_items['general']['season_id']  == $article_season_master['season_id']?'selected':'').'>'.$article_season_master['name'].'</option>';
									}
									?>
									</select>									
								</span>


							<span class="formRow">
								<span class="formLabel">
									<label for="userModule_email" class="">Category</label>
								</span>
								<span class="formInput">
									<select name="general[cate_id]" class="four">
									<?php
									echo '<option value=""'.' '.($general['cate_id']  ==''?'selected':'').'>'.'Please select'.'</option>';
									foreach ($arr_article_category_master  as $article_category_master) { 
									  echo '<option value="'.$article_category_master['cate_id'].'"'.' '.($json_search_items['general']['cate_id']  == $article_category_master['cate_id']?'selected':'').'>'.$article_category_master['name'].'</option>';
									}
									?>
									</select>								
								</span>

								
							
							<span class="formRow">
								<span class="formLabel">
									<label for="userModule_email" class="">Headline</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[headline]"  autocomplete="off" class="five" value="<?php echo f_html_escape($json_search_items['general']['headline']);?>" />
								</span>
							
							
							
							<span class="formRow">
								<button type="submit">Search</button>
							</span>
						</form>
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