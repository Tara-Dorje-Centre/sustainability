<?php
require_once("_formFunctions.php");
require_once("_htmlFunctions.php");
require_once("_baseClass_Links.php");
require_once("_baseClass_Calendar.php");

class UserLinks extends _Links {
	public function __construct($menuType = 'DIV',$styleBase = 'menu'){
		parent::__construct($menuType,$styleBase);
	}
	public function listingHref($userTypeId = 0, $caption = 'AllUsers'){
		$link = $this->listing($userTypeId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	private function detailHref($pageAction = 'VIEW', $userId = 0, $caption = 'User'){
		$link = $this->detail($pageAction,$userId);
		$href = $this->formatHref($caption,$link);
		return $href;	
	}
	
	public function listing($userTypeId = 0){
		$link = 'sys_User_List.php';
		$link .= '?userTypeId='.$userTypeId;
		return $link;
	}
	
	public function listingPaged($baseLink,$found, $resultPage, $perPage){
		$l = $baseLink.'&resultPageUsers=';
		$ls = $this->getPagedLinks($l, $found,$perPage,$resultPage);
		return $ls;
	}
		
	public function detail($pageAction = 'VIEW', $userId = 0, $userTypeId = 0){
		$link = 'sys_User_Detail.php?pageAction='.$pageAction;
		if ($userId != 0){
			$link .= '&userId='.$userId;
		}
		if ($userTypeId != 0){
			$link .= '&userTypeId='.$userTypeId;
		}

		return $link;
	}
	public function detailAddHref($caption = '+User',$userTypeId = 0){
		$l = $this->detailHref('ADD',0,$caption,$userTypeId);
		return $l;	
	}
	public function detailViewHref($userId,$caption = 'ViewUser'){
		$l = $this->detailHref('VIEW',$userId,$caption);
		return $l;	
	}
	public function detailEditHref($userId,$caption = 'EditUser'){
		$l = $this->detailHref('EDIT',$userId,$caption);
		return $l;	
	}
	public function detailViewEditHref($userId = 0, $viewCaption = 'ViewUser'){
		
		if ($userId != 0){
			$links = $this->detailViewHref($userId,$viewCaption);
			$links .= $this->detailEditHref($userId,'#');
		} else {
			$links = $this->listingHref();
		}
		return $links;
	}	
}
//end class UserLinks
class UserList{
	public $found = 0;
	public $resultPage = 1;
	public $perPage = 10;
	public $userTypeId = 0;
	private $sql;
	
	public function __construct(){
		$this->sql = new UserSQL;
	}
	
	public function setDetails($userTypeId = 0, $resultPage = 1, $resultsPerPage = 10){
		$this->resultPage = $resultPage;
		$this->perPage = $resultsPerPage;
		$this->userTypeId = $userTypeId;
		$this->setFoundCount();
	}
	
	private function pageTitle(){
		$title = openDiv('section-heading-title','none');
		$title .= 'User Profiles';
		$title .= closeDiv();
		return $title;	
	}

	private function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		
		$userL = new UserLinks($menuType,$menuStyle);
		$menuL = new MenuLinks($menuType,$menuStyle);
		
		$menu = $userL->openMenu('section-heading-links');
		$menu .= $menuL->linkReference();
		$menu .= $userL->resetMenu();
		if ($_SESSION['is-admin'] == 'yes'){
			//only allow admins to add users
			$menu .= $userL->detailAddHref();
		}
		$menu .= $userL->listingHref();
		
		$menu .= $userL->closeMenu();
		return $menu;			
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}	
	
	private function setFoundCount(){
		$sql = $this->sql->countUsers($this->userTypeId);
		$this->found = dbGetCount($sql, 'total_users');
	}	
	
	public function printPage(){
		
		$heading = $this->getPageHeading();
		$details = $this->getPageDetails();

		$site = new _SiteTemplate;
		$site->setSiteTemplateDetails($heading, $details);
		$site->printSite();
		
	}

	public function getPageDetails(){
	
		$details = $this->getListing();
		return $details;
		
	}
	
	
	public function getListing($pagingBaseLink = 'USE_LISTING'){
		
		$s = new UserSQL;
		$sql = $s->listUsers($this->userTypeId,$this->resultPage,$this->perPage);

		$cl = new UserLinks;
		if ($pagingBaseLink == 'USE_LISTING'){
			$base = $cl->listing($this->userTypeId);
		} else { 
			$base = $pagingBaseLink;
		}
		$pagingLinks = $cl->listingPaged($base, $this->found,$this->resultPage,$this->perPage);
		$u = new User;
		$u->setDetails(0,0,'ADD');
		$quickEdit = $u->editForm();
		$list = openDisplayList('users','Users',$pagingLinks,$quickEdit);
		
		$heading = wrapTh('Login Name');
		$heading .= wrapTh('Profile Type');
		$heading .= wrapTh('First Name');
		$heading .= wrapTh('Last Name');
		$heading .= wrapTh('Focus');
		$heading .= wrapTh('Interests');
		$heading .= wrapTh('Last Login');
		
		$list .= wrapTr($heading);

		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{	
			$u = new User;
			$u->id = $row["id"];
			$u->nameFirst = ($row["name_first"]); 
			$u->nameLast = ($row["name_last"]); 
			$u->email = ($row["email"]); 
			$u->focus = ($row["focus"]);
			$u->interests = ($row["interests"]);
			$u->loginName = ($row["login_name"]);
			$u->lastLogin = $row["last_login"]; 
			$u->created = $row["created"]; 
			$u->typeId = $row["type_id"];
			$u->typeName = $row["type_name"];
			$u->cssHighlight = $row["highlight_style"];
			
			$u->formatForDisplay();
			
			if ($_SESSION['is-admin'] == 'yes' || $_SESSION['user-id'] == $u->id){
				$link = $cl->detailViewEditHref($u->id,$u->loginName);
			} else {
				$link = $cl->detailViewHref($u->id, $u->loginName);
			}
			$detail = wrapTd($link);
			$detail .= wrapTd($u->typeName);
			$detail .=  wrapTd($u->nameFirst);			
			$detail .=  wrapTd($u->nameLast);		
			$detail .=  wrapTd($u->focus);		
			$detail .=  wrapTd($u->interests);		
			$detail .=  wrapTd($u->lastLogin);		
			
			//css will be based on user type
			if ($u->cssHighlight <> '' && !is_null($u->cssHighlight)){
				$cssRow = $u->cssHighlight;
			} else {
				$cssRow = 'none';
			}
			$list .=  wrapTr($detail,$cssRow);
		}
		
		$result->close();
		}

		$list .= closeDisplayList();

		return $list;
		
	}
}
//end class UserList
class User {
    public $id = 0; 
    public $typeId = 0; 
	public $typeName;
	public $cssHighlight;
    public $loginName;
	private $loginPwd;
    public $nameFirst;
    public $nameLast;
	public $email;
	public $interests;
	public $focus;
	public $lastLogin;
	public $created;	 
	public $updated;
	public $isAdmin = 'no';
	public $isActive = 'no';
	public $mustUpdatePwd = 'no';
	// property to support edit/view/add mode of calling page
    public $pageMode;
	public $validationMessages;
	private $sql;
	
	public function __construct(){
		$this->sql = new UserSQL;
	}
	
	public function setDetails($userId, $userTypeId, $inputMode){
		$this->pageMode = $inputMode;
		$this->id = $userId;
		$this->typeId = $userTypeId;

		$sql = $this->sql->infoUser($this->id);
		
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{
			$this->nameFirst = ($row["name_first"]); 
			$this->nameLast = ($row["name_last"]); 
			$this->email = ($row["email"]); 
			$this->focus = ($row["focus"]);
			$this->interests = ($row["interests"]);
			$this->loginName = ($row["login_name"]);
			$this->lastLogin = $row["last_login"]; 
			$this->created = $row["created"]; 
			$this->updated = $row["updated"];
			$this->isAdmin = $row["is_admin"];
			$this->isActive = $row["is_active"];
			$this->mustUpdatePwd = $row["must_update_pwd"];
			$this->typeId = $row["type_id"];
			$this->typeName = $row["type_name"];
			$this->cssHighlight = $row["highlight_style"];
		}

		$result->close();
		}
		
				
	}	
		
	private function pageTitle(){
		$heading = openDiv('section-heading-title');
		if ($this->pageMode != 'ADD'){
			$heading .= $this->loginName;
			if ($_SESSION['user-id'] == $this->id && $_SESSION['must-update-pwd'] == 'yes'){
				$heading .= br().'Please update your profile password.';
			}

		} else {
			$heading .= 'Add User Profile';
		}
		$heading .= closeDiv();		
		return $heading;
	}
	
	
	private function pageMenu(){
		$menuType = 'LIST';
		$menuStyle = 'menu';
		$userL = new UserLinks($menuType,$menuStyle);		
		$menuL = new MenuLinks($menuType,$menuStyle);
		
		$menu = $userL->openMenu('section-heading-links');
		$menu .= $menuL->linkReference();
		$menu .= $userL->resetMenu();
		if ($this->pageMode == 'VIEW'){
			if ($_SESSION['is-admin'] == 'yes' || $_SESSION['user-id'] == $this->id){
				//user is admin or self, show edit link
				$menu .= $userL->detailEditHref($this->id);
			}
		} elseif ($this->pageMode == 'EDIT'){
			$menu .= $userL->detailViewHref($this->id);
		}
		$menu .= $userL->listingHref();
		
		$menu .= $userL->closeMenu();
		return $menu;
	}
	
	public function getPageHeading(){
		$heading = $this->pageTitle();
		$heading .= $this->pageMenu();
		return $heading;
	}
	
	public function formatForDisplay(){
		$this->focus = displayLines($this->focus); 		
		$this->interests = displayLines($this->interests); 
	}

	public function printPage(){
		
		$heading = $this->getPageHeading();
		$details = $this->getPageDetails();

		$site = new _SiteTemplate;
		$site->setSiteTemplateDetails($heading, $details);
		$site->printSite();
		
	}
	
	public function getPageDetails(){			
		if ($_SESSION['is-admin'] == 'yes'){
			//is admin allow edit others and add users
		} else {
			//not admin allow edit self only
			if ($this->pageMode == 'ADD'){
				//non admin trying to add a user
				//revert to viewing self
				$this->setDetails($_SESSION['user-id'],0,'VIEW');
			} elseif ($this->pageMode == 'EDIT' && $_SESSION['user-id'] == $this->id){
				//editing self, ok
			} else {
				$this->pageMode = 'VIEW';
			}	 
		}

		if ($this->pageMode == 'EDIT' or $this->pageMode == 'ADD'){
			$details = $this->editForm();
		} else {
			$details = $this->display();
		}
		return $details;
		
	}
	
	public function display(){
		$this->formatForDisplay();
		$detail = openDisplayDetails('user','User Profile Details');		

		$detail .= captionedParagraph('login-name', 'Login Name', $this->loginName);
		$detail .= captionedParagraph('name-first', 'First Name', $this->nameFirst);
		$detail .= captionedParagraph('name-last', 'Last Name', $this->nameLast);
		$detail .= captionedParagraph('email', 'Email Address', $this->email);
		$detail .= captionedParagraph('type-name', 'Profile Type', $this->typeName);
		$detail .= captionedParagraph('focus', 'Focus', $this->focus);
		$detail .= captionedParagraph('interests', 'Interests', $this->interests);
		$detail .= captionedParagraph('last-login', 'Last Login', $this->lastLogin);
		
		$detail .= captionedParagraph('is-active', 'Active Profile', $this->isActive);
		$detail .= captionedParagraph('is-admin','Administrator', $this->isAdmin);
		$detail .= captionedParagraph('must-update-pwd','Must Update Password', $this->mustUpdatePwd);

		if ($_SESSION['is-admin'] == 'yes' && $_SESSION['user-id'] != $this->id){		
			$detail .= $this->requestPasswordResetForm();
		}
		$detail .= closeDisplayDetails();
		return $detail;
	}

	private function generatePassword($length = 11) {
		$chars = '.!%^&*#@';
		$clen = mb_strlen($chars);
		$base = md5(microtime());
		$password = substr(base_convert(substr($base,1),16,36),0,$length);
		$len = mb_strlen($password);
		for($i = 0;$i < $len;$i++)
		{
			if(rand(0,1) && 1){
				$password[$i] = strtoupper($password[$i]);
			} elseif(rand(0,2) && 1 && $clen){
				$password[$i] = $chars[rand(0,$clen - 1)];
			}
		}
		return $password;
	}
	
	
	
	public function resetPassword($loginName, $loginEmail){
		
		//verify that login and email exist for an active account
		if ($this->validateLoginAndEmail($loginName,$loginEmail) == true){
			//login and email are valid and account is active 
			$this->email = $loginEmail;
		
			$newPass = $this->generatePassword();
			$newPassCrypt = $this->obfuscate(mysql_real_escape_string($newPass),$loginName);
		
		
			$sql = "UPDATE users u SET ";
			$sql .= " u.login_pwd = '".$newPassCrypt."', ";
			$sql .= " u.must_update_pwd = 'yes' ";
			$sql .= " WHERE u.login_name = '".$loginName."' ";
			
			$result = dbRunSQL($sql);
			
			$links = new UserLinks;	
			$message = $_SESSION['site-title'].br();
			$message .= $links->formatHref($_SESSION['site-org'],$_SESSION['site-org-url']).br();
			$message .= "Profile password has been reset".br();
			$message .= "Login Name = ".$loginName.br();
			$message .= "New Password = ".$newPass.br();
			$message .= $links->formatHref($_SESSION['site-url'],$_SESSION['site-url']).br();
			$this->mailUser($message,'Password Reset');
			$_SESSION['login-messages'] = 'Profile password reset';
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
		mail($this->email,$newSubject,$message,$headers, '-f '.$fromAddress);
	}
	
	private function obfuscate($pass,$login){
		//$salt = crypt($login);
		$hash = md5($pass.$login);
		return $hash;	
	}

	public function validateLoginAndEmail($loginName, $loginEmail){
		$validLoginEmail = false;
		$sql = $this->sql->validateLoginAndEmail($loginName,$loginEmail);
	
		$result = dbGetResult($sql);
		if($result){
	  	while ($row = $result->fetch_assoc())
		{
			$found = $row["user_count"];  
		}
		$result->close;
		}

		if ($found == 1){
			$validLoginEmail = true;
		} 
		return $validLoginEmail;
	}
	
	public function validateLogin($loginName, $loginPwd){
		$loginPwdCrypt = $this->obfuscate($loginPwd,$loginName);
		$validUser = false;
		$sql = $this->sql->validateUser($loginName, $loginPwdCrypt);
		
		$result = dbGetResult($sql);
		if($result){
	  		while ($row = $result->fetch_assoc())
			{
				$found = $row["user_count"];  
			}
		
		$result->close();
		}
		
		
		//FORCE LOGIN SUCCESS TO SETUP FIRST USER
		//then comment this line
		//printLine('forcing login');
		//$found = 1;

		if ($found == 1){
			$validUser = true;
			$this->updateLastLogin($loginName);
			$this->setSecurity($loginName);
			$_SESSION['login-messages'] = '';
		} else {
			$_SESSION['login-messages'] = 'Could not validate login';
		}
		
		return $validUser;
		
	}
	
	
	private function setSecurity($loginName){
		$sql = $this->sql->securityUser($loginName);

		$result = dbGetResult($sql);
		if($result){
	  		while ($row = $result->fetch_assoc())
			{
				$_SESSION['user-id'] = $row["id"];
				$_SESSION['is-admin'] = $row["is_admin"];  
				$_SESSION['must-update-pwd'] = $row["must_update_pwd"];
			}
		$result->close();
		}
			
	}
	
	private function updateLastLogin($loginName){
		
		$sql = " UPDATE users u ";
		$sql .= " SET u.last_login = CURRENT_TIMESTAMP ";
		$sql .= " WHERE u.login_name = '".$loginName."' ";	
		
		$result = 
		dbRunSQL($sql);
		
	}
	
	private function setAddRecordDefaults(){
		$this->isAdmin = 'no';
		$this->mustUpdatePwd = 'yes';
		$this->isActive = 'yes';
	}
	
	public function loginForm(){
		
		$message = 'Please Login...';
		$message .= $this->getLoginMessages();
		$entity = 'user-type';
		$c = new ProjectTypeLinks;
		$contextMenu = $c->formatToggleLink('formOptional','Lost Password');
		$form = openEditForm('site-login', $message, 'sys_Login.php',$contextMenu);

		$fields = inputFieldUser($entity,$this->loginName,'login-name','Login Name');

		$input = getPasswordInput('login-pwd', 10, 50);
		$fields .= captionedInput('Password',$input);

		//end required fields
		$formRequired = $fields;
		//formOptional

		$fields = inputFieldText($entity,$this->email,'login-email','Login Email',50,255);
		$fields .= getLoginLogoutButton('Reset Password','submit-pwd-reset');		
		$input = 'To reset password, enter your login name and profile email.'.br();
		$input .= 'If your email is not registered, please contact an administrator to setup a profile.';
		$fields .= captionedInput('Lost Password Instructions',$input);

	
		//end optional fields
		$formOptional = $fields;
		
		$hidden = getHiddenInput('client-time-zone','');
		$input = getLoginLogoutButton('Login','submit-login');
		$formSubmit = $hidden.$input;
			

		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);

		$form .= openDiv('site-login-notices');
		$form .= $_SESSION['site-login-notice'];
		$form .= closeDiv();

		return $form;			
	}
	
	private function getLoginMessages(){
		if (isset($_SESSION['login-messages']) && $_SESSION['login-messages'] != ''){
			$messages = $_SESSION['login-messages'].br();
			unset($_SESSION['login-messages']);
		} else {
			$messages = '';
		}
		return $messages;
	}
	
	public function requestPasswordResetForm(){
		
		$form = openForm('user-password-reset','sys_User_Save.php','none');
		$hidden = getHiddenInput('userId',$this->id);
		$hidden .= getHiddenInput('login-email',$this->email);	
		$hidden .= getHiddenInput('login-name', $this->loginName);
		$button = getSubmitButton('Request Reset','submit-pwd-reset');
		$messages = $this->getLoginMessages();
		$form .= $messages.$button.$hidden;
		$form .= closeForm();		
		return $form;
		
	}

	public function editForm(){
		if ($this->pageMode == 'ADD'){
			$this->setAddRecordDefaults();
			$legend = 'Add Profile';
		} else {
			$legend = 'Edit Profile';
		}

		$entity = 'user';
		$c = new ProjectLinks;
		$contextMenu = $c->formatToggleLink('formOptional','+Options');
		
		$form = openEditForm($entity,$legend,'sys_User_Save.php',$contextMenu);

		if ($this->pageMode == 'ADD'){
			$input = getTextInput('loginName', $this->loginName, 20,50,'Login Name','false');
			$messages = @$this->validationMessages['login'];			
		} else {
			$input = $this->loginName.getHiddenInput('loginName',$this->loginName);
			$messages = '(cannot be changed)';
		}
		$fields = captionedInput('Login Name '.$messages,$input);



		$input = getPasswordInput('loginPwd', 10,20);
		$passwordGroup = captionedInput('Password',$input);
		
		$input = getPasswordInput('loginPwdConfirm', 10,20,'Confirm Password');
		$messages = @$this->validationMessages['password'];
		$passwordGroup .= captionedInput('Password Confirm '.$messages,$input);
		$fields .= wrapDivFieldGrouping($entity,$passwordGroup);

		$input = getTextInput('email', $this->email, 50,255);
		$messages = @$this->validationMessages['email'];
		$fields .= captionedInput('Email Address '.$messages,$input);


				
		//end required fields
		$formRequired = $fields;		
		
		//formOptional
		$fields = inputFieldName($entity,$this->nameFirst,'nameFirst','First Name');
		
		$fields .= inputFieldName($entity,$this->nameLast,'nameLast','Last Name');


		$m = new UserType;
		$select = $m->getUserTypeSelectList($this->typeId,'typeId','false');
		$fields .= captionedInput('User Type',$select);
		

		$fields .= inputFieldComments($entity,$this->focus,'focus','Focus Areas',1000);
		
		$fields .= inputFieldComments($entity,$this->interests,'interests','Interests',1000);
		
		
		if ($_SESSION['is-admin'] == 'yes' && $_SESSION['user-id'] != $this->id){
			//if admin and not editing self allow setting admin
			//prevents admins from disabling their own accounts
			$input1 = getSelectYesNo('isAdmin',$this->isAdmin,'Is Administrator','false');
			$input2 = getSelectYesNo('isActive',$this->isActive,'Is Active','false');
			$input3 = getSelectYesNo('mustUpdatePwd',$this->mustUpdatePwd,'Must Update Password','false');
		} else {
			$input1 = $this->isAdmin.getHiddenInput('isAdmin',$this->isAdmin);
			$input2 = $this->isActive.getHiddenInput('isActive',$this->isActive);
			$input3 = $this->mustUpdatePwd.getHiddenInput('mustUpdatePwd','no');
		}				
		$input = captionedInput('Administrator',$input1);
		$input .= captionedInput('Active Profile',$input2);
		$input .= captionedInput('Must Update Password', $input3);
		$fields .= captionedInput('Profile Settings',$input);
		
		//end optional fields
		$formOptional = $fields;
		
		
		$hidden = getHiddenInput('mode', $this->pageMode);
		$hidden .= getHiddenInput('userId', $this->id);

		$input = getSaveChangesResetButtons();
		$formSubmit = $hidden.$input;

		$form .= closeEditForm($entity,$formRequired,$formOptional,$formSubmit);		
		return $form;
	}
	
	
	public function collectPostValues(){

		$this->id = $_POST['userId'];
		$this->typeId = $_POST['typeId'];
		
		$this->nameFirst = dbEscapeString($_POST['nameFirst']); 
		$this->nameLast = dbEscapeString($_POST['nameLast']); 
		$this->email = dbEscapeString($_POST['email']); 
		$this->focus = dbEscapeString($_POST['focus']); 
		$this->interests = dbEscapeString($_POST['interests']); 
		$this->isAdmin = dbEscapeString($_POST['isAdmin']);
		$this->isActive = dbEscapeString($_POST['isActive']);
		$this->mustUpdatePwd = dbEscapeString($_POST['mustUpdatePwd']);
		$this->pageMode = $_POST['mode'];	
		
		$this->loginName = dbEscapeString($_POST['loginName']); 
		//get password for local validation
		$pwd = dbEscapeString($_POST['loginPwd']);
		$pwdConfirm = dbEscapeString($_POST['loginPwdConfirm']);
		//clear the password from post variables if canceling form post
		$_POST['loginPwd'] = 'password';
		$_POST['loginPwdConfirm'] = 'password';

		//validate login name
		if ($this->loginName == '' || is_null($this->loginName)){
			$this->cancelPost('Login name cannot be blank','login');
		}
		//validate email address
		if ($this->email != '' && filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false) {
    		// $email contains a valid email
		} else {
			if ($this->email == ''){
				$validation = 'Email address cannot be blank';
			} else {
				$validation = 'Email address is not valid';
			}
			$this->cancelPost($validation,'email');
		}
		//validate password
		if ($pwd != '' && $pwd == $pwdConfirm && $pwd != 'password'){
			$this->loginPwd = $this->obfuscate($pwd, $this->loginName);
		} else {
			if ($pwd == '' || is_null($pwd) || is_null($pwdConfirm) || $pwdConfirm == ''){
				$validation = 'Password cannot be blank';
			} elseif ($pwd != $pwdConfirm){
				$validation = 'Passwords do not match';
			}
			$this->cancelPost($validation,'password'); 
		}
	}
	
	private function cancelPost($message, $validation){
		//echo 'in cancel post:'.$message;
		//set validation message
		$this->validationMessages[$validation] = span($message,'highlight-validation');
		//display editing form with current validation state
		$this->printPage();
		die;
	}

	
	public function saveChanges(){
	
		if ($this->pageMode == 'EDIT'){
			
			$sql = " UPDATE users AS u ";
			$sql .= " SET ";
			$sql .= " u.name_first = '".$this->nameFirst."', ";
			$sql .= " u.name_last = '".$this->nameLast."', ";
			$sql .= " u.email = '".$this->email."', ";
			$sql .= " u.focus = '".$this->focus."', ";
			$sql .= " u.interests = '".$this->interests."', ";
			$sql .= " u.login_name = '".$this->loginName."', ";
			$sql .= " u.login_pwd = '".$this->loginPwd."', ";
			$sql .= " u.is_admin = '".$this->isAdmin."', ";
			$sql .= " u.is_active = '".$this->isActive."', ";
			$sql .= " u.must_update_pwd = '".$this->mustUpdatePwd."', ";
			$sql .= " u.type_id = ".$this->typeId." ";
			$sql .= " WHERE u.id = ".$this->id."  ";			

			$result = dbRunSQL($sql);

			$this->mailUser('Your User Profile Has Been Updated');
			
			if ($this->loginName == $_SESSION['login-name']){
				$this->setSecurity($this->loginName);
			}
			
		} else {

			$sql = " INSERT INTO users ";
			$sql .= " (name_first, ";
			$sql .= " name_last, ";
			$sql .= " email, ";
			$sql .= " focus, ";
			$sql .= " interests, ";
			$sql .= " login_name, ";
			$sql .= " login_pwd, ";
			$sql .= " is_admin, ";
			$sql .= " is_active, ";
			$sql .= " must_update_pwd, ";
			$sql .= " created, ";
			$sql .= " type_id) ";
			$sql .= " VALUES (";
			$sql .= " '".$this->nameFirst."', ";
			$sql .= " '".$this->nameLast."', ";
			$sql .= " '".$this->email."', ";
			$sql .= " '".$this->focus."', ";
			$sql .= " '".$this->interests."', ";
			$sql .= " '".$this->loginName."', ";
			$sql .= " '".$this->loginPwd."', ";
			$sql .= " '".$this->isAdmin."', ";
			$sql .= " '".$this->isActive."', ";
			$sql .= " '".$this->mustUpdatePwd."', ";
			$sql .= " CURRENT_TIMESTAMP, ";			
			$sql .= " ".$this->typeId.") ";

			
			$result = dbRunSQL($sql);

			
			$this->id = dbInsertedId();
		}
	
	}
	
} 
//end class User
class UserSQL{
private function columnsUser(){
	$c = " u.id, "; 
	$c .= " u.name_first, "; 
	$c .= " u.name_last, "; 
	$c .= " u.email, "; 
	$c .= " u.focus, ";
	$c .= " u.interests, "; 
	$c .= " u.login_name, ";
	$c .= " u.last_login, "; 
	$c .= " u.created, "; 
	$c .= " u.updated, ";
	$c .= " u.is_admin, ";
	$c .= " u.is_active, ";
	$c .= " u.must_update_pwd, ";
	$c .= " u.type_id, "; 
	$c .= " ut.name type_name, ";
	$c .= " ut.highlight_style ";
	return $c;	
}
public function securityUser($loginName){
	$q = " SELECT ";
	$q .= " u.id, u.is_admin, u.is_active, u.must_update_pwd ";
	$q .= " FROM users u ";
	$q .= " WHERE u.login_name = '".$loginName."' ";	
	return $q;
}
public function infoUser($userId){
	$q = " SELECT ";	
	$q .= $this->columnsUser();
	$q .= " FROM users u LEFT OUTER JOIN user_types ut ON u.type_id = ut.id ";
	$q .= " WHERE  ";
	$q .= " u.id = ".$userId." ";
	return $q;
}
public function listUsers($userTypeId,$resultPage, $rowsPerPage){
	$q = " SELECT ";	
	$q .= $this->columnsUser();
	$q .= " FROM users u LEFT OUTER JOIN user_types ut ON u.type_id = ut.id ";
	if ($userTypeId > 0){
		$q .= " WHERE u.type_id = ".$userTypeId." ";
	}
	$q .= " ORDER BY login_name, name_first, name_last ";
	$q .= sqlLimitClause($resultPage, $rowsPerPage);
	return $q;	
}
public function countUsers($userTypeId = 0){
	$q = " SELECT ";	
	$q .= " COUNT(*) total_users ";
	$q .= " FROM users u ";
	if ($userTypeId > 0){
		$q .= " WHERE u.type_id = ".$userTypeId." ";
	}
	return $q;	
}
public function validateUser($loginName, $loginPwdCrypt){
	$q = " SELECT count(*) AS user_count FROM users u ";
	$q .= " WHERE u.login_name = '".$loginName."' ";
	$q .= " AND u.login_pwd = '".$loginPwdCrypt."' ";
	$q .= " AND u.is_active = 'yes' ";	
	return $q;
}
public function validateLoginAndEmail($loginName, $loginEmail){
	$q = " SELECT count(*) AS user_count FROM users u ";
	$q .= " WHERE u.login_name = '".$loginName."' ";
	$q .= " AND u.email = '".$loginEmail."' ";
	$q .= " AND u.is_active = 'yes' ";	
	return $q;
}
}
//end class UserSQL
?>
