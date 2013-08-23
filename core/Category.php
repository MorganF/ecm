<?php
class Ecm_Category
{
	protected $id;
	protected $slug;
	protected $title;
	protected $description;
	
	public static function constructFromDb ($obj)
	{
		if ($obj)
		{
			$categorie = new self();
			$categorie->id = $obj->id;
			$categorie->slug = $obj->slug;
			$categorie->title = $obj->title;
			$categorie->description = $obj->description;
		}
		else
			$categorie = NULL;
		
		return $categorie;
	}
	
	
	//--> GETTERS
	public function getId ()
	{
		return $this->id;
	}
	
	public function getSlug ()
	{
		return stripslashes($this->slug);
	}
	
	public function getTitle ()
	{
		return stripslashes($this->title);
	}
	
	public function getDescription ()
	{
		return nl2br(stripslashes($this->description));
	}
	
	public function getPermalink ()
	{
		global $permaStructureCategories;
		$url = $permaStructureCategories;
	
		if (preg_match_all('#:([^:]+):#', $url, $tab))
		{
			for ($i = 0; $i < count($tab[1]); $i++)
			{
				switch ($tab[1][$i])
				{
					case Ecm_Urls::PLR_CATEGORY_TITLE :
						$replacement = $this->getSlug();
					break;
						
					default :
						$replacement = $tab[1][$i];
				}
		
				$url = str_replace(':' . $tab[1][$i] . ':', $replacement, $url);
			}
		}
	
		return $url;
	}
	
	
	//--> SETTERS
}