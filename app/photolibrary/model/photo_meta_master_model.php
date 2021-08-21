<?php
class photo_meta_master_model extends dataManager
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
    	$this->mainTable='tbl_photo_meta_category';
    	$this->setTable($this->mainTable);
    	$this->setErrorMsg('Photo Library -> Maintenance -> Meta Tag Master -> SQL error:');
    	$this->table_field=$this->getTableField();

		$this->primary_keyname = 'meta_id';
		$this->primary_indexname = 'meta_id';
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
		
		
		if($json['general']['name']<>"") {
			if(!empty($sql_filter)) $sql_filter.=" AND ";
			$sql_filter .= " CAT.name LIKE '%".addslashes($json['general']['name'])."%'" ;
		}

		//echo "<br>sql_filter:".$sql_filter."<br>";
		
		
		$sql = "SELECT "."CAT.".$this->primary_keyname. " FROM tbl_photo_meta_category AS CAT ";
		$sql .= " WHERE ";		
		
		$sql .= " (1) " ;
		if(!empty($sql_filter)) $sql .= " AND  ".$sql_filter ;
		$sql .= " ORDER BY ". $this->primary_indexname . "  ASC ".  ";";
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

			
			$sql = "SELECT CAT.* FROM tbl_photo_meta_category AS CAT ";
		
			if(!empty($arr_primary_id)) $sql .= "WHERE "."CAT.".$this->primary_keyname. " in (".$arr_primary_id.")" ;
			$sql .= " ORDER BY CAT." .$this->primary_indexname. ' ASC ' ;
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
		//echo "<br>sql:".$sql."<br>";
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
 		$sql ="SELECT * FROM tbl_photo_meta_category WHERE ".$this->primary_keyname. " = '$primary_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	
		
		return $arr_record[0];
	}		
	
 
	public function create($general)
	{

		$name = $general['name'];
		$status = 1;
		$create_user = $_SESSION['sUserID'];		

		$ar_fields=array();
		$ar_fields['name'] = $name;
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
		
		$name = $general['name'];
		$status = $general['status'];
		$modify_user = $_SESSION['sUserID'];

		$ar_fields=array();
		$ar_fields['name'] = $name;
		$ar_fields['status'] = $status;
		$ar_fields['modify_user'] = $modify_user;
		$ar_fields['modify_datetime'] = 'now()';

		$sql= $this->createUpdateSql($ar_fields,$this->table_field,$this->mainTable,$this->primary_keyname,$primary_id);
		//echo '<br> sqal : '.$sql.'<br>';
		$this->runSql($sql);

		
		return $true;
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
		
	
    public function subcategory_list($primary_id)
	{

		$sql = "SELECT SUB.*  FROM tbl_photo_meta_category_sub AS SUB  ";
		$sql .= " LEFT JOIN tbl_photo_meta_category AS C  ON SUB.meta_id = C.meta_id  ";
		$sql .= " WHERE SUB.".$this->primary_keyname. " = '$primary_id'";
		$sql .= " ORDER BY SUB.sub_id; ";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		




	public function subcategory_create($primary_id, $general)
	{
		
		$name = $general['name'];
		$status = 1;
		$create_user = $_SESSION['sUserID'];		

		$ar_fields=array();
		$ar_fields['meta_id'] = $primary_id;
		$ar_fields['name'] = $name;
		$ar_fields['status'] = $status;
		$ar_fields['create_user'] = $create_user;
		$ar_fields['modify_user'] = $create_user;
		$ar_fields['create_datetime'] = 'now()';
		$ar_fields['modify_datetime'] = 'now()';

		
		$this->mainTable='tbl_photo_meta_category_sub';
    	$this->setTable($this->mainTable);
       	$this->table_field=$this->getTableField();

		$sql= $this->createInsertSql($ar_fields,$this->table_field,$this->mainTable);
		echo '<br> sql : '.$sql.'<br>';
		$last_insert_id = $this->runSQLReturnID($sql);	

		return true;
	}	


	
    public function subcategory_select($sub_id)
	{
 		$sql ="SELECT * FROM tbl_photo_meta_category_sub WHERE ".'sub_id'. " = '$sub_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record[0];
	}		
	
	
	public function subcategory_update($sub_id, $general)
	{

		$name = $general['name'];
		$status = $general['status'];
		$modify_user = $_SESSION['sUserID'];

		$ar_fields=array();
		$ar_fields['name'] = $name;
		$ar_fields['status'] = $status;
		$ar_fields['modify_user'] = $modify_user;
		$ar_fields['modify_datetime'] = 'now()';

		$this->mainTable='tbl_photo_meta_category_sub';
    	$this->setTable($this->mainTable);
       	$this->table_field=$this->getTableField();

		$sql= $this->createUpdateSql($ar_fields,$this->table_field,$this->mainTable,'sub_id',$sub_id);
		//echo '<br> sqal : '.$sql.'<br>';
		$this->runSql($sql);

		
		return $true;
	}	

	
	
    public function close()
	{
		$this->dbh = null;
	}	
	
} //class
?>