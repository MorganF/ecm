<?php
class Ecm_QueryPosts
{
	protected static $globalQuery = NULL;
	protected static $currentResult = NULL;
	protected static $currentPost = NULL;
	
	public static function find ($args = array(), $saveAsGlobal = FALSE)
	{
		global $Ecm_db;
		
		if (self::$globalQuery && !count($args))
			$query = self::$globalQuery;
		else
		{
			$where = array();
			$order = array('criteria' => 'date_publication', 'way' => 'DESC');
			$limit = array('page' => 1, 'qte' => 10);
			
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
					
					case "$page" :
						$limit['page'] = $value;
					break;
					
					case "qte" :
						$limit['qte'] = $value;
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
			$limit = "Limit " . (($limit['page'] - 1) * $limit['qte']) . ", " . $limit['qte'];
			
			$query = "Select * From posts $where $order $limit";
			
			if ($saveAsGlobal)
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
		
		self::$currentPost = $toi;
		return $toi;
	}
	
	public static function getCurrentPost ()
	{
		return self::$currentPost;
	}
}