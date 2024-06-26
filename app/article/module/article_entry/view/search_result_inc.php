<?php
require __DIR__.'/../../../../template/header_inc.php';
?>
<?php
$json_search_items = json_decode($json_searchphrase, true);
?>
		<div class="bodyContent sideBarExist breadcrumbExist" id="BodyDiv">
			<div class="contentWrapper" id="BodyWrapDiv">
				<div class="headerNavigation">
				<?php require __DIR__.'/navigation_menu_inc.php'; ?>
				</div>
				<div class="sidebar" id="MainMenuDiv">
				<?php require __DIR__.'/left_menu_inc.php'; ?>
				</div>
				<?php 
				$arr_count_general_model = count($arr_general_model);
				$ifound_record = 0;
				if ($arr_count_general_model >0) $ifound_record = $paging->getRecordCount();
				?>
				<div class="sidebarContent" id="SubMenuDiv">
					<div class="sidebarContentCol" id="TransactionsDiv">
						<span class="menu_group_headers"><span>Total Record Found: <?php echo $ifound_record;?></span></span>
						<table class="searchResult" border="0" cellspacing="0" cellpadding="0">
							<tbody>
								<tr>
								<th style="text-align:left" width ="5%'"><?php echo _t("No");?></th>
								<th style="text-align:left" width ="10%">Season</th>
								<th style="text-align:left" width ="8%">Category</th>
								<th style="text-align:left" width ="8%">Date</th>
								<th style="text-align:left" width ="">Headline</th>
								<th style="text-align:left" width ="8%'">Preview</th>
								<th style="text-align:left" width ="8%'">Publish</th>
								<th  style="text-align:left" width="11%">Last Update Date</th>
								<th  style="text-align:left" width="15%">Last Update By</th>
								<th style="text-align:left" width ="8%">Status</th>
								</tr>
								<?php
								if ($arr_count_general_model >0){
									$i_count=1+($page-1)*SYSTEM_PAGE_ROW_LIMIT;
									foreach ($arr_general_model as $general_model): 
										if ($general_model['status']==1)  echo '<tr>';
										if ($general_model['status']==0)  echo '<tr class="deactive">';
										echo '<td>'.$i_count++.'</td>';


										echo '<td>'.$general_model['season_name'].'</td>';
										echo '<td>'.$general_model['category_name'].'</td>';
										echo '<td>'.toDMY($general_model['article_date']).'</td>';

										echo '<td>';
										echo '<a href="'.actionURL('edit','?item_id='.$general_model[$dmGeneralModel->primary_keyname()].'&lot_id='.$lot_id.'&page='.$page).'">'.f_html_escape($general_model['headline']).'&nbsp;'.'&raquo;'.'</a>';
										echo '</td>';
										
										echo '<td>';
										$url = '/'.IS_PORTAL.'/'.IS_LANG.'/www/view?article_id='.$general_model['article_id'].'&preview=yes&season_id='.$general_model['season_id'];
										echo '<a href="'.$url.'" target="_blank" >[Preview]</a>';
										echo '</td>';


										
										echo '<td>';		
										switch($general_model['publish_is']) {
											case "1": echo "Published"; break;
											case "0": echo "Pending"; break;
										};
										echo '</td>';																				
										
										echo '<td>'.toDMY($general_model['modify_datetime']).'</td>';
										echo '<td>'.$general_model['modify_user'].'</td>';
										
										echo '<td>';		switch($general_model['status']) {
											case "1": echo "Active"; break;
											case "0": echo "De-active"; break;
										};
										echo '</td>';
										echo '</tr>';
									endforeach; 	
								}
								?>	
							</tbody>
						</table>
						<span class="pagination">
						<?php 
						if ($arr_count_general_model >0){
							
							if($paging->getTotalPages()>1) { 
								if($page >1 ){
									echo '<a href="'.actionURL('search','?lot_id='.$lot_id.'&page='.($page-1)).'" class="pageResults">'.'&lt;&lt;</a>';
								}
							}
							echo PHP_EOL;
							for($x = 1; $x <= $paging->getTotalPages(); $x++): 
								echo PHP_EOL;
								if($x == $page ){
									echo '<span class="pageResults pageResultsActive">'.$x.'</span>';
									}else {
										echo '<a href="'.actionURL('search','?lot_id='.$lot_id.'&page='.$x).'" class="pageResults">'.$x.'</a>';
										}
								endfor; 


							if($paging->getTotalPages()>1) { 
								if($page < $paging->getTotalPages() ){
									echo '<a  href="'.actionURL('search','?lot_id='.$lot_id.'&page='.($page+1)).'" class="pageResults">'.'&gt;&gt;</a>';
								}
							}
							
							echo PHP_EOL;
							
						}	//if ($arr_count_general_model >0){
						?>
						
						
						

						</span>
					</div>
				</div>
			</div>
		</div>

<?php
require __DIR__.'/../../../../template/footer_inc.php';
?>
		