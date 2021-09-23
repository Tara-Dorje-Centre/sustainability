<?php
namespace html;

abstract class _element{
	protected const _NONE = 'none';
	protected const _EMPTY = '';
	protected const _EMPTYATTRIBUTE = '""';
	protected const LT = '<';
	protected const GT = '>';
	protected const SL = '/';
	protected const EQ = '=';
	protected const SQ = "'";
	protected const DQ = '"';
	protected const SP = ' ';
	protected const COMMENTOPEN = '<!--';
	protected const COMMENTCLOSE = '-->';
	protected $_content = '';
	protected $_markup = '';
	protected $_attribs = '';
	protected $_tag = '';
	protected $_attributes = array();
	protected $_id = '';
	protected $_name = '';
	protected $_css = '';
	protected $_style = '';
	protected $_cData = false;

	public function __construct(string $tag, string $idName = 'none', string $css = 'none') {
		$this->setTag($tag);
		$this->setIdName($idName);
		$this->setCSS($css);
	}


	public function setContent($content = null){
	/* set contents to input, convert null to empty string*/
		if (!is_null($content)){
			$this->_content = $content;
		} else {
			$this->_content = self::_EMPTY;
		}
	}
	public function clearContent(){
		$this->_content = self::_EMPTY;
	}
	
	
	public function addContent($content = null){
	/*append non null inputs to current contents*/
		if (!is_null($content)){
			$this->_content .= $content;
		}
	}
	public function makeAttribute(string $name, string $value){
		$a = new _attribute($name,$value);
		$this->addAttribute($a);
	}
	
	public function addAttribute(_attribute $a){
		if ($a->isValid() == true){
			$this->_attributes[$a->name] = $a;
		}
	}
	public function removeAttribute(string $name){
		unset($this->attributes[$name]);
	}
	protected function buildAttributes(){
		$this->_attribs = self::_EMPTY;
		foreach ($this->_attributes as $a){
			$this->_attribs .= $a->print();
		}
		unset($this->_attributes);
	}

	protected function setTag(string $tag){
		$this->_tag = $tag;
	}

	public function setIdName(string $idName = 'none'){
		if ($idName != 'none'){
			$this->_id = $idName;
			$this->_name = $idName;
			$this->makeAttribute('id', $this->_id);
			$this->makeAttribute('name', $this->_name);
		}
	}

	public function setCSS(string $css){
		$this->_css = $css;
		$this->makeAttribute('class', $this->_css);
	}

	public function setStyle(string $style){
		$this->_style = $style;
		$this->makeAttribute('style', $this->_style);
	}

	public function setCData(bool $isCData = false){
		$this->$_cData = $isCData;
	}

	protected function start(){
		$this->buildAttributes();
		$this->_markup = self::LT.$this->_tag.$this->_attribs;
	}

	public function open(){
		$this->start();
		$this->_markup .= self::GT;
		//if cdata add after opening markup
		return $this->_markup.PHP_EOL;
	}

	protected function emptyElement(){
		$this->start();
		$this->_markup .= self::SP.self::SL.self::GT;
		return $this->_markup.PHP_EOL;
	}

	public function close(){
		//if cdata add before closing markup
		$this->_markup = self::LT.self::SL.$this->_tag.self::GT;
		return $this->_markup.PHP_EOL;
	}

	public function print(){
		$value = $this->open();
		$value .= $this->_content;
		$value .= $this->close();
		return $value;
	}
	public function printContent($content = null){
		$this->setContent($content);
		return $this->print();
	}
	
	public function commentOpen(){
		return self::COMMENTOPEN;
	}

	public function commentClose(){
		return self::COMMENTCLOSE;
	}
}
?>
