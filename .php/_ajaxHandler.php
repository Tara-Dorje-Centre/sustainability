<?php


include_once("_includes.php");

function sessionVariableGET($var, $default){
	$value = $default;
	if (isset($_GET[$var])){
		$value = $_GET[$var];
	}
	return $value;

}

$ajaxFunction = sessionVariableGET('ajaxFunction','NONE');
$ajaxId = sessionVariableGET('ajaxId',0);

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
		case 'TASK_EST_EFFORT':
			$this->response = $this->TASK_EST_EFFORT();
			break;
		case 'TASK_ACTIVITY_NAME':
			$this->response = $this->TASK_ACTIVITY_NAME();
			break;
			/*
		case 'PROJECTS_SELECT':
			$this->response = $this->PROJECTS_SELECT();
			break;
			*/
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
	$d = new \html\_div();
	$d->addContent($this->ajaxFunction.$this->ajaxId);
	return $d->print();
}


private function PROJECT_TASKS_SELECT(){
	//pass project id to display tasks
	$projectId = $this->ajaxId;
	//currently selected task not defined
	$selectedTaskId = 0;	
	$task = new \application\entities\projects\task();
	//$changeJS = "ajaxRefresh('TASK_EST_EFFORT',this,'hours-estimated');";
	$changeJS = "ajaxRefresh('TASK_ACTIVITY_NAME',this,'name');";
	//$changeJS = null;
	$select = $task->optionsByProject($selectedTaskId,'task-id',$projectId,'no caption',$changeJS);
	return $select->printNoCaption();
}

private function TASK_EST_EFFORT(){
	$taskId = $this->ajaxId;
	$t = new \application\entities\projects\task();
	$a = new \application\entities\projects\activity();
	$hours = $t->getTaskHoursEstimated($taskId);
	$a->f->hoursEstimated->set($hours);
	$effort = $a->f->hoursEstimated->inputNoCaption();
	return $effort;

}

private function TASK_ACTIVITY_NAME(){
	$taskId = $this->ajaxId;
	$t = new \application\entities\projects\task();
	$a = new \application\entities\projects\activity();
	$name = $t->getTaskName($taskId);
	$a->f->name->set($name);
	$effort = $a->f->name->inputNoCaption();
	return $effort;

}

private function PROJECTS_SELECT(){
	//pass currently selected project id
	$selectedId = $this->ajaxId;
	$p = new \application\entities\projects\project();
	//on change refresh project tasks
	$changeJS = "ajaxRefresh('PROJECT_TASKS_SELECT',this,'task-id');";	
	$select = $p->options($selectedId,'project-id','no caption',$changeJs);
	return $select->printNoCaption();
}

private function PROJECTS_BY_TYPE_SELECT(){
	//pass currently selected project type id
	$projectTypeId = $this->ajaxId;
	$selectedId = 0;
	$p = new \application\entities\projects\project();
	//on change refresh project tasks
	$changeJS = "ajaxRefresh('PROJECT_TASKS_SELECT',this,'task-id');";
	$select = $p->optionsByType(0,'project-id',$projectTypeId, 'no caption',$changeJS);
	return $select->printNoCaption();
} 

}

?>
