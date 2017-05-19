<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class ReceiptLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($taskId,$caption = 'Receipts', $projectId = 0, $display = 'TASK', $approved = 'no'){
		$link = $this->listing($taskId, $projectId, $display, $approved);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}	
	private function detailHref($pageAction = 'VIEW', $receiptId = 0, $taskId = 0, $caption = 'Receipt'){
		$link = $this->detail($pageAction,$receiptId,$taskId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	public function listing($taskId, $projectId = 0, $display = 'TASK', $approved = 'no'){
		$link = 'pr_Receipt_List.php?taskId='.$taskId;
		if ($projectId != 0){
			$link .= '&projectId='.$projectId;
		}
		if ($display != 'TASK'){
			$link .= '&displayProject='.$display;
			$link .= '&approvedReceipts='.$approved;
		}
		return $link;
	}
	public function listingPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&resultsPageReceipt=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
	public function detail($pageAction, $receiptId, $taskId = 0){
		$link = 'pr_Receipt_Detail.php?pageAction='.$pageAction;
		if($taskId != 0){
			$link .= '&taskId='.$taskId;			
		}
		if ($receiptId != 0){
			$link .= '&receiptId='.$receiptId;
		}
		return $link;
	}	
	public function detailAddHref($taskId,$caption = '+Receipt'){
		$l = $this->detailHref('ADD',0,$taskId,$caption);
		return $l;	
	}
	public function detailViewHref($receiptId,$caption = 'ViewReceipt'){
		$l = $this->detailHref('VIEW',$receiptId,0,$caption);
		return $l;	
	}
	public function detailEditHref($receiptId,$caption = 'EditReceipt'){
		$l = $this->detailHref('EDIT',$receiptId,0,$caption);
		return $l;	
	}
	public function detailViewEditHref($receiptId = 0, $viewCaption = 'Receipt'){
		
		if ($receiptId != 0){
			$links = $this->detailViewHref($receiptId,$viewCaption);
			$links .= $this->detailEditHref($receiptId,'#');
		}
		return $links;
	}	
	public function linkProjectReceipts($projectId){
		return $this->listingHref(0,'ProjectReceipts',$projectId,'PROJECT','yes');	
	}
	public function linkTaskReceipts($taskId){
		return $this->listingHref($taskId,'TaskReceipts',0,'TASK','no');	
	}
	
}

class ReceiptList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $task;
	private $displayProject = 'TASK';
	private $approved = 'no';
	public $costActual = 0;
	//public $costEstimated = 0;
	private $sql;

	public function __construct(){
		$this->task = new Task;
		$this->sql = new ReceiptSQL;
	}
	
	public function setDetails($taskId, $resultPage = 1, $resultsPerPage = 10, $display = 'TASK', $approved = 'no',$projectId = 0){
		$this->task->id = $taskId;	
		$this->displayProject = $display;
		$this->approved = $approved;
		$this->task->project->id = $projectId;
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		
		$this->task->setDetails($this->task->id, $this->task->project->id, 'VIEW');
		$this->setFoundCount();
		$this->setSummary();
	}
		
	public function pageTitle(){
		$title = openDiv('section-heading-title','none');
		if ($this->displayProject == 'TASK'){
			$title .= $this->task->project->name.br();
			$title .= 'Task: '.$this->task->name.br();
			if ($this->task->receiptsAuthProject == 'yes'){
				$title .= 'Receipts Approved = '.$this->costActual;
			} else {
				$title .= 'Receipts Estimated';
			}
		} elseif ($this->displayProject == 'PROJECT'){
			$title .= $this->task->project->name.br();	
			$title .= 'Receipts Approved = '.$this->costActual;
		}
		$title .= closeDiv();
		return $title;	
	}	

	public function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projects = new ProjectLinks($menuType,$menuStyle);
		$tasks = new TaskLinks($menuType,$menuStyle);
		$rL = new ReceiptLinks($menuType,$menuStyle);
					
		$menu = $projects->openMenu('section-heading-links');
		
		$menu .= $projects->detailViewHref($this->task->project->id);			
		if ($this->task->project->receiptsCount > 0){
			$menu .= $rL->linkProjectReceipts($this->task->project->id);
		}
		if ($this->displayProject == 'TASK'){
			$menu .= $projects->resetMenu();	
			$menu .= $tasks->detailViewHref($this->task->id);
			if ($this->found > 0){
				$menu .= $rL->linkTaskReceipts($this->task->id);		
			}
			$menu .= $projects->resetMenu();	
			$menu .= $rL->detailAddHref($this->task->id);
		}
		$menu .= $projects->closeMenu();	
		return $menu;			
	}	
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		if ($this->displayProject == 'PROJECT'){
			$sql = $this->sql->countReceiptsByProject($this->task->project->id, $this->approved);
		} else {
			$sql = $this->sql->countReceiptsByTask($this->task->id);
		}
		$this->found = getSQLCount($sql, 'total_receipts');
	}
	
	private function setSummary(){
		if ($this->displayProject == 'PROJECT'){
			$sql = $this->sql->summarizeReceiptsByProject($this->task->project->id, $this->approved);
		} else {
			$sql = $this->sql->summarizeReceiptsByTask($this->task->id);
		}
		$result = mysql_query($sql) or die(mysql_error());
		while ($row = mysql_fetch_array($result))
		{
			$this->costActual = $row["sum_cost_actual"];
		}
		mysql_free_result($result);			
	}

	public function printPage(){
		
		$heading = $this->getPageHeading();
		$details = $this->getPageDetails();

		$site = new _SiteTemplate;
		$site->setSiteTemplateDetails($heading, $details);
		$site->printSite();
		
	}

	public function getPageDetails(){
	
		$details = $this->getListing();
		return $details;
		
	}

		
	public function getListing($pagingBaseLink = 'USE_LISTING'){
		if ($this->displayProject == 'PROJECT'){
			$id = $this->task->project->id;
			$sql = $this->sql->listReceiptsByProject($id, $this->resultPage, $this->perPage, $this->approved);		
		} elseif ($this->displayProject == 'TASK'){
			$sql = $this->sql->listReceiptsByTask($this->task->id,$this->resultPage,$this->perPage);		
		}
		$result = mysql_query($sql) or die(mysql_error());		
		
		$receiptL = new ReceiptLinks;
		$taskL = new TaskLinks;
				
		if ($pagingBaseLink == 'USE_LISTING'){
			$base = $receiptL->listing($this->task->id, $this->task->project->id,$this->displayProject,$this->approved);
		} else { 
			$base = $pagingBaseLink;
		}
		$pagingLinks = $receiptL->listingPaged($base,$this->found,$this->resultPage,$this->perPage);		
		
		$r = new Receipt;
		$r->setDetails(0,$this->task->id,'ADD');
		$quickEdit = $r->editForm();
		$list = openDisplayList('receipt','Receipts',$pagingLinks,$quickEdit);

		$heading = '';
		if ($this->displayProject == 'PROJECT'){
			$heading = wrapTh('Task');	
		}
		$heading .=  wrapTh('Receipt');
		$heading .= wrapTh('Type');
		$heading .=  wrapTh('Date Reported');
		$heading .=  wrapTh('Received By');
		$heading .= wrapTh('Qty Units');
		$heading .=  wrapTh('Qty');
		$heading .=  wrapTh('Unit Amount');
		$heading .=  wrapTh('Amount Actual');
		$heading .= wrapTh('Received From');
		$list .=  wrapTr($heading);

		while($row = mysql_fetch_array($result))
		{	
			$m = new Receipt;
			$m->id = $row["id"];
			$m->task->id = $row["task_id"];
			$m->task->name = $row["task_name"];
			$m->activityId = $row["activity_id"];
			$m->name = stripslashes($row["name"]);
			$m->typeName = stripslashes($row["type_name"]);
			//$m->description = stripslashes($row["description"]);
			$m->dateReported = $row["date_reported"];
			$m->receivedBy = stripslashes($row["received_by"]);
			$m->receivedFrom = stripslashes($row["received_from"]);
			$m->updated = $row["updated"];	
			$m->quantityUnitMeasureName = stripslashes($row["qty_unit_measure_name"]);
			$m->quantity = $row["quantity"];	
			$m->costActual = $row["cost_actual"];
			$m->costUnit = $row["cost_unit"];
			//$m->notes = stripslashes($row["notes"]);
			$cssItem = stripslashes($row["highlight_style"]);

			$m->formatForDisplay();
			
			$detail = '';
			if ($this->displayProject == 'PROJECT'){
				$link = $taskL->detailViewEditHref($m->task->id, $m->task->name);
				$detail = wrapTd($link);
			}
			$link = $receiptL->detailViewEditHref($m->id,$m->name);
			$detail .= wrapTd($link);
			$detail .= wrapTd($m->typeName);			
			$detail .= wrapTd($m->dateReported);
			$detail .= wrapTd($m->receivedBy);
			$detail .= wrapTd($m->quantityUnitMeasureName);
			$detail .= wrapTd($m->quantity);
			$detail .= wrapTd($m->costUnit);
			$detail .= wrapTd($m->costActual);
			$detail .= wrapTd($m->receivedFrom);

			$list .=  wrapTr($detail, $cssItem);
		}
		mysql_free_result($result);

		$list .= closeDisplayList();
		return $list;
		
	}
}


class Receipt {
    public $id = 0;
    public $activityId = 0;	
	public $typeId = 0;
	public $typeName = '';
	public $name;
    public $description;
    public $dateReported;
	public $receivedBy;
	public $receivedFrom;
    public $updated;
    public $quantity = 1;	
	public $quantityUnitMeasureId = 0;
	public $quantityUnitMeasureName = '';
    public $costUnit = 0;	
	public $costActual = 0;
    public $notes;
	public $task;
	private $sql;
	// property to support edit/view/add mode of calling page
    public $pageMode;

	public function __construct(){
		$this->task = new Task;
		$this->sql = new ReceiptSQL;
	}


	public function setDetails($detailReceiptId, $parentTaskId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $detailReceiptId;
		$this->task->id = $parentTaskId;
		
		$sql = $this->sql->infoReceipt($this->id);
		$result = mysql_query($sql) or die(mysql_error());

		while($row = mysql_fetch_array($result))
			{	
			$this->task->id = $row["task_id"];
			$this->activityId = $row["activity_id"];
			$this->typeId = $row["type_id"];
			$this->typeName = stripslashes($row["type_name"]);
			$this->name = stripslashes($row["name"]);
			$this->description = stripslashes($row["description"]);
			$this->dateReported = $row["date_reported"];
			$this->receivedBy = stripslashes($row["received_by"]);
			$this->receivedFrom = stripslashes($row["received_from"]);
			$this->updated = $row["updated"];	
			$this->quantity = $row["quantity"];	
			$this->quantityUnitMeasureId = $row["qty_unit_measure_id"];
			$this->quantityUnitMeasureName = stripslashes($row["qty_unit_measure_name"]);
			$this->costActual = $row["cost_actual"];
			$this->costUnit = $row["cost_unit"];
			$this->notes = stripslashes($row["notes"]);
			
		}
		mysql_free_result($result);

		$this->setParentTask();				
	}	
	
	public function setParentTask(){
		$this->task->setDetails($this->task->id, 0, $this->pageMode);
	}
		
	function pageTitle(){	
		$title = openDiv('section-heading-title','none');
		//show page mode for debugging
		//$heading .= '['.$this->pageMode.']';
		$title .= $this->task->project->name.br();
		$title .= 'Task: '.$this->task->name;		
		$title .= closeDiv();
		return $title;
		
	}
	
	
	function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$projects = new ProjectLinks($menuType,$menuStyle);
		$tasks = new TaskLinks($menuType,$menuStyle);
		$rL = new ReceiptLinks($menuType,$menuStyle);
					
		$menu = $projects->openMenu('section-heading-links');

		$menu .= $projects->detailViewHref($this->task->project->id);
		if ($this->task->project->receiptsCount > 0){
			$menu .= $rL->listingHref(0,'ProjectReceipts',$this->task->project->id,'PROJECT','YES');
		}
		
		$menu .= $projects->resetMenu();
		$menu .= $tasks->detailViewHref($this->task->id);
		if ($this->task->receiptCount > 0){
			$menu .= $rL->listingHref($this->task->id,'TaskReceipts',0,'TASK','NO');
		}
		$menu .= $projects->resetMenu();
		if ($this->pageMode == 'VIEW'){
			$menu .= $rL->detailEditHref($this->id);
		} elseif ($this->pageMode == 'EDIT'){
			$menu .= $rL->detailViewHref($this->id);
		}
		
		$menu .= $projects->closeMenu();
		return $menu;
	}

		
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function formatForDisplay(){
		$this->name = displayLines($this->name);
		$this->description = displayLines($this->description);
		$this->notes = displayLines($this->notes);
		$this->dateReported = getTimestampDate($this->dateReported);
		$this->updated = getTimestampDate($this->updated);
	}

	public function printPage(){
		
		$heading = $this->getPageHeading();
		$details = $this->getPageDetails();

		$site = new _SiteTemplate;
		$site->setSiteTemplateDetails($heading, $details);
		$site->printSite();
		
	}

	
	public function getPageDetails(){			

		if ($this->pageMode == 'EDIT' or $this->pageMode == 'ADD'){
			$details = $this->editForm();
		} else {
			$details = $this->display();
		}
		return $details;
		
	}
	
	
	public function display(){
		$this->formatForDisplay();
		
		$detail = openDisplayDetails('receipt','Receipt Details');

		$detail .= captionedParagraph('name','Receipt',$this->name);
		$detail .= captionedParagraph('type-name','Type',$this->typeName);
		$detail .= captionedParagraph('description','Description',$this->description);
		
		$detail .= captionedParagraph('reported','Reported',$this->dateReported);
		$detail .= captionedParagraph('receivedBy','Received By', $this->receivedBy);
		$detail .= captionedParagraph('receivedFrom','Received From', $this->receivedFrom);
		$detail .= captionedParagraph('updated','Updated',$this->updated);	
		$detail .= captionedParagraph('quantity-units','Quantity Units',$this->quantityUnitMeasureName);	
		$detail .= captionedParagraph('quantity','Quantity',$this->quantity);
		$detail .= captionedParagraph('cost-unit','Unit Amount',$this->costUnit);
		$detail .= captionedParagraph('cost-actual','Amount Actual',$this->costActual);
 		$detail .= captionedParagraph('notes','Notes',$this->notes);

		$detail .= closeDisplayDetails();

		
		return $detail;
	}	
	
	private function setAddRecordDefaults(){
		global $sessionTime;
		$this->dateReported = $sessionTime;	
		//$this->activityId = $this->task->locationId;
		$this->quantity = 1;
		$this->quantityUnitMeasureId = 0;
		$this->costUnit = 0;
		$this->costActual = 0;		
		$this->receivedBy = $_SESSION['login-name'];
	}
	
	public function editForm(){
		if ($this->pageMode == 'ADD'){		
			$this->setAddRecordDefaults();
			$legend = 'Add Receipt';
		} else {
			$legend = 'Edit Receipt';
		}
		$entity = 'receipt';
		$c = new ProjectTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		$form = openEditForm($entity,$legend,'pr_Receipt_Save.php',$contextMenu);

		//start required fields
		$fields = inputFieldName($entity,$this->name,'name','Receipt');

		$fields .= inputFieldUser($entity,$this->receivedBy,'receivedBy','Received By');

		$fields .= inputFieldUser($entity,$this->receivedFrom,'receivedFrom','Received From');
				
		$fields .= inputFieldNumber($entity,$this->costUnit,'costUnit','Unit Amount');

		$fields .= inputFieldTimestamp($entity, 'dateReported', $this->dateReported, 'Date Reported'); 		

		$fields .= inputFieldDescription($entity,$this->description,'description');

		//end required fields
		$formRequired = $fields;
		
		//start optional fields
		$fields = inputFieldNumber($entity,$this->quantity,'quantity','Quantity');
		
		$u = new UnitOfMeasure;
		$select = $u->getUnitOfMeasureSelectList($this->quantityUnitMeasureId,'quantityUnitMeasureId','false');
		$fields .= captionedInput('Quantity Units', $select);

		$tooltip = 'Set when task costs are approved';
		$fields .= inputFieldNumber($entity,$this->costActual,'costActual','Amount Actual',$tooltip);

		$m = new ReceiptType;
		$select = $m->getReceiptTypeSelectList($this->typeId,'typeId','false');
		$fields .= captionedInput('Receipt Type',$select);
		

		$fields .= inputFieldNotes($entity,$this->notes,'notes');

		//end optional fields (hidden by default)
		$formOptional = $fields;

		//hidden fields and submit,reset buttons
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('taskId', $this->task->id);
		$hidden .= getHiddenInput('receiptId', $this->id);
		$hidden .= getHiddenInput('activityId', $this->activityId);
		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;
			
		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);	

		return $form;
	}
	
	private function setActualCost(){
			$this->costActual = $this->costUnit * $this->quantity;
	}
	
	public function collectPostValues(){
		//called by save form prior to running adds/updates
		$this->pageMode = $_POST['mode'];
		
		$this->task->id = $_POST['taskId'];
		$this->activityId = $_POST['activityId'];

		$this->id = $_POST['receiptId'];
		$this->receiptId = $_POST['receiptId'];
		$this->typeId = $_POST['typeId'];
		$this->name = $conn>escape_string($_POST['name']);
		$this->description = $conn>escape_string($_POST['description']); 
		$this->notes = $conn>escape_string($_POST['notes']); 
		$this->dateReported = getTimestampPostValues('dateReported');
		$this->receivedBy = $conn>escape_string($_POST['receivedBy']);
		$this->receivedFrom = $conn>escape_string($_POST['receivedFrom']);
		$this->quantity = $_POST['quantity']; 
		$this->quantityUnitMeasureId = $_POST['quantityUnitMeasureId'];
		$this->costUnit = $_POST['costUnit']; 
		
		$this->setActualCost();

		$this->setParentTask();
	}

	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			
			$sql = " UPDATE receipts m ";
			$sql .= " SET ";
			$sql .= " m.name = '".$this->name."', ";
			$sql .= " m.description = '".$this->description."', ";
			$sql .= " m.notes = '".$this->notes."', ";
			$sql .= " m.updated = CURRENT_TIMESTAMP, ";
			$sql .= " m.date_reported = '".$this->dateReported."', ";
			$sql .= " m.received_by = '".$this->receivedBy."', ";
			$sql .= " m.received_from = '".$this->receivedFrom."', ";			
			$sql .= " m.type_id = ".$this->typeId.", ";
			$sql .= " m.activity_id = ".$this->activityId.", ";
			$sql .= " m.quantity = ".$this->quantity.", ";
			$sql .= " m.qty_unit_measure_id = ".$this->quantityUnitMeasureId.", ";
			$sql .= " m.cost_unit = ".$this->costUnit.", ";
			$sql .= " m.cost_actual = ".$this->costActual." ";
			$sql .= " WHERE m.id = ".$this->id." ";

			$result = mysql_query($sql) or die(mysql_error());
			
			$this->task->resetReceiptsAuthorization();
			
		} else {
	
			$sql = " INSERT INTO receipts ";
			$sql .= " (name, ";
			$sql .= " description, ";
			$sql .= " task_id, ";
			$sql .= " activity_id, ";
			$sql .= " date_reported, ";
			$sql .= " received_by, ";
			$sql .= " received_from, ";
			$sql .= " updated, ";
			$sql .= " quantity, ";
			$sql .= " cost_unit, ";
			$sql .= " cost_actual, ";
			$sql .= " type_id, ";
			$sql .= " qty_unit_measure_id, ";
			$sql .= " notes) ";
			$sql .= " VALUES (";
			$sql .= "'".$this->name."', ";
			$sql .= "'".$this->description."', ";
			$sql .= "".$this->task->id.", ";
			$sql .= "".$this->activityId.", ";
			$sql .= " '".$this->dateReported."', ";
			$sql .= " '".$this->receivedBy."', ";
			$sql .= " '".$this->receivedFrom."', ";
			$sql .= " CURRENT_TIMESTAMP, ";
			$sql .= "".$this->quantity.", ";
			$sql .= "".$this->costUnit.", ";
			$sql .= "".$this->costActual.", ";
			$sql .= " ".$this->typeId.", ";
			$sql .= " ".$this->quantityUnitMeasureId.", ";
			$sql .= "'".$this->notes."') ";
			
			$result = mysql_query($sql) or die(mysql_error());
			$this->id = mysql_insert_id();

			$this->task->resetReceiptsAuthorization();
		}
	
	}
	
} 

class ReceiptSQL{
function columnsReceipts(){
$c = " m.id, ";
$c .= " m.task_id, ";
$c .= " t.task_order, ";
$c .= " t.name task_name, ";
$c .= " m.activity_id, ";
$c .= " m.name, ";
$c .= " m.description, ";
$c .= " m.date_reported, ";
$c .= " m.received_by, ";
$c .= " m.received_from, ";
$c .= " m.updated, ";
$c .= " m.quantity, ";
$c .= " m.qty_unit_measure_id, ";
$c .= " uom.name qty_unit_measure_name, ";
$c .= " m.cost_unit, ";
$c .= " m.cost_actual, ";
$c .= " m.type_id, ";
$c .= " mt.name type_name, ";
$c .= " mt.highlight_style, ";
$c .= " m.notes ";
return $c;	
}
public function infoReceipt($receiptId){
$q = " SELECT ";	
$q .= $this->columnsReceipts();
$q .= " FROM receipts AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN receipt_types AS mt ON m.type_id = mt.id ";
$q .= " LEFT OUTER JOIN units_of_measure uom ON m.qty_unit_measure_id = uom.id ";
$q .= " WHERE  ";
$q .= " m.id = ".$receiptId." ";
return $q;
}
public function listReceiptsByTask($selectedTaskId, $resultPage, $rowsPerPage){
$q = " SELECT ";	
$q .= $this->columnsReceipts();
$q .= " FROM receipts AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN receipt_types AS mt ON m.type_id = mt.id ";
$q .= " LEFT OUTER JOIN units_of_measure uom ON m.qty_unit_measure_id = uom.id ";
$q .= " WHERE  ";
$q .= " m.task_id = ".$selectedTaskId." ";
$q .= " ORDER BY ";
$q .= " date(m.date_reported) desc, m.name ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}
public function countReceiptsByTask($selectedTaskId){
$q = " SELECT  ";
$q .= " COUNT(*) total_receipts";
$q .= " FROM receipts AS m ";
$q .= " WHERE  ";
$q .= " m.task_id = ".$selectedTaskId." ";
return $q;
}
public function summarizeReceiptsByTask($selectedTaskId){
$q = " SELECT  ";
$q .= " COUNT(*) total_receipts, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM receipts AS m ";
$q .= " WHERE  ";
$q .= " m.task_id = ".$selectedTaskId." ";
return $q;
}
public function listReceiptsByProject($projectId, $resultPage, $rowsPerPage, $approved = 'yes'){
$q = " SELECT ";	
$q .= $this->columnsReceipts();
$q .= " FROM receipts AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN receipt_types AS mt ON m.type_id = mt.id ";
$q .= " LEFT OUTER JOIN units_of_measure uom ON m.qty_unit_measure_id = uom.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";
$q .= " AND t.receipts_auth_project = '".$approved."' ";
$q .= " ORDER BY ";
$q .= " date(m.date_reported) desc, t.task_order, m.name ";
$q .= sqlLimitClause($resultPage, $rowsPerPage);
return $q;
}
public function countReceiptsByProject($projectId, $approved = 'yes'){
$q = " SELECT  ";
$q .= " COUNT(*) total_receipts";
$q .= " FROM receipts AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";
$q .= " AND t.receipts_auth_project = '".$approved."' ";
return $q;
}
public function summarizeReceiptsByProject($projectId, $approved = 'yes'){
$q = " SELECT  ";
$q .= " COUNT(*) total_receipts, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM receipts AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";
$q .= " AND t.receipts_auth_project = '".$approved."' ";
return $q;
}
public function summarizeReceiptsByProjectAndType($projectId, $approved = 'yes'){
$q = " SELECT  ";
$q .= " IFNULL(mt.name, 'Unspecified') receipt_type, ";
$q .= " COUNT(*) total_receipts, ";
$q .= " SUM(m.cost_actual) sum_cost_actual ";
$q .= " FROM receipts AS m ";
$q .= " JOIN tasks AS t ON m.task_id = t.id ";
$q .= " LEFT OUTER JOIN receipt_types mt on m.type_id = mt.id ";
$q .= " WHERE  ";
$q .= " t.project_id = ".$projectId." ";
$q .= " AND t.receipts_auth_project = '".$approved."' ";
$q .= " GROUP BY receipt_type ";
return $q;
}

}
?>
