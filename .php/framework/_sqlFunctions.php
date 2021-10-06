<?php
namespace framework\sql;

/*
*/
/*
*/
/*
function getSelectOptionsSQL($sql,$selectedValue = 0, $disabled = false, $defaultValue = 0, $defaultCaption = ''){
	if ($defaultValue === 'NO_DEFAULT_VALUE'){
		//omit default value
		$allOptions = '';
	} else {
	    $allOptions = \html\getSelectOption($defaultValue,$defaultCaption,$selectedValue);
	}

		//$locale = 'getSelectOptionsSQL:';
		global $conn;
		$result = $conn->getResult($sql);

		if($result){
		
	  	while ($row = $result->fetch_assoc())
			{	
				$optionValue = $row["value"];
				$optionCaption = $row["caption"];
				$option = \html\getSelectOption($optionValue,$optionCaption,$selectedValue);
				$allOptions .= $option;
			}

		// Free result set
		$result->close();
		}
	
	return $allOptions;	
}
*/


?>
