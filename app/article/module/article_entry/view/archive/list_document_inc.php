<?php
require __DIR__.'/../../../template/header_inc.php';
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
										Headline :<?php echo f_html_escape($primary['headline']); 
										echo '&nbsp;&nbsp;&nbsp;&nbsp;';
										echo '<a class="commonTextBtn" href="'.actionURL('document_new','?item_id='.$item_id.'&lot_id='.$lot_id.'&tab='.$tab).'" >
										Add Document</a>'; 																				
										?>									
									</span>
								</span>
							</span>
						</div>
						<form class="fullWidthForm fullWidthForm-2col" action="" method="post" style="padding-top: 0;">


							<span class="formRow formRow-2col">
								<table class="searchResult" border="0" cellspacing="0" cellpadding="0">
										<tr>
										<th style="text-align:left" width ="5%'"><?php echo _t("No");?></th>
										<th style="text-align:left" width ="">Description</th>
										<th style="text-align:left" width ="15%'">Orginal File Name</th>
										<th style="text-align:center" width ="10%'">Download</th>
										<th style="text-align:left" width="12%">Last Update Date</th>
										<th style="text-align:left" width="22%">Last Update By</th>
										<th style="text-align:left" width ="10%">Status</th>
										</tr>

										<?php
											$balance = 0;
											$i_count=1;
											foreach ($arr_document as $document): 

												if ($document['status']==1)  echo '<tr>';
												if ($document['status']==0)  echo '<tr class="deactive">';
												
												echo '<td style="text-align:left" >'.$i_count++.'</td>';
												
												echo '<td style="text-align:left">';
												echo '<a href="'.actionURL('document_edit','?tab=document&item_id='.$item_id.'&doc_id='.$document['doc_id'].'&mode='.$mode.'&lot_id='.$lot_id.'&page='.$page).'">'.$document['file_desc'].'&nbsp;'.'&raquo;'.'</a>';
												echo '</td>';														
				
												echo '<td style="text-align:left">'.$document['original_file_name'].'</td>';

												echo '<td style="text-align:center">';
												
												echo '<a href="../../../../downloadFile.php?filename='.$document['file_name'].'&path='.$document['file_path'].'" target="_blank">';
												echo '[Download]';
												echo '</a>';

												echo '</td>';
									
	
				
												echo '<td style="text-align:left">'.toDMY($document['modify_datetime']).'</td>';
												echo '<td style="text-align:left">'.$document['modify_user'].'</td>';
												
												echo '<td style="text-align:left">';
												switch($document['status']) {
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
require __DIR__.'/../../../template/footer_inc.php';
?>
		