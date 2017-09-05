<?php
class systemManifest{
	public $htmlLib = './htmlLib/';
	public $htmlLibInputsLib = './htmlLib/inputsLib/';
	public $htmlLibCssLib = './htmlLib/cssLib/';
	public $applicationLib = './applicationLib/';
	public $frameworkLib = './frameworkLib/';
	public $entitiesLib = './entitiesLib/';

	public function includeFile($path,$file = 'manifest.inc'){
		include_once($path.$file);
	}
	public function includeLibraries(){
		$this->includeFile($this->htmlLib);
		$this->includeFile($this->htmlLibInputsLib);
		$this->includeFile($this->htmlLibCssLib);
		$this->includeFile($this->applicationLib);
		$this->includeFile($this->frameworkLib);
		$this->includeFile($this->entitiesLib);

	}
	public function initializeDb(){
		//finish standard include process 
		//by opening active global db connection
		$m->includeFile($m->frameworkLib,'_dbconnect.php');
	}
	public function initialize(){
		$this->includeLibraries();
		$this->initializeDb();
	}
}
?>
