<?php
namespace application\sql;

class baseEntitySQL {
	protected function alias($object, $alias, $useComma = false){
		$a = $object." AS ".$alias;
		if ($useComma == true){
			$a .= ", ";
		}
		return $a;
	}
	protected function clause($first = true, $term = 'SELECT', $connector = ","){
		if ($first == true){
			$c = $term;
		} else {
			$c = $connector;
		}
		return $c;
	}
	
	protected function select($first = true){
	 	return $this->clause($first, ' SELECT ', ',');
	}
	 
	protected function from($first = true){
	 	return $this->clause($first, ' FROM ', ',');
	}
	
	protected function where($first = true){
	 	return $this->clause($first, ' WHERE ', ' AND ');
	}
	 
 	protected function orderBy($first = true){
	 	return $this->clause($first, ' ORDER BY ', ',');
	}
	 
 	protected function groupBy($first = true){
	 	return $this->clause($first, ' GROUP BY ', ',');
 	}
	
	protected function equalTo($field, $value, $quoteValue = false){
		if ($quoteValue == true){
			$v = $this->quote($value);
		} else {
			$v = $value;
		}
		return $field." = ".$v." ";
	}
	
	protected function notEqualTo($field, $value, $quoteValue = false){
		if ($quoteValue == true){
			$v = $this->quote($value);
		} else {
			$v = $value;
		}
		return $field." != ".$v." ";
	}
	
 	protected function whereNumber($id, $field, $first = false){
		$q = $this->where($first);
		$q .= $this->equalTo($field, $id);
		return $q;
 	}
	protected function quote($value){
		return "'".$value."'";
	}
	protected function whereString($value, $field, $useUpper = false, $first = false){
		$w = $this->where($first);
	 	if ($useUpper == false){
	 		$v = $this->quote($value);
	 		$f = $field;
	 	} else {
	 		$f = $this->upper($field);
	 		$v = $this->upper($value, true);
	 	}
	 	$w = $this->equalTo($f, $v);
	 	return $w;
	}
	 
	protected function nullReplace($field, $nullReplace = "'unspecified'"){
		return " IFNULL(".$field.", ".$nullReplace.") ";
	}
	
	protected function dateYear($field){
		return " YEAR(".$field.") ";
	}
	
	protected function dateMonth($field){
		return " MONTH(".$field.") ";
	}
	
	protected function upper($fieldOrValue, $quote = false){
		if ($quote == true){
			$f = $this->quote($fieldOrValue);
		} else {
			$f = $fieldOrValue;
		}
		return " UPPER(".$f.") ";
	}
	
	
	protected function sum($field){
		return " SUM(".$field.") ";
	}
	
	protected function dateDay($field){
		return " DAY(".$field.") ";
	}
	
}

?>
