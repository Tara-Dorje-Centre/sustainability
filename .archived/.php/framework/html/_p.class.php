<?php
namespace html;

class _p extends _anyElement{
	protected $caption;

	public function __construct(string $caption = 'none', string $css = 'none'){
		parent::__construct('p', $css);
		$this->setCaption($caption);
	}
	protected function setCaption($caption){
		if ($caption != 'none'){
			$s = new _span('paragraph-caption');
			$s->addContent($caption);
			$this->caption = $s->print();
		} else {
			$this->caption = '';
		}
	}
	public function open(){
		$result = parent::open();
		$result .= $this->caption;
		return $result;
	}
	
	
	
}

?>
