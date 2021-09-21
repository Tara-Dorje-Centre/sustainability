<?php
namespace application\links;

class entityTypeRequest extends entityRequest{}

class entityTypeLinks extends entityLinks
implements IentityLinks{

	public function setRequest(){
		$this->request = new entityTypeRequest($this->entityContext);
	}
		
}


?>
