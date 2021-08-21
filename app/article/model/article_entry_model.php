<?php
class article_entry_model extends dataManager
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
    	$this->mainTable='tbl_article_entry';
    	$this->setTable($this->mainTable);
    	$this->setErrorMsg('Article -> Maintenance -> Article Entry -> SQL error:');
    	$this->table_field=$this->getTableField();

		$this->primary_keyname = 'article_id';
		$this->primary_indexname = 'article_id';
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

		if($json['general']['season_id']<>"") {
			if(!empty($sql_filter)) $sql_filter.=" AND ";
			$sql_filter .= " ART.season_id = '".addslashes($json['general']['season_id'])."'" ;
		}
		
		if($json['general']['cate_id']<>"") {
			if(!empty($sql_filter)) $sql_filter.=" AND ";
			$sql_filter .= " ART.cate_id = '".addslashes($json['general']['cate_id'])."'" ;
		}
		
		
		if($json['general']['headline']<>"") {
			if(!empty($sql_filter)) $sql_filter.=" AND ";
			$sql_filter .= " ART.headline LIKE '%".addslashes($json['general']['headline'])."%'" ;
		}

		//echo "<br>sql_filter:".$sql_filter."<br>";
		
		
		$sql = "SELECT "."ART.".$this->primary_keyname. " FROM tbl_article_entry AS ART ";
		$sql.=" RIGHT JOIN  tbl_article_season_master AS S ON ART.season_id = S.season_id ";
		$sql.=" RIGHT JOIN  tbl_article_category_master AS C ON ART.cate_id = C.cate_id ";
		$sql.= " WHERE ";		
		$sql.= " (1) " ;
		if(!empty($sql_filter)) $sql .= " AND  ".$sql_filter ;
		$sql .= " ORDER BY ". $this->primary_indexname . "  DESC ".  ";";
		//echo "<br>sql:".$sql."<br>";
		$arr_primary_id =array();
		$rows = $this->runSQLAssoc($sql);	
		foreach ($rows as $row): 
			$arr_primary_id[] = "'". addslashes($row[$this->primary_keyname]) ."'";
		endforeach; 
		
		
		/*
		$arr_primary_id =array();
		try {
			$rs = $this->dbh->query($sql);
			while($row = $rs->fetch(PDO::FETCH_ASSOC)){
				$arr_primary_id[] = "'". addslashes($row[$this->primary_keyname]) ."'";
				}
			} catch (PDOException $e){
				print 'Error!: ' . $e->getMessage() . '<br>Script:'.$sql.'<br>';
				die();
				}		
		
		*/

		$array_count = count($arr_primary_id);

		if ($array_count > 0){	  
			$result_id = implode(",", $arr_primary_id);
		}
		
		$lot_id = strtotime(date("Y-m-d H:i:s")).rand(0, 10);;

		//$this->dbh->beginTransaction();

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

		/*
		try {
			$rows = $this->dbh->query($sql);
			} catch (PDOException $e) {
				print 'Error!: ' . $e->getMessage() . '<br>Script:'.$sql.'<br>';
				die();
				}
		  
		$this->dbh->commit();
		*/

		return $lot_id;
	}

 
  public function retreive_content($lot_id,$page)
	{

		$sql = "SELECT * FROM tbl_sys_paging_control WHERE lot_id = '".$lot_id."' AND create_user =	'".$_SESSION['sUserID']."';";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		/*
		$arr_record = array();	
		try {
				$rs = $this->dbh->query($sql);
				while($row = $rs->fetch(PDO::FETCH_ASSOC)){
					$arr_record[] = $row;
					}
				} catch (PDOException $e){
					print 'Error!: ' . $e->getMessage() . '<br>Script:'.$sql.'<br>';
					die();
					}	
		*/				

		$arr =  $arr_record[0];
		$arr_primary_id = $arr['result_id']; 
		
		
		if ($arr_primary_id != '')		
		{

			
			$sql =" SELECT ART.*  ";
			$sql.=", S.name AS season_name ";
			$sql.=", C.name AS category_name ";
			$sql.=" FROM tbl_article_entry AS ART ";
			$sql.=" RIGHT JOIN  tbl_article_season_master AS S ON ART.season_id = S.season_id ";
			$sql.=" RIGHT JOIN  tbl_article_category_master AS C ON ART.cate_id = C.cate_id ";
		
			if(!empty($arr_primary_id)) $sql .= "WHERE "."ART.".$this->primary_keyname. " in (".$arr_primary_id.")" ;
			$sql .= " ORDER BY ART." .$this->primary_indexname. ' DESC ' ;
			$sql .= " LIMIT ". SYSTEM_PAGE_ROW_LIMIT . " OFFSET  ".($page-1)*SYSTEM_PAGE_ROW_LIMIT ;
			//echo "<br>sql:".$sql."<br>";
			$arr_record = $this->runSQLAssoc($sql);	

			/*
			$arr_record = array();	
			try {
					$rs = $this->dbh->query($sql);
					while($row = $rs->fetch(PDO::FETCH_ASSOC)){
						$arr_record[] = $row;
						}
					} catch (PDOException $e){
						print 'Error!: ' . $e->getMessage() . '<br>Script:'.$sql.'<br>';
						die();
						}	
			*/

			return $arr_record;
		}
		
		return $arr_record;
	}

  public function paging_config($lot_id)
	{
		$sql = "SELECT * FROM tbl_sys_paging_control WHERE lot_id = '".$lot_id."' AND create_user =	'".$_SESSION['sUserID']."';";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	
	
		/*
		$arr_record = array();	
		try {
			$rows = $this->dbh->query($sql);
			while($row = $rows->fetch(PDO::FETCH_ASSOC)){
				$arr_record[] = $row;
					}
				} catch (PDOException $e){
					print 'Error!: ' . $e->getMessage() . '<br>Script:'.$sql.'<br>';
					die();
					}
		*/					
		  
		$result_id = $arr_record[0]['result_id'];

		//$this->dbh->beginTransaction();

		$sql ="UPDATE `tbl_sys_paging_control` SET modify_datetime =now()	WHERE lot_id ='$lot_id'";
		$void = $this->runSQLReturnID($sql);

		/*

		try {
			$rows = $this->dbh->query($sql);
			$last_insert_id = $this->dbh->lastInsertId(); 
			} catch (PDOException $e) {		
				print 'Error!: ' . $e->getMessage() . '<br>Script:'.$sql.'<br>';
				die();
				}		
		
		$this->dbh->commit();	
		*/

		return $result_id;
	}	

    public function searchphrase($lot_id)
	{
		$sql = "SELECT searchphrase FROM tbl_sys_paging_control WHERE lot_id = '".$lot_id."' AND create_user =	'".$_SESSION['sUserID']."';";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		/*
		$arr_record = array();	
		try {
			$rows = $this->dbh->query($sql);
			while($row = $rows->fetch(PDO::FETCH_ASSOC)){
				$arr_record[] = $row;
				}
			} catch (PDOException $e) {
				print 'Error!: ' . $e->getMessage() . '<br>Script:'.$sql.'<br>';
				die();
				}	
		*/			

		$searchphrase = $arr_record[0]['searchphrase'];
		return $searchphrase;
				
	}		

    public function select($primary_id)
	{
 		$sql ="SELECT * FROM tbl_article_entry WHERE ".$this->primary_keyname. " = '$primary_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		/*
		$arr_record = array();
		try {
			$rows = $this->dbh->query($sql);
			while($row = $rows->fetch(PDO::FETCH_ASSOC)){
			  $arr_record[] = $row;
			 }
			} catch (PDOException $e) {
				print 'Error!: ' . $e->getMessage() . '<br>Script:'.$sql.'<br>';
				die();
				}	
		*/			
		
		return $arr_record[0];
	}		
	
 
	public function create($general)
	{

		$season_id = $general['season_id'];
		$cate_id = $general['cate_id'];
		$article_date = toYMD($general['article_date']);
		$headline = $general['headline'];
		$content = $general['content'];
		$publish_is = 0;
		$status = 1;
		$create_user = $_SESSION['sUserID'];		

		$ar_fields=array();
		$ar_fields['season_id'] = $season_id;
		$ar_fields['cate_id'] = $cate_id;
		$ar_fields['article_date'] = $article_date;
		$ar_fields['headline'] = $headline;
		$ar_fields['content'] = $content;
		$ar_fields['publish_is'] = $publish_is;
		$ar_fields['status'] = $status;
		$ar_fields['create_user'] = $create_user;
		$ar_fields['modify_user'] = $create_user;
		$ar_fields['create_datetime'] = 'now()';
		$ar_fields['modify_datetime'] = 'now()';

		$sql= $this->createInsertSql($ar_fields,$this->table_field,$this->mainTable);
		//echo '<br> sql : '.$sql.'<br>';
		$last_insert_id = $this->runSQLReturnID($sql);

			
		return $last_insert_id;
	}
	
	
	public function update($primary_id, $general)
	{
		$season_id = $general['season_id'];
		$cate_id = $general['cate_id'];
		$article_date = toYMD($general['article_date']);
		$headline = $general['headline'];
		$content = $general['content'];
		$publish_is = $general['publish_is'];
		$status = $general['status'];
		$modify_user = $_SESSION['sUserID'];

		$ar_fields=array();
		$ar_fields['season_id'] = $season_id;
		$ar_fields['cate_id'] = $cate_id;
		$ar_fields['article_date'] = $article_date;
		$ar_fields['headline'] = $headline;
		$ar_fields['content'] = $content;
		$ar_fields['publish_is'] = $publish_is;
		$ar_fields['status'] = $status;
		$ar_fields['modify_user'] = $modify_user;
		$ar_fields['modify_datetime'] = 'now()';

		$sql= $this->createUpdateSql($ar_fields,$this->table_field,$this->mainTable,$this->primary_keyname,$primary_id);
		//echo '<br> sqal : '.$sql.'<br>';
		$this->runSql($sql);


		return $last_insert_id;
	}	

	
	public function is_duplicate_field($field_name, $para)
	{
		$para = addslashes($para);
		$sql ="SELECT COUNT(*) AS RecordCount FROM tbl_article_entry ";
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
		
		$sql ="SELECT COUNT(*) AS RecordCount FROM tbl_article_entry ";
		$sql .= "  WHERE ";
		$sql .= " (1) " ;
		$sql .=" AND $field_name = '$field_para' AND ".$this->primary_keyname. "<>'$myself_id_para' ";

		//echo '<br>'.$sql; // Debug used		
		$arr_record = $this->runSQLAssoc($sql);	

		$is_find = false;
		if ($arr_record[0]['RecordCount'] >=1) $is_find = true;
		
		return $is_find;
	}	
		
	


	
    public function article_season_master_viewall()
	{
		$sql ="SELECT * FROM tbl_article_season_master ORDER BY season_id DESC;";
		//echo '<br>'.$sql; // Debug used		
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		

    public function article_category_master_viewall()
	{
		$sql ="SELECT * FROM tbl_article_category_master ORDER BY cate_id ;";
		//echo '<br>'.$sql; // Debug used		
		$arr_record = $this->runSQLAssoc($sql);	
		
		return $arr_record;
	}		
	
	
	
    public function photos_list($primary_id)
	{

		$sql = "SELECT PHOTOS.*  FROM tbl_article_entry_photo AS PHOTOS  ";
		$sql .= " LEFT JOIN tbl_article_entry AS C  ON PHOTOS.article_id = C.article_id  ";
		$sql .= " WHERE PHOTOS.".$this->primary_keyname. " = '$primary_id'";
		$sql .= " ORDER BY PHOTOS.photo_id; ";
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		
		return $arr_record;
	}		

	
	public function photos_create($primary_id, $general)
	{
		
		$caption = addslashes($general['caption']);
		$video_url = addslashes($general['video_url']);
		$other_url = addslashes($general['other_url']);
		$main_photo_is = addslashes($general['main_photo_is']);
		$status = 1;
		$create_user = $_SESSION['sUserID'];

		
		
		$sql = "INSERT INTO `tbl_article_entry_photo`(
						`article_id`
						,`caption`
						,`video_url`
						,`other_url`
						,`main_photo_is`
						,`status`
						,`create_user`
						,`modify_user`
						,`create_datetime`
						,`modify_datetime`
						) VALUES (
							'$primary_id'
							,'$caption'
							,'$video_url'
							,'$other_url'
							,'$main_photo_is'
							,'$status'
							,'$create_user'
							,'$create_user'
							,now()
							,now()
							)";
		
		//echo '<br>'.$sql; // Debug used				
		$void = $this->runSQLReturnID($sql);

		
		
		return $last_insert_id;
	}	

	
	public function small_jpeg_filename_update($photo_id, $new_file_name_saved, $file_size, $ext, $small_file_path, $sUserID)
	
	{
		

		$sql ='UPDATE  tbl_article_entry_photo SET ';
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
	
	
	
	public function large_jpeg_filename_update($photo_id, $new_file_name_saved, $file_size, $ext, $large_file_path, $sUserID)
	
	{
		
		
		$sql ='UPDATE  tbl_article_entry_photo SET ';
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
	
	
	
    public function photos_select($photo_id)
	{
 		$sql ="SELECT * FROM tbl_article_entry_photo WHERE ".'photo_id'. " = '$photo_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	
				
		return $arr_record[0];
	}		
	
	
	
	public function photos_update($photo_id, $general)
	{
		$caption = trim(addslashes($general['caption']));
		$video_url = addslashes($general['video_url']);
		$other_url = addslashes($general['other_url']);
		$main_photo_is = addslashes($general['main_photo_is']);
		$status = $general['status'];

		$modify_user = $_SESSION['sUserID'];

	
		$sql ='UPDATE  `tbl_article_entry_photo` SET ';
		$sql.='`caption`='.'\''.$caption.'\'';
		$sql.=',`video_url`='.'\''.$video_url.'\'';
		$sql.=',`other_url`='.'\''.$other_url.'\'';
		$sql.=',`main_photo_is`='.'\''.$main_photo_is.'\'';
		$sql.=',`status`='.'\''.$status.'\'';
		$sql.=',`modify_user`='.'\''.$modify_user.'\'';
		$sql.=',`modify_datetime`=NOW()'.' ';
		$sql.=' WHERE ';
		$sql.='`'.'photo_id'. '`='.'\''.addslashes($photo_id).'\''.' ';
		//echo '<br>'.$sql; // Debug used				
		$void = $this->runSQLReturnID($sql);

		
		return $last_insert_id;
	}	
	
	
    public function article_entry_document_listall($article_id)
	{

		$sql = 'SELECT  ';
		$sql .= 'DOC.*  ';
		$sql .='FROM tbl_article_entry_document AS DOC ';
		$sql .='WHERE DOC.article_id ="'.$article_id.'" ';
		//$sql .=' AND DOC.status = 1  ';
		$sql .=' ORDER BY DOC.doc_id ASC ;';
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

	
		return $arr_record;
	}		


	public function document_create($primary_id, $general)
	{
		
		$file_desc = addslashes($general['file_desc']);
		$status = 1;
		$create_user = $_SESSION['sUserID'];

		$this->dbh->beginTransaction();
		
		
		$sql = "INSERT INTO `tbl_article_entry_document`(
						`article_id`
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
	
		$sql ='UPDATE  tbl_article_entry_document SET ';
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
 		$sql ="SELECT * FROM tbl_article_entry_document WHERE ".'doc_id'. " = '$doc_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	
				
		return $arr_record[0];
	}		
	
	
	
	public function document_update($doc_id, $general)
	{
		$file_desc = trim(addslashes($general['file_desc']));
		$status = $general['status'];

		$modify_user = $_SESSION['sUserID'];
	
		$sql ='UPDATE  `tbl_article_entry_document` SET ';
		$sql.='`file_desc`='.'\''.$file_desc.'\'';
		$sql.=',`status`='.'\''.$status.'\'';
		$sql.=',`modify_user`='.'\''.$modify_user.'\'';
		$sql.=',`modify_datetime`=NOW()'.' ';
		$sql.=' WHERE ';
		$sql.='`'.'doc_id'. '`='.'\''.addslashes($doc_id).'\''.' ';
		//echo '<br>'.$sql; // Debug used				
		$void = $this->runSQLReturnID($sql);

	
		return true;
	}	

	
	
    public function media_list($primary_id)
	{

		$sql = "SELECT M.*, TY.name as 'TY_name'   FROM tbl_article_entry_media AS M  ";
		$sql .= " LEFT JOIN tbl_article_entry AS C  ON M.article_id = C.article_id  ";
		$sql .= " LEFT JOIN tbl_article_media_type_master AS TY  ON M.media_type_id = TY.media_type_id  ";
		$sql .= " WHERE M.".$this->primary_keyname. " = '$primary_id'";
		$sql .= " ORDER BY M.media_id; ";

		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		
		return $arr_record;
	}		


    public function article_media_type_master_viewall()
	{
		$sql ="SELECT * FROM tbl_article_media_type_master ORDER BY media_type_id ASC;";
		//echo '<br>'.$sql; // Debug used		
		$arr_record = $this->runSQLAssoc($sql);	
		
		return $arr_record;
	}		
	
	
	public function media_create($primary_id, $general)
	{
		
		$media_type_id = addslashes($general['media_type_id']);
		$caption = addslashes($general['caption']);
		$main_is = addslashes($general['main_is']);
		$main_inner_is = addslashes($general['main_inner_is']);
		$display_priority = addslashes($general['display_priority']);		
		$status = 1;
		$create_user = $_SESSION['sUserID'];

		
		
		$sql = "INSERT INTO `tbl_article_entry_media`(
						`article_id`
						,`media_type_id`
						,`main_is`
						,`main_inner_is`
						,`display_priority`
						,`caption`
						,`status`
						,`create_user`
						,`modify_user`
						,`create_datetime`
						,`modify_datetime`
						) VALUES (
							'$primary_id'
							,'$media_type_id'
							,'$main_is'
							,'$main_inner_is'
							,'$display_priority'
							,'$caption'
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

	
	public function media_reference_url_update($media_id, $general)
	{

		$reference_url = addslashes($general['reference_url']);
		
		if(!isset($general['status'])) $status = 1;
		if(isset($general['status']))  $status = $general['status'];
		
		$modify_user = $_SESSION['sUserID'];
	
		$sql ='UPDATE  tbl_article_entry_media SET ';
		$sql.='reference_url='.'\''.addslashes($reference_url).'\''.' ';
		$sql.=','.'status='.'\''.addslashes($status).'\''.' ';
		$sql.='WHERE ';
		$sql.='media_id='.'\''.addslashes($media_id).'\''.' ';
		$sql.=' AND  ';
		$sql.='modify_user='.'\''.addslashes($modify_user).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);

			
		return true;

	}
	
	
	
    public function media_select($media_id)
	{
 		$sql ="SELECT * FROM tbl_article_entry_media WHERE ".'media_id'. " = '$media_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record[0];
	}		
	
	
	public function media_update($media_id, $general)
	{
		$caption = trim(addslashes($general['caption']));
		$main_is = addslashes($general['main_is']);
		$main_inner_is = addslashes($general['main_inner_is']);
		$display_priority = addslashes($general['display_priority']);
		$status = $general['status'];
		$modify_user = $_SESSION['sUserID'];

	
		$sql ='UPDATE  `tbl_article_entry_media` SET ';
		$sql.='`caption`='.'\''.$caption.'\'';
		$sql.=',`main_is`='.'\''.$main_is.'\'';
		$sql.=',`main_inner_is`='.'\''.$main_inner_is.'\'';
		$sql.=',`display_priority`='.'\''.$display_priority.'\'';
		$sql.=',`status`='.'\''.$status.'\'';
		$sql.=',`modify_user`='.'\''.$modify_user.'\'';
		$sql.=',`modify_datetime`=NOW()'.' ';
		$sql.=' WHERE ';
		$sql.='`'.'media_id'. '`='.'\''.addslashes($media_id).'\''.' ';
		//echo '<br>'.$sql; // Debug used				
	
		$void = $this->runSQLReturnID($sql);


		return true;
	}	
	
	
	public function media_document_upload_update($media_id, $general, $new_file_name_saved, $file_size, $ext, $file_path, $original_file_name)
	{

		$caption = trim(addslashes($general['caption']));
		$main_is = addslashes($general['main_is']);
		if(!isset($general['status'])) $status = 1;
		if(isset($general['status']))  $status = $general['status'];
		$modify_user = $_SESSION['sUserID'];

	
		$sql ='UPDATE  tbl_article_entry_media SET ';
		$sql.='caption='.'\''.addslashes($caption).'\''.',';
		$sql.='main_is='.'\''.addslashes($main_is).'\''.',';
		$sql.='upload_original_file_name='.'\''.addslashes($original_file_name).'\''.',';
		$sql.='upload_file_name='.'\''.addslashes($new_file_name_saved).'\''.',';
		$sql.='upload_file_size='.'\''.addslashes($file_size).'\''.',';
		$sql.='upload_file_extension='.'\''.addslashes($ext).'\''.',';
		$sql.='upload_file_path='.'\''.addslashes($file_path).'\''.',';
		$sql.='status='.'\''.addslashes($status).'\''.',';
		$sql.='modify_user='.'\''.addslashes($modify_user).'\''.',';
		$sql.='modify_datetime=NOW()'.' ';
		$sql.='WHERE ';
		$sql.='media_id='.'\''.addslashes($media_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);
			
		return true;

	}
	
	
	public function media_photo_small_update($media_id, $general, $new_file_name_saved, $file_size, $ext, $small_file_path)
	{
		$caption = trim(addslashes($general['caption']));
		$main_is = addslashes($general['main_is']);
		if(!isset($general['status'])) $status = 1;
		if(isset($general['status']))  $status = $general['status'];
		$modify_user = $_SESSION['sUserID'];
		

		$sql ='UPDATE  tbl_article_entry_media SET ';
		$sql.='photo_small_file_name='.'\''.addslashes($new_file_name_saved).'\''.',';
		$sql.='photo_small_file_size='.'\''.addslashes($file_size).'\''.',';
		$sql.='photo_small_file_extension='.'\''.addslashes($ext).'\''.',';
		$sql.='photo_small_file_path='.'\''.addslashes($small_file_path).'\''.',';
		$sql.='status='.'\''.addslashes($status).'\''.',';
		$sql.='modify_user='.'\''.addslashes($modify_user).'\''.',';
		$sql.='modify_datetime=NOW()'.' ';		
		$sql.='WHERE ';
		$sql.='media_id='.'\''.addslashes($media_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);
			
		return true;

	}
	
	
	public function media_photo_large_update($media_id, $general, $new_file_name_saved, $file_size, $ext, $large_file_path)
	{
		$caption = trim(addslashes($general['caption']));
		$main_is = addslashes($general['main_is']);
		if(!isset($general['status'])) $status = 1;
		if(isset($general['status']))  $status = $general['status'];
		$modify_user = $_SESSION['sUserID'];
		
	
		$sql ='UPDATE  tbl_article_entry_media SET ';
		$sql.='photo_large_file_name='.'\''.addslashes($new_file_name_saved).'\''.',';
		$sql.='photo_large_file_size='.'\''.addslashes($file_size).'\''.',';
		$sql.='photo_large_file_extension='.'\''.addslashes($ext).'\''.',';
		$sql.='photo_large_file_path='.'\''.addslashes($large_file_path).'\''.',';
		$sql.='status='.'\''.addslashes($status).'\''.',';
		$sql.='modify_user='.'\''.addslashes($modify_user).'\''.',';
		$sql.='modify_datetime=NOW()'.' ';				
		$sql.='WHERE ';
		$sql.='media_id='.'\''.addslashes($media_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);
		
		
		return true;

	}
	
	
	public function media_youtube_url_update($media_id, $general)
	{
		$youtube_video_url = trim(addslashes($general['youtube_video_url']));
		$modify_user = $_SESSION['sUserID'];
		
	
		$sql ='UPDATE  tbl_article_entry_media SET ';
		$sql.='youtube_video_url='.'\''.addslashes($youtube_video_url).'\''.',';
		$sql.='modify_user='.'\''.addslashes($modify_user).'\''.',';
		$sql.='modify_datetime=NOW()'.' ';		
		$sql.='WHERE ';
		$sql.='media_id='.'\''.addslashes($media_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);
	
		
		return true;

	}
	
	
	public function media_self_hosted_video_update($media_id, $general, $new_file_name_saved, $file_size, $ext, $file_path, $original_file_name)
	{

		$caption = trim(addslashes($general['caption']));
		$main_is = addslashes($general['main_is']);
		if(!isset($general['status'])) $status = 1;
		if(isset($general['status']))  $status = $general['status'];
		$modify_user = $_SESSION['sUserID'];

		
		$sql ='UPDATE  tbl_article_entry_media SET ';
		$sql.='caption='.'\''.addslashes($caption).'\''.',';
		$sql.='main_is='.'\''.addslashes($main_is).'\''.',';
		$sql.='video_original_file_name='.'\''.addslashes($original_file_name).'\''.',';
		$sql.='video_file_name='.'\''.addslashes($new_file_name_saved).'\''.',';
		$sql.='video_file_size='.'\''.addslashes($file_size).'\''.',';
		$sql.='video_file_extension='.'\''.addslashes($ext).'\''.',';
		$sql.='video_file_path='.'\''.addslashes($file_path).'\''.',';
		$sql.='status='.'\''.addslashes($status).'\''.',';
		$sql.='modify_user='.'\''.addslashes($modify_user).'\''.',';
		$sql.='modify_datetime=NOW()'.' ';
		$sql.='WHERE ';
		$sql.='media_id='.'\''.addslashes($media_id).'\''.' ';

		//echo '<br>'.$sql.'<br>';		
		$void = $this->runSQLReturnID($sql);
	
				
		return true;

	}
	
	
	
    public function close()
	{
		$this->dbh = null;
	}	
	
} //class
?>