					<ul>
						<li class="main_menu_selected"><span>Season Master</span></li>
						<li class="main_menu_unselected">
						<?php echo '<a href="/'.IS_PORTAL.'/'.IS_LANG.'/article_season_master/new">'.'Add Season'.'</a>'?></li>
					</ul>

					<div class="cardWrapper">
						<?php echo '<form action="'.actionURL('search','').'" method="post" >'; ?>
							<span class="formRow">
								<span class="menu_group_headers searchUser"><span>Search</span></span>
							</span>

					
							
							<span class="formRow">
								<span class="formLabel">
									<label for="userModule_email" class="">Name</label>
								</span>
								<span class="formInput">
									<input type="text"  name="general[name]"  autocomplete="off" class="five" value="<?php echo htmlspecialchars($json_search_items['general']['name']);?>" />
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