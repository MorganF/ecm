<?php
class Ecm_QueryPosts
{
	protected static $globalQuery = NULL;
	protected static $currentResult = NULL;
	
	public static function find ($args = array(), $global = FALSE)
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
					case "post-id" :
						$where['posts.id'] = $value;
					break;
					
					case "post-slug" :
						$where['posts.slug'] = $value; 
					break;
					
					case "qte" :
						
					break;
				}
			}
			
			$where = count($where) ? 'Where ' . $Ecm_db->processConjonction($where) : '';
			
			$query = "Select * From posts $where";
			
			if ($global)
				self::$globalQuery = $query;
		}
		
		self::$currentResult = $Ecm_db->query($query);
	}
	
	public static function next ()
	{
		global $Ecm_db;
		$toi = Ecm_Post::constructFromDb($Ecm_db->fetch(self::$currentResult));
		
		if (!$toi)
			self::$currentResult = NULL;
		
		return $toi;
	}
}