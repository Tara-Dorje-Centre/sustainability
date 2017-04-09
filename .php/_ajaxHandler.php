<?php 
include_once("_includes.php");
include_once("_htmlFunctions.php");
include_once("_formFunctions.php");

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
	
		if ($this->ajaxFunction == 'PROJECT_TASKS_SELECT'){
			$this->response = $this->PROJECT_TASKS_SELECT();
		} else if ($this->ajaxFunction == 'PROJECT_TASKS_RESET'){
			$this->response = $this->PROJECT_TASKS_RESET();
		} else if ($this->ajaxFunction == 'TASK_EST_EFFORT'){
			$this->response = $this->TASK_EST_EFFORT();
		} else if ($this->ajaxFunction == 'PROJECTS_SELECT'){
			$this->response = $this->PROJECTS_SELECT();
		} else if ($this->ajaxFunction == 'PROJECTS_BY_TYPE_SELECT'){
			$this->response = $this->PROJECTS_BY_TYPE_SELECT();
		} else {
			$this->response = $this->UNDEFINED_AJAX_FUNCTION();
		}
		
		//send response to the caller
		echo $this->response;		
		
	}


private function UNDEFINED_AJAX_FUNCTION(){
	$div = wrapDiv($this->ajaxFunction.$this->ajaxId,'ajaxMessages');
	return $div;
}

//if ($ajaxFunction == 'MESSAGES'
private function PROJECT_TASKS_SELECT(){
	//pass project id to display tasks
	$projectId = $this->ajaxId;
	//currently selected task not defined
	$selectedTaskId = 0;	
	include_once("pr_Task_Classes.php");
	$t = new Task;
	$t->setDetails($selectedTaskId,$projectId,'VIEW');
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
	include_once("pr_Task_Classes.php");
	$t = new Task;
	$t->setDetails($selectedTaskId,$projectId,'VIEW');
	$t->project->id = $projectId;
	
	$changeJs = "ajaxRefresh('TASK_EST_EFFORT',this,'activityHoursActual');";
	$select = $t->getTaskSelectList($selectedTaskId,'taskId','false',false,$changeJs);
	return $select;
}

private function TASK_EST_EFFORT(){
	$taskId = $this->ajaxId;

	//include_once("pr_Activity_Classes.php");
	$a = new Activity;
	
	$a->setDetails(0,$taskId,'ADD');
	$a->setAddRecordDefaults();
	$effort = getTextInput('activityHoursActual', $a->hoursActual, 10, 4);
	return $effort;
		
}

private function PROJECTS_SELECT(){
	//pass currently selected project id
	$selectedId = $this->ajaxId;

	//include_once("pr_Project_Classes.php");
	$p = new Project;
	$p->setDetails($selectedId,'VIEW');
	$changeJs = "ajaxRefresh('PROJECT_TASKS_SELECT',this,'taskId');";	
	$select = $p->getProjectSelectList($selectedId,'projectId','false',false,$changeJs);
	return $select;
}

private function PROJECTS_BY_TYPE_SELECT(){
	$projectTypeId = $this->ajaxId;
	$selectedId = 0;
	$p = new Project;
	$p->setDetails($selectedId,'VIEW');
	$changeJs = "ajaxRefresh('PROJECT_TASKS_SELECT',this,'taskId');";
	$select = $p->getProjectSelectListByTypeId($projectTypeId,$selectedId,'projectId','false',false,$changeJs);
	return $select;

	
} 

}

?>