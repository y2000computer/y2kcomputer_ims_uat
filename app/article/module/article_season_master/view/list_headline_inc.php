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
										Name: <?php echo f_html_escape($general['name']);?>									
										<?php
										echo '&nbsp;&nbsp;&nbsp;&nbsp;';
										echo '<a class="commonTextBtn" href="'.actionURL('headline_new','?item_id='.$item_id.'&lot_id='.$lot_id.'&tab='.$tab).'" >
										Add Headline</a>'; 																				
										?>									
									</span>
								</span>
							</span>
						</div>
						<form class="fullWidthForm fullWidthForm-2col" action="" method="post" style="padding-top: 0;">


							<span class="formRow formRow-2col">
								<table class="searchResult" border="0" cellspacing="0" cellpadding="0">
									<tbody>
										<tr>
										<th style="text-align:left" width ="3%'"><?php echo _t("No");?></th>
										<th style="text-align:left" width ="15%">Category</th>
										<th style="text-align:left" width ="50%'">Headline</th>
										<th style="text-align:left" width="10%">Last Update Date</th>
										<th style="text-align:left" width="15%">Last Update By</th>
										<th style="text-align:left" width ="8%">Status</th>
										</tr>

										<?php
											$balance = 0;
											$i_count=1;
											foreach ($arr_headline as $headline): 

												if ($headline['status']==1)  echo '<tr>';
												if ($headline['status']==0)  echo '<tr class="deactive">';
												
												echo '<td style="text-align:left" >'.$i_count++.'</td>';
												
												echo '<td style="text-align:left">'.$headline['category_name'].'</td>';
												
												
												echo '<td style="text-align:left">';
												echo '<a href="'.actionURL('headline_edit','?tab=headline&item_id='.$item_id.'&season_headline_id='.$headline['season_headline_id'].'&mode='.$mode.'&lot_id='.$lot_id.'&page='.$page).'">'.$headline['headline'].'&nbsp;'.'&raquo;'.'</a>';
												echo '</td>';												
				
												echo '<td style="text-align:left">'.toDMY($headline['modify_datetime']).'</td>';
												echo '<td style="text-align:left">'.$headline['modify_user'].'</td>';
												
												echo '<td style="text-align:left">';
												switch($headline['status']) {
													case "1": echo "Active"; break;
													case "0": echo "De-active"; break;
												};
												echo '</td>';	
												
												echo '</tr>';
											endforeach; 	

											
										?>	
										
									<tbody>
								</table>
							</span>	
										
							
							
						</form>
					</div>
				</div>
			</div>
		</div>
		
				
 		
<?php
require __DIR__.'/../../../../template/footer_inc.php';
?>
		