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
										Headline :<?php echo f_html_escape($primary['headline']); 
										echo '&nbsp;&nbsp;&nbsp;&nbsp;';
										echo '<a class="commonTextBtn" href="'.actionURL('media_new_step_one','?item_id='.$item_id.'&lot_id='.$lot_id.'&tab='.$tab).'" >
										Add Media</a>'; 																				
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
										<th style="text-align:left" width ="10%">Media Type</th>
										<th style="text-align:left" width ="3%'">Main</th>
										<th style="text-align:left" width ="3%'">Main<br>Inner</th>
										<th style="text-align:left" width ="3%'">Priority</th>
										<th style="text-align:center" width ="20%">Thumbnail</th>
										<th style="text-align:left" width ="">Caption</th>
										<th style="text-align:left" width ="10%">URL</th>
										<th style="text-align:left" width="10%">Last Update Date</th>
										<th style="text-align:left" width="15%">Last Update By</th>
										<th style="text-align:left" width ="8%">Status</th>
										</tr>

										<?php
											$balance = 0;
											$i_count=1;
											foreach ($arr_media as $media): 

												if ($media['status']==1)  echo '<tr>';
												if ($media['status']==0)  echo '<tr class="deactive">';
												
												echo '<td style="text-align:left" >'.$i_count++.'</td>';
												
												echo '<td style="text-align:left">'.$media['TY_name'].'</td>';
												
												
												echo '<td style="text-align:left">';
												switch($media['main_is']) {
													case "1": echo "Yes"; break;
													case "0": echo "No"; break;
												};
												echo '</td>';	

												echo '<td style="text-align:left">';
												switch($media['main_inner_is']) {
													case "1": echo "Yes"; break;
													case "0": echo "No"; break;
												};
												echo '</td>';	
												
												echo '<td style="text-align:left">'.$media['display_priority'].'</td>';

												
												echo '<td style="text-align:center">';
												if($media['photo_small_file_path']<>'') {
													$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $media['photo_small_file_path']. '/'. $media['photo_small_file_name'];
													echo '<img src="' . $photo_url . '" style="width:250;height:200px;" >';
													} else {
															echo 'No Photo';
													}
												echo '</td>';
												
												
												echo '<td style="text-align:left">';
												echo '<a href="'.actionURL('media_edit','?tab=media&item_id='.$item_id.'&media_id='.$media['media_id'].'&mode='.$mode.'&lot_id='.$lot_id.'&page='.$page).'">'.$media['caption'].'&nbsp;'.'&raquo;'.'</a>';
												echo '</td>';														
								
												/*								
												1,'Photo'
												2,'Youtube Video'
												3,'Self Hosted Video'
												4,'Reference URL'
												5,'Upload Document'
												*/
												
												echo '<td style="text-align:left">';
												switch($media['media_type_id'])
												{
													case "1";
															echo 'N/A';
															break;																									
													case "2";
															echo '<a href="' .$media['youtube_video_url']  . '" target="_blank">' .'[Play]' .'</a>';
															break;												
													case "3";
															if($media['video_file_path']<>'') {
																$video_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $media['video_file_path']. '/'. $media['video_file_name'];
																echo '<a href="' .$video_url  . '" target="_blank">' .'[Play]' .'</a>';
																} else {
																		echo 'No Video';
																}
															break;												
													case "4";
															echo '<a href="' .$media['reference_url']  . '" target="_blank">' .'[Click to open]' .'</a>';
															break;												
													case "5";
															echo '<a href="../../../../downloadFile.php?filename='.$media['upload_file_name'].'&path='.$media['upload_file_path'].'" target="_blank">';
															echo '[Download]';
															echo '</a>';
															break;												
															
												}		
												echo '</td>';
												


												
												
				
												echo '<td style="text-align:left">'.toDMY($media['modify_datetime']).'</td>';
												echo '<td style="text-align:left">'.$media['modify_user'].'</td>';
												
												echo '<td style="text-align:left">';
												switch($media['status']) {
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
		