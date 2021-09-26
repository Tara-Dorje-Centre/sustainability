<?php 
namespace framework\sql;

class column{
	public $name;
	public $qualifier;
	public $alias;
	public $value;
	public $operator = '=';
	public function __construct($name,$qualifier,$alias){
		$this->name = $name;
		$this->qualifier = $qualifier;
		$this->alias = $alias;
	}
	public function set($value,$operator = '='){
		$this->value = $value;
		$this->operator = $operator;
	}
	public function parentheses($content){
		return '('.$content.')';
	}
	public function qualified(bool $showAlias = false){
	
		if (!is_null($this->qualifier)){
			$name = $this->qualifier.'.'.$this->name;
		} else {
			$name = $this->name;
		}
		if ((!is_null($this->alias)) and ($showAlias == true)){
			$name .= ' as '.$this->alias;
		}
		return $name;
	}
	public function select(){
		return $this->qualified(true);
	}
	public function update(){

		$set = $this->qualified(false);
		$set .= $this->operator;
		$set .= $this->value;
		return $set;
		
	}
	protected function insertColumn(){
		return $this->name;
	}
	protected function insertValue(){
		return $this->value;
	}
	protected function where(){
	//$operator = '='){
		//$this->operator = $operator;
		$compare = $this>qualified(false);
		$compare .= $this->operator;
		$compare .= $this->value;
		return $this->parentheses($compare);
	}
	protected function orderBy(){
		return $this->qualified(false);
	}
	protected function groupBy(){
		return $this->qualified(false);
	}
	public function print($type = 'SELECT'){
		switch($type){
			case 'WHERE':
				$result = $this->where();
				break;
			case 'UPDATE':
				$result = $this->update();
				break;
			case 'INSERT-VALUE':
				$result = $this->insertValue();
				break;
			case 'INSERT-COLUMN':
				$result = $this->insertColumn();
				break;
			case 'SELECT':
				$result = $this->select();
				break;
			case 'ORDER-BY':
				$result = $this->orderBy();
				break;
			case 'GROUP-BY':
				$result = $this->groupBy();
				break;
			default:
				
		}
		return $result;
	}
	
}
class clause extends \framework\_contentWriter{
	public $type;
	public function __construct($type){
		$this->type = $type;
	}

	public function print(){
		$this->getContent();
	}
}

class limit extends clause{
	protected $page = 1;
	protected $rows = 10;

	public function __construct($page, $rows){
		parent::__construct('LIMIT');
		$this->set($page, $rows);
	}
	public function set($page, $rows){
		$this->page = $page;
		$this->rows = $rows;
	}
	public function print(){
		$limitSQL = " LIMIT ";
		$limitOffset = ($this->page - 1) * $this->perPage;
		$limitSQL .= $limitOffset.", ".$this->perPage;
		return $limitSQL;	
	}
}
class clauseColumns extends clause{
	protected $hasCols = false;
	protected $cols = array();
	protected $term = 'SELECT';
	protected $colPrefix = ', ';
	protected $printMode = 'SELECT';
	public function __construct($type){
		parent::__construct(type);
	}
	public function addCol(column $c){
		$this->hasCols = true;
		$this->cols[] = $c;
	}
	public function addColumn($name,$qualifier,$alias){
		$c = new column($name,$qualifier,$alias);
		$this->addCol($c);
	}
	public function addEditColumn($name,$value,$qualifier){
		$c = new column($name,$qualifier);
		$c->set($value);
		$this->addCol($c);
	}
	protected function printItem($c,$first){
		if ($first == false){
			$this->addContent($this->prefix);
		}
		$this->addContent($c->print($this->printMode));
	
	}
	protected function printCols(){
		$first = true;
		foreach($this->cols as $c){
			$this->printItem($c,$first);
			if($first == true){
				$first = false;
			}
		}
	}
	public function print(){
		if ($this->hasCols == true){
		$this->addContent(' '.$this->term.' ');
		$this->printCols();
		} else {
		$this->clearContent();
		}
		return $this->getContent();
	}
}

class select extends clauseColumns{
	protected $term = 'SELECT';
	protected $prefix = ', ';
	protected $printMode = 'SELECT';
	public function __construct(){
		parent::__construct('SELECT');
	}
	
}
class where extends clauseColumns{
	protected $term = 'WHERE';
	protected $prefix = ' AND ';
	protected $printMode = 'WHERE';
	public function __construct(){
		parent::__construct('WHERE');
	}
	public function compare($col,$value,$operator = '='){
		$c = new column($col);
		$c->set($value,$operator);
		$this->addCol($c);
	}
	public function compareColumns($colLeft, $colRight, $operator = '='){
		$c =new column($colLeft);
		$c->set($colRight,$operator);
		$this->addCol($c);
	}
	
}
class groupBy extends clauseColumns{
	protected $term = 'GROUP BY';
	protected $prefix = ', ';
	protected $printMode = 'GROUP-BY';
	public function __construct(){
		parent::__construct('GROUP BY');
		

	}
}
class orderBy extends clauseColumns{
	protected $term = 'ORDER BY';
	protected $prefix = ', ';
	protected $printMode = 'ORDER-BY';
	public function __construct(){
		parent::__construct('ORDER BY');
	}
}

class update extends clauseColumns{
	protected $term = '(';
	protected $prefix = ', ';
	protected $printMode = 'UPDATE';
	public function __construct(){
		parent::__construct('UPDATE');
	}
	public function print(){
		//$this->addContent(' (');
		//$this->printMode = 'UPDATE';
		$this->printCols();
		$this->addContent(') ');
		return $this->getContent();
	}
}


class insert extends clauseColumns{
	protected $term = '(';
	protected $prefix = ', ';
	protected $printMode = 'switched';
	public function __construct(){
		parent::__construct('UPDATE');
	}
	public function print(){
		//$this->addContent(' (');
		$this->printMode = 'INSERT-COLUMNS';
		$this->printCols();
	//	$this->addContent(') ');
		$this->term = ') VALUES (';
		$this->printMode = 'INSERT-VALUES';
		$this->printCols();
		$this->addContent(') ');
		return $this->getContent();
	}
}
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
 //nvalid statement type
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
