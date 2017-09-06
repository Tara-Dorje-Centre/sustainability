<?php
class systemManifest{
	public $framework = './framework/';
	public function includeFile($path,$file = 'manifest.inc'){
		include_once($path.$file);
	}
	public function includeLibraries(){
		$this->includeFile($this->framework);
	}
	public function initializeDb(){
		//finish standard include process 
		//by opening active global db connection
		$m->includeFile($m->framework,'_dbconnect.php');
	}
	public function initialize(){
		$this->includeLibraries();
		$this->initializeDb();
	}
}
?>
