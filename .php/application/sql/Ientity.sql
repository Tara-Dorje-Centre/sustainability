<?php
namespace application\sql;

interface IentitySQL{
	public function info($id);
	public function count();
	public function list($page = 1, $rows = 10);
	public function summary();
	public function options();
	
}

?>
