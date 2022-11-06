<?php
namespace application;


  
	function mailUser($message = '', $subject = 'User Notification'){

		$fromAddress = ini_get('sendmail_from');
		$headers = "From: ".$fromAddress."\n";
		$headers .= "X-Mailer: PHP/".phpversion()."\n"; 
		$headers .= "MIME-Version: 1.0\n"; 
		$headers .= "Content-Type: text/html; charset=utf-8\n"; 
		$headers .= "Content-Transfer-Encoding: 8bit\n"; 
		$newSubject = $_SESSION['site-title'].':'.$subject;
		echo 'SYSTEM EMAIL, SUBJECT: '.$subject.' MESSAGE: '.$message;
		//mail($this->f->email,$newSubject,$message,$headers, '-f '.$fromAddress);
	}
	


?>
