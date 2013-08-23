<?php
class Ecm_Author
{
	protected $id;
	protected $login;
	protected $email;
	
	public static function constructFromDb ($obj)
	{
		if ($obj)
		{
			$author = new self();
			$author->id = $obj->id;
			$author->login = $obj->login;
			$author->email = $obj->email;
		}
		else
			$author = NULL;
	
		return $author;
	}
	
	public function getId ()
	{
		return $this->id;
	}
	
	public function getLogin ()
	{
		return $this->login;
	}
	
	public function getEmail ()
	{
		return $this->email;
	}
	
	public function getPermalink ()
	{
		return '/auteur/' . $this->login;
	}
}