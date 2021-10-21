<?php
namespace html\inputs;

trait valueAttribute{
	public function setValue($value){
		if (!is_null($value)){
			$this->makeAttribute('value',$value);
		}
	}
}

trait valueContent{
	public function setValue($value){
		
		if (!is_null($value)){
			$this->setContent($value);
		}
	}
}

trait caption{
	protected $_caption = '';
	protected $_showCaption = false;
	public function setCaption(string $caption = 'none'){
		if ($caption != 'none'){
		$this->_caption = $caption;
		$this->_showCaption = true;
		};
	}
	public function printCaption(){
		if ($this->_showCaption == true){
			$c = new \html\_div('none','input-caption');
			$c->setContent($this->_caption);
			$value = $c->print();
		} else {
			$value = '';
		}
		return $value;
	}

}

trait validation{
	protected $_validation = '';
	protected $_showValidation = false;

	public function setValidation(string $validation = 'none'){
		if ($validation != 'none'){
		$this->_validation = $validation;
		$this->_showValidation = true;
		};
	}
	public function printValidation(){
		if ($this->_showValidation == true){
			$d = new \html\_div('none','input-validation');
			$d->addContent($this->_validation);
			$value = $d->print();
		} else {
			$value = '';
		}
		return $value;
	}

}

trait tooltip{
	public function setTooltip(string $tooltip = 'none'){
		$this->makeAttribute('title',$tooltip);
	}
}

trait disabled{
	public function setDisabled($disabled = 'false'){
		if ($disabled != 'false'){
			$this->makeAttribute('disabled',$disabled);
		}
	}
}

trait size{
	public function setSize($size = 0){
		$this->makeAttribute('size',$size);
	}
}



trait max{
	public function setMax($max = 0){
		$this->makeAttribute('max',$max);
	}
}

trait min{
	public function setMin($min = 0){
		$this->makeAttribute('min',$min);
	}
}

trait step{
	public function setStep($step = 0){
		$this->makeAttribute('step',$step);
	}
}




trait rows{
	public function setRows($rows = 0,$cols = 0){
		$this->makeAttribute('rows',$rows);
		$this->makeAttribute('cols',$cols);
	}
}

trait maxLength{
	public function setMaxlength($maxLength = 0){
		$this->makeAttribute('maxlength',$maxLength);
	}
}

trait dimensionsSize{
use size, maxLength;
	public function setDimensions($maxLength = 0, $size = 0){
		$this->setMaxLength($maxLength);
		$this->setSize($size);
	}
}
trait dimensionsRows{
use rows, maxLength;
	public function setDimensions($maxLength = 0,$rows = 0, $columns = 0){
		$this->setMaxLength($maxLength);
		$this->setRows($rows,$columns);
	}
}


?>
