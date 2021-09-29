<?php
namespace application\entities\reference\sql;

class locationSQL extends \application\sql\entitySQL{

public function __construct(){
		$this->baseTable = 'locations';
		$this->typeTable = 'location_types';

}





protected function cols(){
$c = $this->select();
	$c .=  " l.id, ";
	$c .= " l.parent_id, ";
	$c .= " p.name parent_name, ";
	$c .= " p.sort_key parent_sort_key, ";
	$c .= " l.type_id, ";
	$c .= " t.name type_name, ";
	$c .= " l.name, ";
	$c .= " l.sort_key, ";
	$c .= " l.description, ";
	$c .= " l.created, ";
	$c .= " l.updated ";
	return $c;
}



protected function tables($joinTypes = true){
$q = " FROM locations as l ";
if ($joinTypes == true){
$q .= " LEFT OUTER JOIN location_types as t ON l.type_id = t.id ";
$q .= " LEFT OUTER JOIN locations as p ON l.parent_id = p.id ";
}
return $q;
}


public function list($page = 1, $rows = 10){

	$q = $this->cols();
	$q .= $this->tables(true);
	
	$q .= " ORDER BY l.sort_key ";

	$q .= $this->limit($page, $rows);
	return $q;
}


public function listChildren($idParent, $page = 1, $rows = 10){

	$q = $this->cols();
	$q .= $this->tables(true);
	//if ($idParent != -1){
	$q .= " WHERE l.parent_id = ".$idParent." "; 
	//} 
	$q .= " ORDER BY l.sort_key ";

	$q .= $this->limit($page, $rows);
	return $q;
}

public function count(){
	$q = " SELECT  ";
	$q .= " COUNT(*) count_details ";
	$q .= $this->tables();
	return $q;
}

public function countChildren($id){
	$q = " SELECT  ";
	$q .= " COUNT(*) count_details ";
	$q .= $this->tables();
	//if ($id >= 0){
	$q .= " WHERE l.parent_id = ".$id." "; 
	//} 
	return $q;
}



public function sortKey($id){
		$sql = " SELECT l.sort_key FROM locations l WHERE l.id = ".$id.' ';
		return $sql;
}

public function sortKeyUpdate($id, $sortKey){
		
		$sql = " UPDATE locations as l	";
		$sql .= " SET l.sort_key = '".dbEscapeString($sortKey)."' ";	
		$sql .= " WHERE l.id = ".$id." ";
	return $sql;
}


public function listByParentSortKey($sortKeyParent, $idParent, $page = 1, $perPage = 10){

	$q .= $this->columns();
	$q .= $this->tables();
	$q .= " WHERE l.sort_key LIKE '".$sortKeyParent."%' "; 
	$q .= " AND l.id != ".$idParent." ";
	$q .= " ORDER BY l.sort_key ";
	$q .= sqlLimitClause($page, $perPage);
	return $q;
}

public function countByParentSortKey($sortKeyParent, $idParent){
	$q = " SELECT  ";
	$q .= " COUNT(*) total_locations ";
	$q .= $this->tables();
	$q .= " WHERE l.sort_key LIKE '".$sortKeyParent."%' ";  
	$q .= " AND l.id != ".$idParent." ";	
	return $q;
}


public function info($id = 0){

	$q .= $this->cols();
	$q .= $this->tables();
	$q .= " WHERE l.id = ".$id." "; 
	return $q;
}

public function options(){

	$q = " SELECT ";
	$q .= " l.id as value, ";
	$q .= " l.sort_key as caption ";
	$q .= $this->tables();
	
	$q .= " ORDER BY caption ";
	return $q;	
}






}
?>
