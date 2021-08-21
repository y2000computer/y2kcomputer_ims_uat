<?php
class api_article_model extends dataManager
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
    	$this->setErrorMsg('Article -> Maintenance -> API -> SQL error:');
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
	

    public function article_viewall()
	{
		
		$sql =" SELECT 	E.article_id 
						,E.article_date
						,E.headline
						,E.content 
						FROM
		 			tbl_article_entry AS E
					LEFT JOIN tbl_article_category_master AS CM ON E.cate_id = CM.cate_id
					LEFT JOIN tbl_article_season_master AS SEA ON E.season_id = SEA.season_id
					WHERE ";
		$sql .=" E.status = 1 ";
		$sql .=" AND E.publish_is = 1 ";
		$sql .=" ORDER BY E.article_date DESC; ";
		
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
		//$sql .= " AND M.main_is = 1 ";
		$sql .= " ORDER BY M.media_id LIMIT 1 ";

		
		//echo "<br>sql:".$sql."<br>";
		$arr_record = $this->runSQLAssoc($sql);	

		
		return $arr_record[0];
	}		
	
    public function close()
	{
		$this->dbh = null;
	}	
	
} //class
?>