<?php
class Ecm_QueryAuthor
{
	public static function insert ($login, $password, $email)
	{
		global $Ecm_db;
		
		$login = $Ecm_db->secureString($login);
		$email = $Ecm_db->secureString($email);
		$password = md5($password);
		
		$query = "Insert Into authors (login, password, email) Values ('$login', '$password', '$email')";
		$Ecm_db->query($query) or die ($Ecm_db->error());
		
		return $Ecm_db->affectedRows() == 1;
	}
	
	public static function isLoginAvailable ($login)
	{
		global $Ecm_db;
		
		$login = $Ecm_db->secureString($login);
		
		$query = "Select id From authors Where login='$login'";
		$result = $Ecm_db->query($query);
		
		return !$Ecm_db->countRows($result);
	}
	
	public static function isMailAvailable ($mail)
	{
		global $Ecm_db;
	
		$mail = $Ecm_db->secureString($mail);
	
		$query = "Select id From authors Where email='$mail'";
		$result = $Ecm_db->query($query);
	
		return !$Ecm_db->countRows($result);
	}
	
	public static function getAuthorFromLoginPassword ($login, $password)
	{
		global $Ecm_db;
		
		$login = $Ecm_db->secureString($login);
		$password = md5($password);
		$loginField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login'; // On peut se connecter via login ou email
		
		$query = "Select * From authors Where $loginField='$login' And password='$password'";
		$result = $Ecm_db->query($query);
		
		return Ecm_Author::constructFromDb($Ecm_db->fetch($result));
	}
}