<?php 
namespace framework\sql;


class _sqlWriter extends \framework\_contentWriter{
 protected $sqlType = 'SELECT';
 public $cols;
 public $tables;
 public $where;
 public $order;
 public $group;
 public $limit;

 public function __construct($sqlType = 'SELECT'){
 
 $this->sqlType = $sqlType;
// $this->tables = new tables();
  $this->tables = new clause('FROM');
 $this->where = new where();
 
 switch ($this->sqlType){
 case 'SELECT':
 $this->cols = new select();
 $this->group = new groupBy();
 $this->order = new orderBy();
 $this->limit = new limit();
 break;
 case 'UPDATE':
 $this->cols = new update();
 break;
 case 'INSERT';
 $this->cols = new insert();
 break;
 default:
 //invalid statement type
 }

 }
 public function setLimit($page,$rows){
 	$this->limit = new limit($page,$rows);
 }
public function addGroupingColumn($name){
	$this->group->addColumn($name);

} 
public function addOrderingColumn($name){
	$this->order->addColumn($name);

} 

 
 public function editColumn(string $field, $value){
 	$this->cols->addEditColumn($field,$value);
 	
 }
 public function editField(_field &$fld){
 
 		$this->editColumn($fld->dbColumn,$fld->valueSQL());
 }
 public function where($field,$value,$operator = '='){
 	$this->where->compare($field,$value,$operator);

 }
 public function editStatement($table){
 switch($this->sqlType){
 case 'UPDATE':
 $edit = $this->sqlUpdate($table);
 break;
 case 'INSERT':
 $edit = $this->sqlInsert($table);
 break;
 default:
 }
 return $edit;
 }
 
 /*
insert into x
(a,b,c)
values
(1,2,3)

update x set
(a=1,c=3)
*/
 protected function sqlInsert($table){
 $this->addContent(" INSERT INTO ".$table." ( ");
 $this->addContent($this->cols->print());
 return $this->getContent();
 }

 protected function sqlUpdate($table){
 $this->addContent(" UPDATE ".$table." SET ");
 $this->addContent($this->cols->print());
 $this->addContent($this->where->print());
 return $this->getContent();
 }

 }





?>
