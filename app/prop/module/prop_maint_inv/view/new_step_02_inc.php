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
								<th style="text-align:left" width ="25%">Building</th>
								<th style="text-align:left" width ="13%">Tenant Code</th>
								<th style="text-align:left" width ="">Name</th>
					
								</tr>
								<?php
								if ($arr_count_general_model >0){
									$i_count=1+($page-1)*SYSTEM_PAGE_ROW_LIMIT;
									foreach ($arr_general_model as $general_model): 
										echo '<tr>';

										echo '<td>'.$i_count++.'</td>';
										echo '<td>'.f_html_escape($general_model['build_eng_name']).'</td>';						
										echo '<td>';
										echo '<a href="'.actionURL('new_step_03','?item_id='.$general_model['tenant_id'].'&lot_id='.$lot_id.'&page='.$page).'">'.f_html_escape($general_model['tenant_code']).'&nbsp;'.'&raquo;'.'</a>';
										echo '</td>';


										echo '<td>'.f_html_escape($general_model['eng_name']).'</td>';


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
		