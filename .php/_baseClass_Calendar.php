<?php 
include_once("_includes.php");
class _Calendar{
	private $title;
	private $year;
	private $month;
	private $monthName;
	private $firstDate;
	private $firstDay = 0;
	private $startingBlanks = 0;
	private $daysInMonth;
	private $lastDate;
	private $lastDay = 0;
	private $closingBlanks = 0;
	private $days = array();
	private $nextLink;
	private $prevLink;
	private $content;
	private $currentYear;
	private $currentMonth;
	private $currentDay;
	private $isCurrent = false;
	
	public function __construct($year, $month, $title = null){
		$this->year = $year;
		$this->month = $month;
		$this->title = $title;
		$this->setLayout();
		$this->setLinks();
		$this->setCurrentDay();		
	}
	
	private function setCurrentDay(){
		global $sessionTime;
		$this->currentYear = getTimestampYear($sessionTime);
		$this->currentMonth = getTimestampMonth($sessionTime,'YES');
		$this->currentDay = getTimestampDay($sessionTime,'YES');
		if ($this->currentYear == $this->year && $this->currentMonth == ltrim($this->month,'0')){
			$this->isCurrent = true;
		} else {
			$this->isCurrent = false;
		}
	}
	
	public function setLinks($nextLink = 'none', $prevLink = 'none'){
		$this->nextLink = $nextLink;
		$this->prevLink = $prevLink;
	}
	
	private function setLayout(){
		//default timezone for mktime and date function calls
		//prevents server warning that timezone is not set
		date_default_timezone_set('UTC');
		//calculate firstDayOfMonth
		$this->firstDate = mktime(0,0,0,$this->month, 1, $this->year) ; 

		//calculate dayofweek for first day
		//0 = sunday ... 6 = saturday
		$this->firstDay = date('w', $this->firstDate); 
		$this->monthName = date('F',$this->firstDate);
		//determine blank spaces before first day based on weekstartingday
		$this->startingBlanks = $this->firstDay;
		
		//calculate days in month
		 $this->daysInMonth = cal_days_in_month(0, $this->month, $this->year);
		//calculate lastDayOfMonth		 
		 $this->lastDate = mktime(0,0,0,$this->month,$this->daysInMonth,$this->year);
		
		//calculate dayofweek for last day		 
		//0 = sunday ... 6 = saturday
		$this->lastDay = date('w', $this->lastDate); 

		//determine blank spaces after last day based on weekstartingday
		$this->closingBlanks = 6 - $this->lastDay;
		
		//populate array of days
		for ($i = 0; $i <= $this->daysInMonth; $i++){
			$this->days[$i] = spacer();	
		}
	}
	
	public function addItem($dayIndex, $text, $itemStyle = 'calendar-item'){
		//add an item to internal array of day contents
		//2013-01-24
		//$date = substr($timestamp,8,2);			
		$this->days[$dayIndex] .= openDiv('dayItem',$itemStyle).$text.closeDiv();		
	}

	public function addItemByTimestamp($timestamp, $text, $itemStyle = 'calendar-item'){
		//add an item to internal array of day contents
		//2013-01-24
		//$date = substr($timestamp,8,2);		
		$dayIndex = ltrim(getTimestampDay($timestamp),'0');
		$this->addItem($dayIndex,$text, $itemStyle);	
	}

	private function isDayToday($dayIndex){
		$isToday = false;		
		if ($this->isCurrent == true && $dayIndex == $this->currentDay){
			$isToday = true;
		}
		return $isToday;		
	}

	private function getDayContent($dayIndex){
		$text = openDiv('dayNumber','calendar-date').$dayIndex.closeDiv();		
		$text .= openDiv('dayContent','calendar-details');
		$text .= $this->days[$dayIndex];
		$text .= closeDiv();
		return $text;
	}
	private function getLinkPrev(){
		if ($this->prevLink != 'none'){
			$content = openDiv('CalendarPrev','calendar-link-prev');	
			$content .= $this->prevLink;
			$content .= closeDiv();	
		} else {
			$content = '';
		}
		return $content;
	}
	private function getLinkNext(){
		if ($this->nextLink != 'none'){
			$content = openDiv('CalendarNext','calendar-link-next');	
			$content .= $this->nextLink;
			$content .= closeDiv();	
		} else {
			$content = '';
		}
		return $content;
	}

	public function buildCalendar(){
		//loop through internal array of days, printing table rows/data and content
		$daysOfWeek = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
		$calendar = openDiv('calendarContainer','calendar-container');
		$calendar .= openTable('calendar','calendar-table');
		$text = $this->getLinkPrev();
		$text .= $this->monthName.spacer(4).$this->year.spacer(4).$this->title;
		$text .= $this->getLinkNext();
		$title = wrapTh($text,'calendar-title',7);
		$calendar .= wrapTr($title);
		$h = '';
		for($i = 0; $i <= 6; $i++){
			$h .= wrapTh($daysOfWeek[$i],'calendar-title-day');	
		}
		$calendar .= wrapTr($h);
		$week = '';
		//add padding cells to table
		$dayCount = 1;
		if ($this->startingBlanks != 0){
			$week = wrapTd(spacer(),0,'calendar-td-blanks',$this->startingBlanks);
			$dayCount += $this->startingBlanks;
		}
		//add days of month, break rows every seven
		//sets the first day of the month to 1 
		$dayNumber = 1;

		//count up the days, untill we've done all of them in the month
		while ($dayNumber <= $this->daysInMonth) 
		{ 
			if ($this->isDayToday($dayNumber) == true){
				$dayStyle = 'calendar-td-today';
			} else {
				$dayStyle = 'calendar-td';
			}
			$week .= wrapTd($this->getDayContent($dayNumber),0,$dayStyle);
 
			$dayNumber++; 
			$dayCount++;
			//Make sure we start a new row every week
			if ($dayCount > 7){
				$calendar .= wrapTr($week);
				$dayCount = 1;
				$week = '';
			}

 		} 
		//add closing cells to table
		if ($this->closingBlanks != 0){
			$week .= wrapTd(spacer(),0,'calendar-td-blanks',$this->closingBlanks);
		}	
		$calendar .= wrapTr($week);
		$calendar .= closeTable();
		$calendar .= closeDiv();
		return $calendar;
	}
		
}
?>
