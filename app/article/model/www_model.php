<?php
class www_model extends dataManager
{
	private $dbh;
	
	private $table_field;  // variable for dataManager
	private $errorMsg;   // variable for dataManager
	private $mainTable;   // variable for dataManager

	public function __construct()
    {
		parent::__construct();
    	$this->mainTable='tbl_article_season_master';
    	$this->setTable($this->mainTable);
    	$this->setErrorMsg('Article -> Maintenance -> WWW -> SQL error:');
    	$this->table_field=$this->getTableField();
	
			
		try {
			$this->dbh = new PDO(DB_CONNECTION_STRING,DB_USERNAME,DB_PASSWORD);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->dbh->query("set names utf8");
			} catch (PDOException $e) {
				print 'Error!: ' . $e->getMessage();
				die();
				}
	}
	

     public function season_viewall($preview)
	{
		
 		$sql ="SELECT * FROM 	tbl_article_season_master  ";
		$sql .=" WHERE ";
		$sql .=" status = 1 ";
		if($preview =='no') {
			$sql .=" AND publish_is = 1 ";
		}
		$sql .=" ORDER BY season_id DESC; ";
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record;
	}		
	

     public function season_last_three_view($season_id, $preview)
	{
		
 		$sql ="SELECT * FROM 	tbl_article_season_master  ";
		$sql .=" WHERE ";
		$sql .=" status = 1 ";
		if($preview == 'no') {
			$sql .=" AND publish_is = 1 ";
		}
		$sql .=" AND season_id  <= ". $season_id ;
		$sql .=" ORDER BY season_id DESC LIMIT 3; ";
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		
	
	
	
    public function season_last_select($preview)
	{
		
 		$sql ="SELECT * FROM 	tbl_article_season_master  ";
		$sql .=" WHERE ";
		$sql .=" status = 1 ";
		if($preview =='no') {
			$sql .=" AND publish_is = 1 ";
		}
		$sql .=" ORDER BY season_id DESC LIMIT 1; ";
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record[0];
	}		
	
	
    public function season_select($season_id)
	{
 		$sql ="SELECT * FROM tbl_article_season_master WHERE season_id = '$season_id'";
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record[0];
	}		
	
	
     public function category_view($season_id, $preview)
	{
		
		$sql =" SELECT 	
						DISTINCT
						CM.cate_id, 
						CM.name AS category_name
						FROM
		 			tbl_article_entry AS E
					LEFT JOIN tbl_article_category_master AS CM ON E.cate_id = CM.cate_id
					WHERE ";
		$sql .=" E.season_id = ".$season_id;
		$sql .=" AND E.status = 1 ";
		if($preview == 'no') {
			$sql .=" AND E.publish_is = 1 ";
		}		
		$sql .=" ORDER BY CM.sorting;";
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		
	
	
     public function category_article_view($season_id, $cate_id, $preview)
	{
	
		$sql =" SELECT 	
						DISTINCT
						CM.cate_id, 
						CM.name AS category_name,
						E.*
						FROM
		 			tbl_article_entry AS E
					LEFT JOIN tbl_article_category_master AS CM ON E.cate_id = CM.cate_id
					WHERE ";
		$sql .=" E.season_id = ".$season_id;
		$sql .=" AND E.status = 1 ";
		$sql .=" AND E.cate_id = ".$cate_id;
		if($preview == 'no') {
			$sql .=" AND E.publish_is = 1 ";
		}		
		$sql .=" ORDER BY CM.sorting, E.article_date DESC ;";
		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record;
	}		
	
	
    public function media_main_select($article_id)
	{

		$sql = "SELECT M.*, TY.name as 'TY_name'   FROM tbl_article_entry_media AS M  ";
		$sql .= " LEFT JOIN tbl_article_entry AS C  ON M.article_id = C.article_id  ";
		$sql .= " LEFT JOIN tbl_article_media_type_master AS TY  ON M.media_type_id = TY.media_type_id  ";
		$sql .= " WHERE M.article_id = '$article_id' ";
		$sql .= " AND M.main_is = 1 ";
		$sql .= " ORDER BY M.media_id LIMIT 1 ";

		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record[0];
	}		

	
	
    public function media_article_select($article_id)
	{
		
		$sql =" SELECT 	
						CM.cate_id, 
						CM.name AS category_name,
						E.*
						FROM
		 			tbl_article_entry AS E
					LEFT JOIN tbl_article_category_master AS CM ON E.cate_id = CM.cate_id
					WHERE ";
		$sql .=" E.status = 1 ";
		$sql .=" AND E.article_id = ".$article_id;

		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	
				
		
		return $arr_record[0];
	}		
	


    public function article_media_viewall($article_id, $main_inner_is)
	{

		$sql = "SELECT M.*, TY.name as 'TY_name'   FROM tbl_article_entry_media AS M  ";
		$sql .= " LEFT JOIN tbl_article_entry AS C  ON M.article_id = C.article_id  ";
		$sql .= " LEFT JOIN tbl_article_media_type_master AS TY  ON M.media_type_id = TY.media_type_id  ";
		$sql .= " WHERE M.article_id = '$article_id' ";
		$sql .= " AND M.main_inner_is = ". $main_inner_is ;
		$sql .= " AND M.status = 1 ";
		//$sql .= " ORDER BY M.main_is DESC , M.media_id ASC ";
		$sql .= " ORDER BY M.display_priority DESC , M.media_id ASC ";

		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		return $arr_record;
	}		

	
     public function category_season_headline_select($season_id, $cate_id)
	{
		
		
		$sql = "SELECT H.*, M.name as 'category_name'   FROM tbl_article_season_master_headline AS H  ";
		$sql .= " LEFT JOIN tbl_article_category_master AS M  ON H.cate_id = M.cate_id  ";
		$sql .= " WHERE ";
		$sql .=" H.season_id = ".$season_id;
		$sql .=" AND H.status = 1 ";
		$sql .=" AND H.cate_id = ".$cate_id;
		$sql .= " ORDER BY H.season_headline_id; ";


		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	


		return $arr_record;
	}		
	
	
	
    public function close()
	{
		$this->dbh = null;
	}	
	
} //class
?>