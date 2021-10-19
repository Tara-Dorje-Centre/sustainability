<?php 
namespace framework;

interface Iecho{
public function setEchoState(bool $enabled = false);
public function echoLocale(string $f = '-use-current');
public function echoValue(bool $show = true, string $n = 'name', $v = 'value', string $f = '-use-current-');
public function echoPrint(bool $show = true, string $msg = 'message', string $f = '-use-current-');
public function echoPrintline(string $msg = 'message');
}

class _echo implements Iecho{

protected $echoEnabled = true;

protected $_myClassName = 'echo';
protected $_myFunctionName = '__construct';

public function setEchoState(bool $enabled = false){
	$this->echoEnabled = $enabled;
}
public function echoLocale(string $f = '-use-current-'){

	
	$this->_myClassName = get_class($this);
	
	if ($f != '-use-current-'){
		$this->_myFunctionName = $f;
	}
}
protected function echoVN($v = 'value', string $n = '-not-set-'){
	if ($n != '-not-set-'){
		return $n.'=['.$v.']';
	} else {
		return '['.$v.']';
	}
}
protected function echoCF(){
		return $this->_myClassName.'.'.$this->_myFunctionName.':';
}

public function echoValue(bool $show = true, string $n = 'name', $v = 'value', string $f = '-use-current-'){
	$this->echoLocale($f);
	if ($show == true){
		$this->echoPrintline($this->echoCF().$this->echoVN($v, $n));
	}
}

public function echoPrint(bool $show = true,string $msg = 'message', string $f = '-use-current-'){
	$this->echoLocale($f);
	if ($show == true){
		$this->echoPrintline($this->echoCF().$this->echoVN($msg));
	}
}
public function echoPrintline(string$msg = 'message'){
	//disable all echo printing
	//$this->echoEnabled = false;
	if ($this->echoEnabled == true){
		echo $msg.'<br />'.PHP_EOL;
	}
}
	
}
?>
