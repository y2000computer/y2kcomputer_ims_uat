					<div class="cardWrapper">
						<div class="fullWidthContent">
							<span class="contentRow">
								<span class="menu_group_headers noIcon"><span>Image Preview</span></span>
							</span>
							<span class="contentRow">
								<span class="contentMessage">
									<?php 
									$url = actionURL('edit','?tab=general&item_id='.$general_model['photo_id'].'&lot_id='.$lot_id.'&page='.$page.'&panelsize='.$panelsize); 
									//$url = actionURL('edit','?item_id='.$general_model[$dmGeneralModel->primary_keyname()].'&lot_id='.$lot_id.'&page='.$page.'&panelsize='.$panelsize); 
									echo '<form action="'.$url.'" method="POST" >'; 
									echo '<button type="submit">Edit</button>';
									echo '</form>';
									?>
									<?php		
									//echo '<a href="'.actionURL('edit','?item_id='.$general_model[$dmGeneralModel->primary_keyname()].'&lot_id='.$lot_id.'&page='.$page).'">'.'&nbsp;'.'Edit'.'&raquo;'.'</a>';
									//$url = actionURL('edit','?item_id='.$general_model[$dmGeneralModel->primary_keyname()].'&lot_id='.$lot_id.'&page='.$page.'&panelsize='.$panelsize); 
									//echo '<a class="changePwBtn commonTextBtn" href="'. $url .'";">Edit</a>';								
									?>
								</span>
							</span>
							<span class="contentRow">
								<span class="contentMessage">
									<?php
										//$photo_url ='../../../../photos/'. $general_model['small_file_name'];
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general_model['small_file_path']. '/'. $general_model['small_file_name'];
										
										echo '<span class="previewImage" style="background-image: url(\'' . $photo_url . '\');" ></span>';
									?>
								</span>
							</span>
							<span class="contentRow">
								<span class="contentLabel">
									<span class="labelTitle">Caption</span>
								</span>
								<span class="contentMessage">
										<?php
										$caption_show = $general_model['caption'];
										echo '<span class="itemCaption">'. $caption_show . '</span>';								
										?>
								</span>
							</span>
							<span class="contentRow">
								<span class="contentLabel">
									<span class="labelTitle">Year</span>
								</span>
								<span class="contentMessage">
										<?php
										$photo_year = $general_model['photo_year'];
										echo '<span class="message">'. $photo_year . '</span>';								
										?>								
								</span>
							</span>
							<span class="contentRow">
								<span class="contentLabel">
									<span class="labelTitle">Description</span>
								</span>
								<span class="contentMessage">
										<?php
										$desc_show = $general_model['desc'];
										$desc_show = mb_str_replace("\r", "<br>\r", $desc_show);
										echo '<span class="message">'. $desc_show . '</span>';
										?>																
								</span>
							</span>
						   <span class="contentRow">
							   <span class="contentLabel">
								   <span class="labelTitle">RFID</span>
							   </span>
							   <span class="contentMessage">
									<?php
									$rfid = $general_model['rfid'];
									echo '<span class="message">'. $rfid . '</span>';								
									?>															   
							   </span>
						   </span>
						   <span class="contentRow">
							   <span class="menu_group_headers noIcon"><span>Meta Tag</span></span>
						   </span>
						   
						   <?php
						   foreach ($arr_photo_entry_meta_category_distinct_list as $category_distinct_list): 

							   echo '<span class="contentRow">';
								   echo '<span class="contentLabel">';
									   echo '<span class="labelTitle">'. $category_distinct_list['name'] .'</span>';
								   echo '</span>';
								   echo '<span class="contentMessage">';
									   echo '<ul class="innerMessage">';
										$arr_photo_entry_meta_category_sub_distinct_list = $dmGeneralModel->photo_entry_meta_category_sub_distinct_list($photo_id, $category_distinct_list["meta_id"]);
										foreach ($arr_photo_entry_meta_category_sub_distinct_list as $category_sub_distinct_list): 
											   echo '<li>'.$category_sub_distinct_list['name'].'</li>';
										endforeach; 	
									   echo '</ul>';
								   echo '</span>';
							   echo '</span>';
							   
						   endforeach; 	
						   ?>
						   




   							<span class="contentRow">
   								<span class="menu_group_headers noIcon"><span>Download Photos</span></span>
   							</span>
							<span class="contentRow">
								<span class="contentMessage">
									<?php
										if($general_model['psd_file_name'] !='') {
											$photo_url ='../../../../photos/'. $general_model['psd_file_name'];
											$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general_model['psdl_file_path']. '/'. $general_model['psd_file_name'];										
											echo '<a href="' . $photo_url . '" download >[Photoshop PSD]</a>';
											} else {
												echo '<a class="downloadLink linkNotAvailable" href="javascript:void(0);">Photoshop PSD</a>';
											}
									?>	
									<br>
								</span>
								<span class="contentMessage">
									<?php
										//$photo_url ='../../../../photos/'. $general_model['large_file_name'];
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general_model['small_file_path']. '/'. $general_model['large_file_name'];										
										echo '<a href="' . $photo_url . '" download >[Large JPEG]</a>';
									?>
									<br>		
								</span>
								<span class="contentMessage">
									<?php
										//$photo_url ='../../../../photos/'. $general_model['small_file_name'];
										$photo_url =DIR_PHOTOS_PUBLIC_HTML.'/'. $general_model['small_file_path']. '/'. $general_model['small_file_name'];										
										echo '<a href="' . $photo_url . '" download >[Thumbnail JPEG]</a>';
									?>
								</span>
							</span>
							
							
							
							
   							<span class="contentRow">
   								<span class="menu_group_headers noIcon"><span>Download Documents</span></span>
   							</span>
							
							
						   <?php
						   foreach ($arr_photo_document_list as $document): 

							echo '<span class="contentRow">';
								echo '<span class="contentMessage">';
									//$download_url ='../../../../document/'. $document_list['file_name'];
									//echo '<a href="' . $download_url . '" download >['. $document_list['file_name'].']</a>';
									$download_url ='../../../../downloadFile.php?filename='.$document['file_name'].'&path='.$document['file_path'];
									echo '<a href="' . $download_url . '" download >['. $document['file_desc'].']</a>';
									echo '</span>';
								echo '</span>';
						   endforeach; 	
						   ?>
							
							
							
							
							
							
							
   							<span class="contentRow">
   								<span class="menu_group_headers noIcon"><span>Other Informations</span></span>
   							</span>
						   <span class="contentRow">
							   <span class="contentLabel">
								   <span class="labelTitle-remarks">Photo ID</span>
							   </span>
							   <span class="contentMessage">
									<?php
									$photo_code = $general_model['photo_code'];
									echo '<span class="message-remarks">'. $photo_code . '</span>';								
									?>															   							   
							   </span>
						   </span>
						   <span class="contentRow">
							   <span class="contentLabel">
								   <span class="labelTitle-remarks">Created By</span>
							   </span>
							   <span class="contentMessage">
									<?php
									$create_user = $general_model['create_user'];
									echo '<span class="message-remarks">'. $create_user . '</span>';								
									?>															   							   							   
							   </span>
						   </span>
						   <span class="contentRow">
							   <span class="contentLabel">
								   <span class="labelTitle-remarks">Create Date</span>
							   </span>
							   <span class="contentMessage">
									<?php
									$create_datetime = $general_model['create_datetime'];
									echo '<span class="message-remarks">'. $create_datetime . '</span>';								
									?>															   							   							   							   
							   </span>
						   </span>
						   <span class="contentRow">
							   <span class="contentLabel">
								   <span class="labelTitle-remarks">Last Update</span>
							   </span>
							   <span class="contentMessage">
									<?php
									$modify_user = $general_model['modify_user'];
									echo '<span class="message-remarks">'. $modify_user . '</span>';								
									?>															   							   							   
							   </span>
						   </span>
						   <span class="contentRow">
							   <span class="contentLabel">
								   <span class="labelTitle-remarks">Last Update</span>
							   </span>
							   <span class="contentMessage">
									<?php
									$modify_datetime = $general_model['modify_datetime'];
									echo '<span class="message-remarks">'. $modify_datetime . '</span>';								
									?>															   							   							   							   
							   </span>
						   </span>
						</div>
					</div>
