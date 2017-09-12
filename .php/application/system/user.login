<?php
namespace application\system;


class userLogin extends \framework\_contentWriter{
	private $_form = 'LOGIN';
	protected $myClassName = 'userLogin';
	private $f;
	private $s;
	private $sql;

	
	public function __construct($mode = 'LOGIN'){
		$this->_form = $mode;
		$this->f = new forms\userLoginFields();
		$this->s = new forms\userSecurityFields();
		$this->sql = new sql\UserSQL();
		//$this->links = new UserLinks();
	}

	
	
	public function getRequestArguments(){



	}
	public function resetPassword(){
		$this->f->_loginEmail->read();
		$this->f->_loginName->read();
		$loginName = $this->f->_loginName->value();
		$loginEmail = $this->f->_loginEmail->value();
		//verify that login and email exist for an active account
		if ($this->validateLoginAndEmail($loginName,$loginEmail) == true){
			//login and email are valid and account is active 
			$this->f->_loginEmail->set($loginEmail);
		
			$newPass = $this->f->_loginPassword->generate();
			$newPassCrypt = $this->obfuscate(dbEscapeString($newPass),$loginName);
		
		
			$sql = $this->sql>updatePass($loginName, $newPassCrypt);
			global $conn;
			$result = $conn->runStatement($sql);
			
			$links = new UserLinks;	
			$message = $_SESSION['site-title'].br();
			$message .= $links->formatHref($_SESSION['site-org'],$_SESSION['site-org-url']).br();
			$message .= "Profile password has been reset".br();
			$message .= "Login Name = ".$loginName.br();
			$message .= "New Password = ".$newPass.br();
			$message .= $links->formatHref($_SESSION['site-url'],$_SESSION['site-url']).br();
			$this->mailUser($message,'Password Reset');
			$_SESSION['login-messages']  = 'Profile password reset';
		} else {
			$_SESSION['login-messages'] = 'Active profile not found, contact an administrator';	
		}
	}

	private function mailUser($message = '', $subject = 'User Notification'){

		$fromAddress = ini_get('sendmail_from');
		$headers = "From: ".$fromAddress."\n";
		$headers .= "X-Mailer: PHP/".phpversion()."\n"; 
		$headers .= "MIME-Version: 1.0\n"; 
		$headers .= "Content-Type: text/html; charset=utf-8\n"; 
		$headers .= "Content-Transfer-Encoding: 8bit\n"; 
		$newSubject = $_SESSION['site-title'].':'.$subject;
		mail($this->f->email,$newSubject,$message,$headers, '-f '.$fromAddress);
	}
	
	private function obfuscate($pass,$login){
		//$salt = crypt($login);
		$hash = md5($pass.$login);
		return $hash;	
	}

	public function validateLoginAndEmail($loginName, $loginEmail){
		$valid = false;
		$sql = $this->sql->validateLoginAndEmail($loginName,$loginEmail);
		global $conn;
		
		$found = $conn->getCount($sql,'user_count');
		

		if ($found == 1){
			$valid = true;
		} 
		return $valid;
	}
	public function validate(){
	
		$this->f->read();
		$this->s->read();

			if (isset($_SESSION['logged-in']) && ($_SESSION['logged-in'] == true)){
				$this->s->_isLoggedIn->remove();
				unset($_SESSION['logged-in']);
				$this->echoPrint(true,'apparently logged in ','validate');
				$this->echoSecurity();
			}
		$this->echoPrint(true,'check submit vars ','validate');
		$this->echoPrint(true,'mode= '.$this->_form,'validate');
		
		if ($this->f->_loginSubmit->exists() == true){
			$this->echoPrint(true, 'submit login pushed','validate');
			$this->validateLogin();
		}
		if ($this->f->_resetPasswordSubmit->exists() == true){
			$this->echoPrint(true, 'reset pasword pushed','validate');
			$this->resetPassword( );
		}
}
	private function validateLogin(){
		$this->echoPrint(true, 'checking name-password','validateLogin');
		//$this->f->read();
		//$this->f->_loginName->read();
		$login = $this->f->_loginName->value();
		$pass = $this->f->_loginPassword->value();
			
		$loginPwdCrypt = $this->obfuscate($pass,$login);
		$validUser = false;
		$sql = $this->sql->validateUser($login, $loginPwdCrypt);
		global $conn;
		$found = $conn->getCount($sql,'user_count');
	
		
		$this->echoValue(true, 'foundUser?', $found, 'validateLogin');
	

		//FORCE LOGIN SUCCESS TO SETUP FIRST USER
		//then comment this line
		//printLine('forcing login');
		$found = 1;
		$this->echoValue(true, 'foundUser', $found, 'DEVELOPMENT Forcing result validateLogin');
		if ($found == 1){
		
		
		
			$validUser = true;
			$this->echoPrint(true,'valid login.updatingLastLogin');
			$this->updateLastLogin($login);
			$this->echoPrint(true,'valid login.setting session security');
			$this->setSecurity($login);
			
			$_SESSION['logged-in'] = true;
			//$_SESSION['client-time-zone'] = $_POST['client-time-zone'];
			

			//$this->f->_sLoginMessages->write();
			
		} else {
			//$_SESSION['login-messages'] = 'Could not validate login';
			$this->s->remove();
			$this->s->_loginMessages->set('Could not validate login');
			$this->f->_loginMessages->write();
			
		}
		
		return $validUser;
		
	}
	
	
	private function setSecurity($loginName){
		$this->echoPrint(true, 'begin','setSecurity');
		$sql = $this->sql->securityUser($loginName);
		global $conn;
		$result = $conn->getResult($sql);
		if($result){
			$this->echoPrint(true, 'found user profile','setSecurity');
	  		while ($row = $result->fetch_assoc())
			{
				//$this->f->fetchSecurity($row);
				$this->s->_currentUserId->fetch($row);
    			$this->s->_mustUpdatePwd->fetch($row);
    			$this->s->_isAdmin->fetch($row);
			}
		$result->close();
		}
		$this->s->_currentUser->set($loginName);
		$this->s->_clientTimeZone->set($this->f->_clientTimeZone->value());
		$this->s->_loginMessages->set('Login Success');
		$this->echoPrint(true, 'writing SESSION security fields','setSecurity');
		$this->s->setEnvironment('SESSION');
		$this->s->write();
		$this->echoSecurity();

	}
	private function echoSecurity(){
		$this->s->setEnvironment('SESSION');
		$this->s->read();
		$this->echoValue(true, 'currentUser?', $this->s->_currentUser->value());
		$this->echoValue(true, 'currentUserId?', $this->s->_currentUserId->value());
		$this->echoValue(true, 'isAdmin?', $this->s->_isAdmin->value());
		$this->echoValue(true, 'mustUpdatePassword?', $this->s->_mustUpdatePwd->value());
		$this->echoValue(true, 'clientTimeZone?', $this->s->_clientTimeZone->value());
		$this->echoValue(true, 'loginMessages?', $this->s->_loginMessages->value());
	}
	private function updateLastLogin(string $loginName = 'unknkownId'){
		
		$sql = $this->sql->updateLastLogin($loginName);
		global $conn;
		$result = $conn->runStatement($sql);
		
	}
	
	public function loginForm(){
		$this->echoPrint(true, 'building login form','loginForm');
		$message = 'Please Login...';
		$message .= $this->getLoginMessages();
		$entity = 'site-login';
		$this->f->setEntity($entity);



		$login = new \application\forms\inputForm('sys_Login.php','Login','LOGIN',$entity,true);
		
		
		$login->required->input($this->f->_loginName);
		$login->required->input($this->f->_loginPassword);

		$login->optional->input($this->f->_loginEmail);
		$login->optional->button($this->f->_resetPasswordSubmit);

		$msg = 'To reset password, enter your login name and profile email.'.\html\br();
		$msg .= 'To register, please contact an administrator.';
		$login->optional->addContent('Lost Password:'.$msg);
		$this->f->_clientTimeZone->set('0');
		$login->hidden->inputHidden($this->f->_clientTimeZone);
		//= getHiddenInput('client-time-zone','');
		$login->submit->button($this->f->_loginSubmit);

		
		$login->submit->addContent('+'.$_SESSION['site-login-notice']);

		$this->echoPrint(true, 'returning login form','loginForm');
		return $login->print();		
	}
	
	private function getLoginMessages(){
		if ($this->s->_loginMessages->exists()){
			$this->s->_loginMessages->read();
			$messages = $this->s->_loginMessages->value();
			$this->s->_loginMessages->remove();
		} else {
			$messages = '';
		}
		
		return $messages;
	}
	
	
} 






?>
