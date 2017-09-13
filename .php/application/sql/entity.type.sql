<?php
namespace application\sql;

class entityTypeSQL extends entitySQL{

//protected $baseTable = '[entity]_type';
	protected $fieldId = 'et.id';
protected function cols(){
$c = $this->select();
$c .= "et.id, ";
$c .= " et.description, ";
$c .= " et.name, ";
$c .= " et.updated, ";
$c .= " et.created, ";
$c .= " et.highlight_style, ";
//missing display order from some tables
$c .= " et.display_order, ";
$c .= " et.notes ";
return $c;	
}



	protected function colsOption($first = true){
		$c = $this->select($first);
		$c .= " et.id as value, ";
		$c .= " et.name as caption ";
		return $c;
	}

	protected function tables($joinTypes = false){
		$f = " FROM ".$this->baseTable." as et ";
		return $f;
	}
	
	//override entity list sql to order by disdplkayorder
	public function list($page = 1, $rows = 10){
		$q = $this->cols();
		$q .= $this->tables();
		$q .= ' ORDER BY display_order ';
		$q .= $this->limit($page, $rows);
		return $q;
	}

}
?>
