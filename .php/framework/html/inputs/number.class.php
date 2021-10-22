<?php
namespace html\inputs;

class number extends __inputCaptioned{
use maxLength, min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-number'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setMaxLength(8);
		$this->setMax(999999);
		$this->setValue($value);

	}
	
}

class id extends number{

}

class percent extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-number'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(0.01);
		$this->setMax(1);
		$this->setMin(0);
		$this->setValue($value);

	}
	
}


class hours extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-number'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(0.1);
		$this->setMax(9999);
		$this->setMin(0);
		$this->setValue($value);

	}
	
}


class priority extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-number'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(0.1);
		$this->setMax(10);
		$this->setMin(0);
		$this->setValue($value);

	}
	
}

?>
