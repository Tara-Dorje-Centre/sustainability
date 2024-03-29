<?php
namespace html;

trait colspan{
	public function setColspan($colspan = 0){
		if ($colspan != 0){
			$this->makeAttribute('colspan',$colspan);
		}
	}
}

trait rowspan{
	public function setRowspan($rowspan = 0){
		if ($rowspan != 0){
			$this->makeAttribute('rowspan',$rowspan);
		}
	}
}
trait width{
	public function setWidth($width = 0){
		if ($width!=0){
			$this->makeAttribute('width', $width.'%');
		}
	}
	
}

trait dimensions{
	use colspan, rowspan, width;
	
	public function setDimensions($colspan = 0, $rowspan = 0, $width = 0){
		$this->setColspan($colspan);
		$this->setRowspan($rowspan);
		$this->setWidth($width);
	}

}

class _table extends _element{
	protected $tableRows = array();
	
	public function __construct($idName = 'none', $css = 'none'){
		parent::__construct('table', $idName, $css);
	}
	public function addRow(_tr &$tr){
		$this->tableRows[] = $tr;
	}
	public function makeRow($content,$css = 'none'){
		$tr = new _tr($css);
		$tr->setContent($content);
		$this->addRow($tr);
	}
	public function newRow($css = 'none'){
		$tr = new _tr($css);
		$tr->setContent($content);
		return $tr;
	}
	protected function printRows(){
		if ((isset($this->tableRows) == true) AND (count($this->tableRows) > 0)){
			foreach ($this->tableRows as $r){
				$this->addContent($r->print());
			}
			unset($this->tableRows);
		}
	}
	public function print(){
		$this->printRows();
		return parent::print();
	}
}



class _tr extends _anyElement{
	protected $rowData = array();
	public function __construct($css = 'none'){
		parent::__construct('tr', $css);
	}
	
	public function makeHeading($content,$css = 'none', $colspan = 0){
		$th = new _th($content, $css);
		$th->setColspan($colspan);
		$this->addCell($th);
	}
	public function addCell(_tableCell $tc){
		$this->rowData[] = $tc;
	}
	
	public function makeData($content,$css = 'none',$colspan = 0){
		$td = new _td($content, $css);
		$td->setColspan($colspan);
		$this->addCell($td);
	}
	
	protected function printData(){
		if ((isset($this->rowData) == true) AND (count($this->rowData) > 0)){
			foreach ($this->rowData as $c){
				$this->addContent($c->print());
			}
			unset($this->rowData);
		}
	}
	public function print(){
		$this->printData();
		return parent::print();
	}
}

abstract class _tableCell extends _anyElement{
use dimensions;
	public function __construct(string $tag, $content, string $css = 'none'){
		parent::__construct($tag, $css);
		$this->setContent($content);
	}
}

class _th extends _tableCell{
	public function __construct($content = '', string $css = 'none'){
		parent::__construct('th', $content, $css);
	}
}

class _td extends _tableCell{
	public function __construct($content = '', string $css = 'none'){
		parent::__construct('td', $content, $css);
	}
}

?>
