<?php
class photo_entry_model extends dataManager
{
	private $dbh;
	private $primary_table;
	private $primary_keyname;
	private $primary_indexname;
	
	private $table_field;  // variable for dataManager
	private $errorMsg;   // variable for dataManager
	private $mainTable;   // variable for dataManager

	public function __construct()
    {
		parent::__construct();
    	$this->mainTable='tbl_photo_entry';
    	$this->setTable($this->mainTable);
    	$this->setErrorMsg('Photo Library -> Maintenance -> Photo Entry -> SQL error:');
    	$this->table_field=$this->getTableField();

		$this->primary_keyname = 'photo_id';
		$this->primary_indexname = 'photo_id';
		try {
			$this->dbh = new PDO(DB_CONNECTION_STRING,DB_USERNAME,DB_PASSWORD);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->dbh->query("set names utf8");
			} catch (PDOException $e) {
				print 'Error!: ' . $e->getMessage();
				die();
				}
	}
	
   public function primary_keyname()
	{
		return $this->primary_keyname;
	}

   public function search($jsondata)
	{
		$json = json_decode($jsondata, true);
	
		$sql_filter = "";
		
		
		if($json['general']['keyword']<>"") {
			//if(!empty($sql_filter)) $sql_filter.=" OR ";
			//$sql_filter .= " PHOTO.caption LIKE '%".addslashes($json['general']['keyword'])."%'" ;
			
			//Handle keyword and high light the keyword in red color 
			$keyword_input_is =false;
			if(f_html_escape($json['general']['keyword'])<>'' ) $keyword_input_is = true;

			//if( $keyword_input_is == true )  echo '<br> keyword_input_is is true<br>';
			//if( $keyword_input_is == false)  echo '<br> keyword_input_is is false<br>';
								
			//verify keyword input, filter error_get_last
			$pattern_cks  = explode(',', f_html_escape($json['general']['keyword']));
			$pattern_checkeds =array();
			$i = 0;
			$p = 0;
			foreach($pattern_cks  as $pattern_ck){
				//echo '<br> pattern_ck['.$i.'] = '. $pattern_ck.' ->len = '.strlen($pattern_ck).'<br>';
				$pattern_ck=mb_trim($pattern_ck) ;
					
				if($pattern_ck != '') {
						$pattern_checkeds[$p]=$pattern_ck;
						$p += 1;
				}
				$i += 1;
			}
			
			if( count($pattern_checkeds) >0 )  {
				$keyword_input_is =true;
				$sql_filter .= '  ( ';
				$sql_pattern_filter = '';
				foreach($pattern_checkeds  as $pattern_checked){
					if(!empty($sql_pattern_filter)) $sql_pattern_filter.=" OR ";
					//echo '<br> pattern_checked = '. $pattern_checked.'<br>';
					$sql_pattern_filter .= " PHOTO.caption LIKE '%".addslashes($pattern_checked)."%'" ;
				}
				$sql_filter .= $sql_pattern_filter.  ' ) ';
			}


			
		} //if($json['general']['keyword']<>"") {
		

		if($json['general']['rfid']<>"") {
			if($keyword_input_is==true) $sql_filter.="  AND ( ";
			if($keyword_input_is==false) $sql_filter.="  ( ";
			$sql_filter .= " PHOTO.rfid = '".addslashes($json['general']['rfid'])."'" ;
			$sql_filter.=" ) ";
		}
		
		//Handle meta tag filter
		$meta_tag_input_is =false;
		$arr_photo_meta_category = $this->photo_meta_category_viewall(); 
	
		$sql_meta_tag_filter = '';
		foreach ($arr_photo_meta_category as $row): 
			$arr_photo_category_sub = $this->photo_category_sub_list($row['meta_id']);
				foreach ($arr_photo_category_sub as $sub): 
					$input_id='sub_id_'.$sub['sub_id'];
					if ($json['general'][$input_id]==1) {
						$meta_tag_input_is = true;
						if(!empty($sql_meta_tag_filter)) $sql_meta_tag_filter.=" OR ";
						$sql_meta_tag_filter .= " PHOTOSUB.sub_id = '".addslashes($sub['sub_id'])."'" ;
						//echo '<br> MATCH $input_id = '.$input_id.'<br>';

						} else {
							//echo '<br> NO-match $input_id = '.$input_id.'<br>';
							
						}//if ($json['general'][$input_id]==1) {
				endforeach; 				
			endforeach; 	
		
		
		if($meta_tag_input_is == true) {
			$sql_meta_tag_filter = ' ( ' . $sql_meta_tag_filter. ' ) ';
			$sql = "SELECT DISTINCT PHOTOSUB.photo_id  FROM tbl_photo_entry_meta_category_sub AS PHOTOSUB ";
			$sql .= " WHERE ";		
			
			$sql .= "  ". $sql_meta_tag_filter ;
			$sql .= " ORDER BY PHOTOSUB.sub_id  ASC " ;
			//echo "<br>sql:".$sql."<br>";
			$arr_primary_id =array();
			$rows = $this->runSQLAssoc($sql);	
			foreach ($rows as $row): 
				$arr_photo_ids[] = "'". addslashes($row['photo_id']) ."'";
			endforeach; 

		
		} //if($meta_tag_input_is == true) {
			
		//<end> Handle meta tag filter
		
		
		//echo "<br>sql_filter:".$sql_filter."<br>";
		
		
		$sql = "SELECT "."PHOTO.".$this->primary_keyname. " FROM tbl_photo_entry AS PHOTO ";
		$sql .= " WHERE ";		
		
		$sql .= " (1) " ;
		$sql .= "  AND PHOTO.status = 1 " ;

		if(!empty($sql_filter)) $sql .= " AND  ".$sql_filter ;
		
		if ($arr_photo_ids > 0){	  
			$sql .= " AND  PHOTO.photo_id IN ( ";
			$sql_photo_id_filter ='';
			foreach ($arr_photo_ids as $photo_id): 
				if(!empty($sql_photo_id_filter)) $sql_photo_id_filter.=" , ";
				$sql_photo_id_filter .=  $photo_id;
				endforeach; 	
			$sql .= $sql_photo_id_filter . " ) ";
		}
		
		$sql .= " ORDER BY ". $this->primary_indexname . "  ASC " ;
		$sql .= " LIMIT 1000 ;";
		//echo "<br>sql:".$sql."<br>";

		$arr_primary_id =array();
		$rows = $this->runSQLAssoc($sql);	
		foreach ($rows as $row): 
			$arr_primary_id[] = "'". addslashes($row[$this->primary_keyname]) ."'";
		endforeach; 
		

		$array_count = count($arr_primary_id);

		if ($array_count > 0){	  
			$result_id = implode(",", $arr_primary_id);
		}
		
		$lot_id = strtotime(date("Y-m-d H:i:s")).rand(0, 10);;

	
		$sql = 'INSERT INTO `tbl_sys_paging_control`(
					`searchphrase`,
					`lot_id`,
					`result_id`,
					`create_user`,
					`create_datetime`
					) VALUES (';
		$sql.='\''.addslashes($jsondata).'\''.',';
		$sql.='\''.addslashes($lot_id).'\''.',';
		$sql.='\''.addslashes($result_id).'\''.',';
		$sql.='\''.addslashes($_SESSION["sUserID"]).'\''.',';
		$sql.='now()'.')';

		$last_insert_id = $this->runSQLReturnID($sql);		


		return $lot_id;
	}

 
  public function retreive_content($lot_id,$page)
	{

		$sql = "SELECT * FROM tbl_sys_paging_control WHERE lot_id = '".$lot_id."' AND create_user =	'".$_SESSION['sUserID']."';";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	


		$arr =  $arr_record[0];
		$arr_primary_id = $arr['result_id']; 
		
		
		if ($arr_primary_id != '')		
		{

			
			$sql = "SELECT PHOTO.* FROM tbl_photo_entry AS PHOTO ";
		
			if(!empty($arr_primary_id)) $sql .= "WHERE "."PHOTO.".$this->primary_keyname. " in (".$arr_primary_id.")" ;
			$sql .= " ORDER BY PHOTO." .$this->primary_indexname. ' ASC ' ;
			$sql .= " LIMIT ". SYSTEM_PAGE_ROW_LIMIT . " OFFSET  ".($page-1)*SYSTEM_PAGE_ROW_LIMIT ;
			//echo "<br>sql:".$sql."<br>";
			$arr_record = $this->runSQLAssoc($sql);	
					
			return $arr_record;
		}
		
		return $arr_record;
	}

  public function paging_config($lot_id)
	{
		$sql = "SELECT * FROM tbl_sys_paging_control WHERE lot_id = '".$lot_id."' AND create_user =	'".$_SESSION['sUserID']."';";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		$result_id = $arr_record[0]['result_id'];


		$sql ="UPDATE `tbl_sys_paging_control` SET modify_datetime =now()	WHERE lot_id ='$lot_id'";
		$void = $this->runSQLReturnID($sql);


		return $result_id;
	}	

    public function searchphrase($lot_id)
	{
		$sql = "SELECT searchphrase FROM tbl_sys_paging_control WHERE lot_id = '".$lot_id."' AND create_user =	'".$_SESSION['sUserID']."';";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		$searchphrase = $arr_record[0]['searchphrase'];
		return $searchphrase;
				
	}		

    public function select($primary_id)
	{
 		$sql ="SELECT * FROM tbl_photo_entry  WHERE ".$this->primary_keyname. " = '$primary_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record[0];
	}		
	
 
	public function create($general)
	{

		$photo_year = $general['photo_year'];
		$caption = $general['caption'];
		$desc = $general['desc'];
		$rfid = $general['rfid'];
		$status = 1;
		$create_user = $_SESSION['sUserID'];		

		$ar_fields=array();
		$ar_fields['photo_year'] = $photo_year;
		$ar_fields['caption'] = $caption;
		$ar_fields['desc'] = $desc;
		$ar_fields['rfid'] = $rfid;
		$ar_fields['status'] = $status;
		$ar_fields['create_user'] = $create_user;
		$ar_fields['modify_user'] = $create_user;
		$ar_fields['create_datetime'] = 'now()';
		$ar_fields['modify_datetime'] = 'now()';

		
		//Generate YYMMDD9999 photo_code
		$YYMMDD =date('ymd');
		//echo '<br>YYMMDD ='.$YYMMDD.'<br>'	;
		$sql = "SELECT MAX(photo_code) as max  FROM tbl_photo_entry WHERE left(photo_code,6)='".$YYMMDD."'";
		//echo '<br>'.$sql.'<br>';		
		$arr_record = $this->runSQLAssoc($sql);	
		$photo_code_max = $arr_record[0]['max'];

		//echo '<br>photo_code_max ='.$photo_code_max.'<br>'	;
		
		if($photo_code_max ==null )	{
			$photo_code_max_no = 0;	  
		} else {
			$photo_code_max_no = substr($photo_code_max, 6, 4); 
		}
		
		$photo_code_max_no = $photo_code_max_no+1;
		//echo 'photo_code_max_no ='.$photo_code_max_no.'<br>'	;
		$photo_code =$YYMMDD.str_pad($photo_code_max_no,4,0,STR_PAD_LEFT);
		//echo 'photo_code ='.$photo_code.'<br>'	;
		
		//<end>Generate YYMMDD9999 photo_code

		$ar_fields['photo_code'] = $photo_code;
	
		$sql= $this->createInsertSql($ar_fields,$this->table_field,$this->mainTable);
		//echo '<br> sql : '.$sql.'<br>';
		$last_insert_id = $this->runSQLReturnID($sql);

		
		//Handle meta tag record
		$arr_photo_meta_category = $this->photo_meta_category_viewall(); 		
		foreach ($arr_photo_meta_category as $row): 
			$arr_photo_category_sub = $this->photo_category_sub_list($row['meta_id']);
			foreach ($arr_photo_category_sub as $sub): 
				$sub_id = $sub['sub_id'];
				$input_id='sub_id_'.$sub['sub_id'];
				if ($general[$input_id]==1) {

						$sql = "INSERT INTO `tbl_photo_entry_meta_category_sub`(
										`photo_id`
										,`sub_id`
										,`status`
										,`create_user`
										,`modify_user`
										,`create_datetime`
										,`modify_datetime`
										) VALUES (
											'$last_insert_id'
											,'$sub_id'
											,'$status'
											,'$create_user'
											,'$create_user'
											,now()
											,now()
											)";

						//echo '<br>'.$sql.'<br>';		
						$void = $this->runSQLReturnID($sql);
						
				
				}
			endforeach; // foreach ($arr_photo_category_sub as $sub):  				
		endforeach;  // foreach ($arr_photo_meta_category as $row): 			
		
		
			
		return $last_insert_id;
	}
	
	
	public function update($primary_id, $general)
	{

		$photo_year = $general['photo_year'];
		$caption = $general['caption'];
		$desc = $general['desc'];
		$rfid = $general['rfid'];
		$status = $general['status'];
		$modify_user = $_SESSION['sUserID'];

		$ar_fields=array();
		$ar_fields['photo_year'] = $photo_year;
		$ar_fields['caption'] = $caption;
		$ar_fields['desc'] = $desc;
		$ar_fields['rfid'] = $rfid;
		$ar_fields['status'] = $status;
		$ar_fields['modify_user'] = $modify_user;
		$ar_fields['modify_datetime'] = 'now()';

		$sql= $this->createUpdateSql($ar_fields,$this->table_field,$this->mainTable,$this->primary_keyname,$primary_id);
		//echo '<br> sqal : '.$sql.'<br>';
		$this->runSql($sql);



		//Handle meta tag record
		// Delete old meta tag record first 
		$sql ='DELETE FROM  `tbl_photo_entry_meta_category_sub` ' ;
		$sql.=' WHERE ';
		$sql.='`'.$this->primary_keyname. '`='.'\''.addslashes($primary_id).'\''.' ';
		//echo '<br>'.$sql; // Debug used				
		$void = $this->runSQLReturnID($sql);
		

		// <end>Delete old meta tag record first 
		
		
		//Handle meta tag record
		// Insert new meta tag record first 
		$arr_photo_meta_category = $this->photo_meta_category_viewall(); 		
		foreach ($arr_photo_meta_category as $row): 
			$arr_photo_category_sub = $this->photo_category_sub_list($row['meta_id']);
			foreach ($arr_photo_category_sub as $sub): 
				$sub_id = $sub['sub_id'];
				$input_id='sub_id_'.$sub['sub_id'];
				if ($general[$input_id]==1) {

						$create_user = $_SESSION['sUserID'];
						
						$sql = "INSERT INTO `tbl_photo_entry_meta_category_sub`(
										`photo_id`
										,`sub_id`
										,`status`
										,`create_user`
										,`modify_user`
										,`create_datetime`
										,`modify_datetime`
										) VALUES (
											'$primary_id'
											,'$sub_id'
											,'$status'
											,'$create_user'
											,'$create_user'
											,now()
											,now()
											)";

						//echo '<br>'.$sql.'<br>';		
						$void = $this->runSQLReturnID($sql);
			

				
				}
			endforeach; // foreach ($arr_photo_category_sub as $sub):  				
		endforeach;  // foreach ($arr_photo_meta_category as $row): 			
		
		//<end>Handle meta tag record
		
		
		
		return true;
	}	

	
	public function is_duplicate_field($field_name, $para)
	{
		$para = addslashes($para);
		$sql ="SELECT COUNT(*) AS RecordCount FROM tbl_photo_meta_category ";
		$sql .= "  WHERE ";
		$sql .= " (1) " ;
		$sql .=" AND $field_name = '$para'";
		
		//echo '<br>'.$sql; // Debug used		
		$arr_record = $this->runSQLAssoc($sql);	

		$is_find = false;
		if ($arr_record[0]['RecordCount'] >=1) $is_find = true;
		
		return $is_find;
	}	
			
			
	public function is_duplicate_field_myself($myself_id_para , $field_name, $field_para)
	{
		$myself_id_para = addslashes($myself_id_para);
		$field_name = addslashes($field_name);
		$field_para = addslashes($field_para);
		
		$sql ="SELECT COUNT(*) AS RecordCount FROM tbl_photo_meta_category ";
		$sql .= "  WHERE ";
		$sql .= " (1) " ;
		$sql .=" AND $field_name = '$field_para' AND ".$this->primary_keyname. "<>'$myself_id_para' ";

		//echo '<br>'.$sql; // Debug used		
		$arr_record = $this->runSQLAssoc($sql);	


		$is_find = false;
		if ($arr_record[0]['RecordCount'] >=1) $is_find = true;
		
		return $is_find;
	}	
		
	
    public function entry_meta_category_sub($primary_id)
	{

		$sql = "SELECT SUB.*  FROM tbl_photo_entry_meta_category_sub AS SUB  ";
		$sql .= " LEFT JOIN tbl_photo_entry AS C  ON SUB.photo_id = C.photo_id  ";
		$sql .= " WHERE SUB.".$this->primary_keyname. " = '$primary_id'";
		$sql .= " ORDER BY SUB.sub_id; ";

		//echo "<br>sql:".$sql."<br>";

		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record;
	}		

	
    public function photo_meta_category_viewall()
	{
		$sql ="SELECT * FROM tbl_photo_meta_category WHERE status = 1 ORDER BY meta_id;";
		//echo '<br>'.$sql; // Debug used		
		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record;
	}		
	
	
	
	
    public function photo_category_sub_list($meta_id)
	{
 		$sql ="SELECT * FROM tbl_photo_meta_category_sub WHERE ".'meta_id'. " = '$meta_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		
	
	
	
    public function photo_entry_meta_category_sub_list($photo_id)
	{
 
		$sql = 'SELECT  ';
		$sql .= 'PS.sub_id ';
		$sql .= ',CAT.name AS "category_name" ';
		$sql .= ',CATSUB.name AS "category_sub_name" ';
		$sql .='FROM tbl_photo_entry_meta_category_sub AS PS ';
		$sql .='LEFT OUTER JOIN tbl_photo_meta_category_sub AS CATSUB ';
		$sql .='ON PS.sub_id = CATSUB.sub_id ';
		$sql .='LEFT OUTER JOIN tbl_photo_meta_category AS CAT ';
		$sql .='ON CATSUB.meta_id = CAT.meta_id ';
		$sql .='WHERE PS.photo_id ="'.$photo_id.'" ';
		//$sql .=' ORDER BY CAT.meta_id, CATSUB.sub_id ASC ;';
		$sql .=' ORDER BY CATSUB.sub_id ASC ;';
			
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record;
	}		
	

    public function photo_entry_meta_category_distinct_list($photo_id)
	{

		$sql = 'SELECT  ';
		$sql .= 'distinct CAT.meta_id, CAT.name ';
		$sql .='FROM tbl_photo_entry_meta_category_sub AS PS ';
		$sql .='LEFT OUTER JOIN tbl_photo_meta_category_sub AS CATSUB ';
		$sql .='ON PS.sub_id = CATSUB.sub_id ';
		$sql .='LEFT OUTER JOIN tbl_photo_meta_category AS CAT ';
		$sql .='ON CATSUB.meta_id = CAT.meta_id ';
		$sql .='WHERE PS.photo_id ="'.$photo_id.'" ';
		$sql .=' ORDER BY CAT.meta_id ASC ;';
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record;
	}		

	
    public function photo_entry_meta_category_sub_distinct_list($photo_id, $meta_id)
	{

		$sql = 'SELECT  ';
		$sql .= 'distinct CATSUB.sub_id, CATSUB.name ';
		$sql .='FROM tbl_photo_entry_meta_category_sub AS PS ';
		$sql .='LEFT OUTER JOIN tbl_photo_meta_category_sub AS CATSUB ';
		$sql .='ON PS.sub_id = CATSUB.sub_id ';
		$sql .='LEFT OUTER JOIN tbl_photo_meta_category AS CAT ';
		$sql .='ON CATSUB.meta_id = CAT.meta_id ';
		$sql .=' WHERE  ';
		$sql .=' PS.photo_id ="'.$photo_id.'" ';
		$sql .=' AND  ';
		$sql .=' CATSUB.meta_id ="'.$meta_id.'" ';
		$sql .=' ORDER BY CATSUB.sub_id ASC ;';
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		
	
	
    public function photo_entry_document_list($photo_id)
	{

		$sql = 'SELECT  ';
		$sql .= 'DOC.*  ';
		$sql .='FROM tbl_photo_entry_document AS DOC ';
		$sql .='WHERE DOC.photo_id ="'.$photo_id.'" ';
		$sql .=' AND DOC.status = 1  ';
		$sql .=' ORDER BY DOC.doc_id ASC ;';
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		


    public function photo_entry_document_listall($photo_id)
	{

		$sql = 'SELECT  ';
		$sql .= 'DOC.*  ';
		$sql .='FROM tbl_photo_entry_document AS DOC ';
		$sql .='WHERE DOC.photo_id ="'.$photo_id.'" ';
		//$sql .=' AND DOC.status = 1  ';
		$sql .=' ORDER BY DOC.doc_id ASC ;';
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		

	
	public function large_jpeg_filename_update($photo_id, $new_file_name_saved, $file_size, $ext, $large_file_path, $sUserID)
	
	{
		
		$sql ='UPDATE  tbl_photo_entry SET ';
		$sql.='large_file_name='.'\''.addslashes($new_file_name_saved).'\''.',';
		$sql.='large_file_size='.'\''.addslashes($file_size).'\''.',';
		$sql.='large_file_extension='.'\''.addslashes($ext).'\''.',';
		$sql.='large_file_path='.'\''.addslashes($large_file_path).'\''.' ';
		$sql.='WHERE ';
		$sql.='photo_id='.'\''.addslashes($photo_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);

			
		return true;

	}
	
	
	public function small_jpeg_filename_update($photo_id, $new_file_name_saved, $file_size, $ext, $small_file_path, $sUserID)
	
	{
		

		$sql ='UPDATE  tbl_photo_entry SET ';
		$sql.='small_file_name='.'\''.addslashes($new_file_name_saved).'\''.',';
		$sql.='small_file_size='.'\''.addslashes($file_size).'\''.',';
		$sql.='small_file_extension='.'\''.addslashes($ext).'\''.',';
		$sql.='small_file_path='.'\''.addslashes($small_file_path).'\''.' ';
		$sql.='WHERE ';
		$sql.='photo_id='.'\''.addslashes($photo_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);
	
			
		return true;

	}
	
	public function photo_psd_filename_update($photo_id, $new_file_name_saved, $file_size, $ext, $psd_file_path, $sUserID)
	
	{
		
		$sql ='UPDATE  tbl_photo_entry SET ';
		$sql.='psd_file_name='.'\''.addslashes($new_file_name_saved).'\''.',';
		$sql.='psd_file_size='.'\''.addslashes($file_size).'\''.',';
		$sql.='psd_file_extension='.'\''.addslashes($ext).'\''.',';
		$sql.='psd_file_path='.'\''.addslashes($psd_file_path).'\''.' ';
		$sql.='WHERE ';
		$sql.='photo_id='.'\''.addslashes($photo_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);

			
		return true;

	}
	
	
	public function document_create($primary_id, $general)
	{
		
		$file_desc = addslashes($general['file_desc']);
		$status = 1;
		$create_user = $_SESSION['sUserID'];

		
		$sql = "INSERT INTO `tbl_photo_entry_document`(
						`photo_id`
						,`file_desc`
						,`status`
						,`create_user`
						,`modify_user`
						,`create_datetime`
						,`modify_datetime`
						) VALUES (
							'$primary_id'
							,'$file_desc'
							,'$status'
							,'$create_user'
							,'$create_user'
							,now()
							,now()
							)";
		
		//echo '<br>'.$sql; // Debug used				
		$last_insert_id = $this->runSQLReturnID($sql);
	
		return $last_insert_id;
	}	
	

	public function document_filename_update($doc_id, $new_file_name_saved, $file_size, $ext, $file_path, $original_file_name, $file_desc, $sUserID)
	
	{
		
		$sql ='UPDATE  tbl_photo_entry_document SET ';
		$sql.='file_desc='.'\''.addslashes($file_desc).'\''.',';
		$sql.='original_file_name='.'\''.addslashes($original_file_name).'\''.',';
		$sql.='file_name='.'\''.addslashes($new_file_name_saved).'\''.',';
		$sql.='file_size='.'\''.addslashes($file_size).'\''.',';
		$sql.='file_extension='.'\''.addslashes($ext).'\''.',';
		$sql.='file_path='.'\''.addslashes($file_path).'\''.' ';
		$sql.='WHERE ';
		$sql.='doc_id='.'\''.addslashes($doc_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);
		
			
		return true;

	}
	
	
    public function document_select($doc_id)
	{
 		$sql ="SELECT * FROM tbl_photo_entry_document WHERE ".'doc_id'. " = '$doc_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record[0];
	}		
	
	
	public function document_update($doc_id, $general)
	{
		$file_desc = trim(addslashes($general['file_desc']));
		$status = $general['status'];

		$modify_user = $_SESSION['sUserID'];

	
		$sql ='UPDATE  `tbl_photo_entry_document` SET ';
		$sql.='`file_desc`='.'\''.$file_desc.'\'';
		$sql.=',`status`='.'\''.$status.'\'';
		$sql.=',`modify_user`='.'\''.$modify_user.'\'';
		$sql.=',`modify_datetime`=NOW()'.' ';
		$sql.=' WHERE ';
		$sql.='`'.'doc_id'. '`='.'\''.addslashes($doc_id).'\''.' ';
		echo '<br>'.$sql; // Debug used				
		$void = $this->runSQLReturnID($sql);

		
		return $last_insert_id;
	}	

	
	
	
    public function close()
	{
		$this->dbh = null;
	}	
	
} //class
?>