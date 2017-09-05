<?php 
namespace framework;

Interface IcontentWriter{
 	public function resetContent(bool $reset = true);
	public function addContent(string $value = '',bool $reset = false);
	public function getContent(bool $reset = true);
	public function encloseString(string $value = '', $delimiter = "'");
	public function qString(string $value = '');
	public function qqString(string $value = '');
}

abstract class _contentWriter extends _echo implements Iecho, IcontentWriter{
	private $_privateContent = '';
 	public function resetContent(bool $reset = true){
 		if ($reset == true){
			$this->_privateContent = '';
 		}
 	}
	public function addContent(string $value = '',bool $reset = false){
		$this->resetContent($reset);
 		$this->_privateContent .= $value;
 	}
	public function getContent(bool $reset = true){
 		$value = $this->_privateContent;
		$this->resetContent($reset);
 		return $value;
	}
	public function encloseString(string $value = '', $delimiter = "'"){
		return $delimiter.$value.$delimiter;
	}
	public function qString(string $value = ''){
		return $this->encloseString($value."'");
	}
	public function qqString(string $value = ''){
		return $this->encloseString($value.'"');
	}
}
 
?>
