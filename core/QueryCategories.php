<?php
class Ecm_QueryCategories
{
	public static function find ($slug)
	{
		global $Ecm_db;
		$slug = $Ecm_db->secureString($slug);
		
		$query = "Select * From categories Where slug='$slug'";
		
		return Ecm_Category::constructFromDb($Ecm_db->fetch($Ecm_db->query($query)));
	}
}