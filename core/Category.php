<?php
class Ecm_Category
{
	protected $slug;
	protected $title;
	protected $description;
	
	public static function constructFromDb ($obj)
	{
		if ($obj)
		{
			$categorie = new self();
			$categorie->slug = $obj->slug;
			$categorie->title = $obj->title;
			$categorie->description = $obj->description;
		}
		else
			$categorie = NULL;
		
		return $categorie;
	}
	
	public function getTitle ()
	{
		return stripslashes($this->title);
	}
	
	public function getDescription ()
	{
		return nl2br(stripslashes($this->description));
	}
}