<?php 
include_once("_includes.php");



$ajaxFunction = sessionVariableGET('ajaxFunction','NONE');
$ajaxId = sessionVariableGET('ajaxId',0);
//$ajaxAction = sessionVariableGET('ajaxAction','DEFAULT');

$svc = new _ajax($ajaxFunction, $ajaxId);
$svc->respond();

class _ajax{

	private $ajaxFunction = '';
	private $ajaxId = 0;
	private $response = '';

	public function __construct($ajaxFunction = '', $ajaxId = 0){
		$this->ajaxFunction = $ajaxFunction;
		$this->ajaxId = $ajaxId;
	}
	
	public function respond(){
	
		switch ($this->ajaxFunction){
		case 'PROJECT_TASKS_SELECT':
			$this->response = $this->PROJECT_TASKS_SELECT();
			break;
		case 'PROJECT_TASKS_RESET':
			$this->response = $this->PROJECT_TASKS_RESET();
			break;
		case 'TASK_EST_EFFORT':
			$this->response = $this->TASK_EST_EFFORT();
			break;
		case 'PROJECTS_SELECT':
			$this->response = $this->PROJECTS_SELECT();
			break;
		case 'PROJECTS_BY_TYPE_SELECT':
			$this->response = $this->PROJECTS_BY_TYPE_SELECT();
			break;
		default:
			$this->response = $this->UNDEFINED_AJAX_FUNCTION();
		}
		
		//send response to the caller
		echo $this->response;		
		
	}


private function UNDEFINED_AJAX_FUNCTION(){
	$div = wrapDiv($this->ajaxFunction.$this->ajaxId,'ajaxMessages');
	return $div;
}


private function PROJECT_TASKS_SELECT(){
	//pass project id to display tasks
	$projectId = $this->ajaxId;
	//currently selected task not defined
	$selectedTaskId = 0;	
	//include_once("pr_Task_Classes.php");
	$t = new Task('VIEW',$selectedTaskId,$projectId);
	$t->setDetails();
	$t->project->id = $projectId;
	
	$changeJs = "ajaxRefresh('TASK_EST_EFFORT',this,'activityHoursActual');";
	$select = $t->getTaskSelectList($selectedTaskId,'taskId','false',false,$changeJs);

	return $select;
}

private function PROJECT_TASKS_RESET(){
	//pass project id to display tasks
	$projectId = $this->ajaxId;
	//currently selected task not defined
	$selectedTaskId = 0;	
	//include_once("pr_Task_Classes.php");/
	$t = new Task('VIEW',$selectedTaskId,$projectId);
	$t->setDetails();
	$t->project->id = $projectId;
	
	$changeJs = "ajaxRefresh('TASK_EST_EFFORT',this,'activityHoursActual');";
	$select = $t->getTaskSelectList($selectedTaskId,'taskId','false',false,$changeJs);
	return $select;
}

private function TASK_EST_EFFORT(){
	$taskId = $this->ajaxId;

	//include_once("pr_Activity_Classes.php");
	$a = new Activity('ADD',0,$taskId);
	
	$a->setDetails();
	$a->setAddRecordDefaults();
	$effort = getTextInput('activityHoursActual', $a->hoursActual, 10, 4);
	return $effort;
		
}

private function PROJECTS_SELECT(){
	//pass currently selected project id
	$selectedId = $this->ajaxId;

	//include_once("pr_Project_Classes.php");
	$p = new Project('VIEW',$selectedId);
	$p->setDetails();
	$changeJs = "ajaxRefresh('PROJECT_TASKS_SELECT',this,'taskId');";	
	$select = $p->getProjectSelectList($selectedId,'projectId','false',false,$changeJs);
	return $select;
}

private function PROJECTS_BY_TYPE_SELECT(){
	$projectTypeId = $this->ajaxId;
	$selectedId = 0;
	$p = new Project('VIEW',$selectedId);
	$p->setDetails();
	$changeJs = "ajaxRefresh('PROJECT_TASKS_SELECT',this,'taskId');";
	$select = $p->getProjectSelectListByTypeId($projectTypeId,$selectedId,'projectId','false',false,$changeJs);
	return $select;

	
} 

}

?>
