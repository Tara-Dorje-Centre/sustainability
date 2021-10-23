<?php
namespace html\inputs;

class number extends __inputCaptioned{
use maxLength, min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-number'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		//$this->setMaxLength(8);
		$this->setStep(0.001);
		$this->setMax(999999);
		$this->setValue($value);

	}
	
}


class id extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-percent'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(1);
		$this->setMax(999999);
		$this->setMin(0);
		$this->setValue($value);

	}
	
}


class percent extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-percent'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(0.01);
		$this->setMax(1);
		$this->setMin(0);
		$this->setValue($value);

	}
	
}

class currency extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-currency'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(0.01);
		$this->setMax(99999);
		$this->setMin(0);
		$this->setValue($value);

	}
	
}



class hours extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-hours'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(0.1);
		$this->setMax(9999);
		$this->setMin(0);
		$this->setValue($value);

	}
	
}


class order extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-order'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(1);
		$this->setMax(1000);
		$this->setMin(1);
		$this->setValue($value);

	}
	
}

class priority extends __inputCaptioned{
use min, max, step;
	public function __construct($name, $value, $caption, $css = 'editing-input-priority'){
		parent::__construct('number',$name, $caption, $css);
		//setDefaultDimenions
		$this->setStep(0.1);
		$this->setMax(10);
		$this->setMin(0);
		$this->setValue($value);

	}
	
}

?>
