<?php
class Ecm_Session
{
	public $user = NULL;
	
	public function __construct ()
	{
		session_start();
	}
	
	public function saveUser ($user)
	{
		if ($user)
			$_SESSION['user'] = gzdeflate(serialize($user), 9);
	}
	
	public function getUser ()
	{
		return isset($_SESSION['user']) && $_SESSION['user'] ? unserialize(gzinflate($_SESSION['user'])) : NULL;
	}
}