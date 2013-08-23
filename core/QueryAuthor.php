<?php
class Ecm_QueryAuthor
{
	protected static $globalQuery = NULL;
	protected static $currentResult = NULL;
	protected static $currentAuthor = NULL;
	
	public static function find ($args = array(), $saveAsGlobal = FALSE)
	{
		global $Ecm_db;
		
		if (self::$globalQuery && !count($args))
			$query = self::$globalQuery;
		else
		{
			$where = array();
				
			foreach ($args as $field => $value)
			{
				switch ($field)
				{
					case "author-id" :
						$where['authors.id'] = $value;
					break;
				}
			}
			
			$where = count($where) ? 'Where ' . $Ecm_db->processConjonction($where) : '';
			
			$query = "Select * From authors $where";
			
			if ($saveAsGlobal)
				self::$globalQuery = $query;
		}
		
		self::$currentResult = $Ecm_db->query($query);
	}
	
	public static function next ()
	{
		global $Ecm_db;
		$toi = Ecm_Author::constructFromDb($Ecm_db->fetch(self::$currentResult));
		
		if (!$toi)
			self::$currentResult = NULL;
		
		self::$currentAuthor = $toi;
		return $toi;
	}
	
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
	
	public static function getCurrentAuthor ()
	{
		return self::$currentAuthor;
	}
}