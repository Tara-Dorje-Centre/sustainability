<?php
namespace application\system\sql;

class userSQL extends \application\sql\entitySQL{
	public function __construct(){
		$this->baseTable = 'users';
	}
	

protected function cols(){
$c = $this->select();
	$c .= " u.id, "; 
	$c .= " u.name_first, "; 
	$c .= " u.name_last, "; 
	$c .= " u.email, "; 
	$c .= " u.focus, ";
	$c .= " u.interests, "; 
	$c .= " u.login_name, ";
	$c .= " u.last_login, "; 
	$c .= " u.created, "; 
	$c .= " u.updated, ";
	$c .= " u.is_admin, ";
	$c .= " u.is_active, ";
	$c .= " u.must_update_pwd, ";
	$c .= " u.type_id, "; 
	$c .= " ut.name type_name, ";
	$c .= " ut.highlight_style ";
	return $c;	
}

protected function tables($includeTypes = true){

	$t = " FROM users u LEFT OUTER JOIN user_types ut ON u.type_id = ut.id ";
	return $t;
}

public function validateUser($loginName, $loginPwdCrypt){
	$q = " SELECT count(*) AS user_count FROM users u ";
	$q .= " WHERE u.login_name = '".$loginName."' ";
	$q .= " AND u.login_pwd = '".$loginPwdCrypt."' ";
	$q .= " AND u.is_active = 'yes' ";	
	return $q;
}
public function validateLoginAndEmail($loginName, $loginEmail){
	$q = " SELECT count(*) AS user_count FROM users u ";
	$q .= " WHERE u.login_name = '".$loginName."' ";
	$q .= " AND u.email = '".$loginEmail."' ";
	$q .= " AND u.is_active = 'yes' ";	
	return $q;
}

public function securityUser($loginName){
	$q = " SELECT ";
	$q .= " u.id, u.is_admin, u.is_active, u.must_update_pwd ";
	$q .= " FROM users u ";
	$q .= " WHERE u.login_name = '".$loginName."' ";	
	return $q;
}

		public function updatePass($loginName, $newPassCrypt){
			$sql = "UPDATE users u SET ";
			$sql .= " u.login_pwd = '".$newPassCrypt."', ";
			$sql .= " u.must_update_pwd = 'yes' ";
			$sql .= " WHERE u.login_name = '".$loginName."' ";
			return $sql;
		}
		

	public function updateLastLogin($loginName){
		$sql = " UPDATE users u ";
		$sql .= " SET u.last_login = CURRENT_TIMESTAMP ";
		$sql .= " WHERE u.login_name = '".$loginName."' ";	
		return $sql;
		
	}
	
	public function info($id = 0){
		$q = $this->cols();
		$q .= $this->tables(true);
		$q .= $this->whereId($id, true, 'u.id');
		return $q;
	}

	
	/*
	public function options($selectedId = 0, $disabled = 'false'){
	
	
	
	}
	*/
		
}
?>
