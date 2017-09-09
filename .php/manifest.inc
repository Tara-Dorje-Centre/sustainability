<?php
class systemManifest{
	public $framework = 'framework/';
	public $application = 'application/';
	public function includeFile($path,$file = 'manifest.inc'){
		include_once($path.$file);
	}
	public function includeLibraries(){
		$this->includeFile($this->framework);
		$this->includeFile($this->application);
	}
	public function initializeDb(){
		//finish standard include process 
		//by opening active global db connection
		$this->includeFile($this->framework,'_dbconnect.php');
	}
	public function initialize(){
		$this->includeLibraries();
		$this->initializeDb();
	}
}
?>
