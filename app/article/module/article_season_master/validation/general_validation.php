<?php
class general_validation
{
	private $action;   
	private $dataModel; 
	private $problemMsg; 
	
	public function __construct($action,$dataModel)
    {
		$this->action =$action;
		$this->dataModel = $dataModel;
		$this->problemMsg ='';
	
	}

    public function ValidateFormActionCreate($form)
	{
		$icheck = true;
		//do checking for action create


		if (!valid::isDate($form['date_from'])) {
			$this->problemMsg .= '[Date From] format is not Vaild!<br>';
			$icheck = false;
		} 

		if (!valid::isDate($form['date_to'])) {
			$this->problemMsg .= '[Date To] format is not Vaild!<br>';
			$icheck = false;
		} 
		
		
		if($this->dataModel->is_duplicate_field('name', $form['name'])){
				$this->problemMsg .= '[Name] cannot duplicate !<br>';
				$icheck = false;
		}


		
		return $icheck;
	}
	

    public function ValidateFormActionUpdate($id, $form)
	{
		$icheck = true;
		//do checking for action update

		if (!valid::isDate($form['date_from'])) {
			$this->problemMsg .= '[Date From] format is not Vaild!<br>';
			$icheck = false;
		} 

		if (!valid::isDate($form['date_to'])) {
			$this->problemMsg .= '[Date To] format is not Vaild!<br>';
			$icheck = false;
		} 
		
		
		

		if($this->dataModel->is_duplicate_field_myself($id, 'name', $form['name'])){
				$this->problemMsg .= '[Name] cannot duplicate !<br>';
				$icheck = false;
		}
	
		
		
		return $icheck;
	}

	
	public function getProblemMsg(){
		return $this->problemMsg;
	}
	
	
}
?>