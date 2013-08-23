<?php
class Ecm_QueryCategories
{
	protected static $globalQuery = NULL;
	protected static $currentResult = NULL;
	protected static $currentCategory = NULL;
	
	public static function find ($args = array(), $saveAsGlobal = FALSE)
	{
		global $Ecm_db;
		
		if (self::$globalQuery && !count($args))
			$query = self::$globalQuery;
		else
		{
			$where = array();
			$order = array('criteria' => 'title', 'way' => 'ASC');
				
			foreach ($args as $field => $value)
			{
				switch ($field)
				{
					case "category-id" :
						$where['categories.id'] = $value;
					break;
					
					case "category-slug" :
						$where['categories.slug'] = $value;
					break;
					
					case "sort-by" :
						$order['criteria'] = $value;
					break;
							
					case "sort-way" :
						$order['way'] = strtolower($value) == 'asc' ? 'ASC' : 'DESC';
					break;
				}
			}
			
			$where = count($where) ? 'Where ' . $Ecm_db->processConjonction($where) : '';
			$order = "Order By " . $Ecm_db->secureString($order['criteria']) . " " . $order['way'];
			
			$query = "Select * From categories $where $order";
			
			if ($saveAsGlobal)
				self::$globalQuery = $query;
		}
		
		self::$currentResult = $Ecm_db->query($query);
	}
	
	public static function next ()
	{
		global $Ecm_db;
		$toi = Ecm_Category::constructFromDb($Ecm_db->fetch(self::$currentResult));
	
		if (!$toi)
			self::$currentResult = NULL;
	
		self::$currentCategory = $toi;
		return $toi;
	}
	
	public static function insert ($slug, $title, $description)
	{
		global $Ecm_db;
	
		$slug = $Ecm_db->secureString(Ecm_Urls::slugItUnique($slug));
		$title = $Ecm_db->secureString($title);
		$description = $Ecm_db->secureString($description);
	
		$query = "Insert Into categories (slug, title, descriptio) Values ('$slug', '$title', '$description')";
		$Ecm_db->query($query) or die ($Ecm_db->error());
	
		return $Ecm_db->affectedRows() == 1;
	}
	
	public static function getCurrentCategory ()
	{
		return self::$currentCategory;
	}
}