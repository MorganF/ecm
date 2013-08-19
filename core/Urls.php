<?php
class Ecm_Urls
{
	const PLR_CATEGORY_TITLE		=	'category-title';
	const PLR_POST_TITLE		=	'post-title';
	const PLR_POST_ID			=	'post-id';
	const PLR_AUTHOR_LOGIN		=	'author-login';
	
	public static $replaces = array
	(
		self::PLR_CATEGORY_TITLE	=>	'[a-z0-9-]+',
		self::PLR_POST_TITLE		=>	'[a-z0-9-]+',
		self::PLR_POST_ID			=>	'[0-9-]+',
		self::PLR_AUTHOR_LOGIN		=>	'[a-z0-9-]+'
	);
	
	public static function getRequestUri ()
	{
		global $installPath;
	
		return str_replace($installPath, '', $_SERVER['REQUEST_URI']);
	}
	
	public static function slugIt ($texte)
	{
		$slug = htmlentities($texte, ENT_NOQUOTES, 'UTF-8'); // Pour pouvoir traiter l'UTF-8
		$slug = mb_strtolower ($slug); // Le tout doit être en minuscules
		$slug = preg_replace('#\&([A-za-z])(?:acute|cedil|circ|grave|ring|tilde|uml)\;#', '\1', $slug); // Les caractères accentués en entités HTML sont toujours du type &eacute; = é <=> e
		$slug = preg_replace('#\&([A-za-z]{2})(?:lig)\;#', '\1', $slug); // Pour les &eolig;
		$slug = preg_replace('#\&[^;]+\;#', '', $slug); // Le reste est supprimé
		$slug = trim(preg_replace('#[^[:alnum:]]#i', ' ', $slug)); // On ne garde que les caractères alphanumériques et on trim
		$slug = preg_replace('#[[:space:]]+#i', '-', $slug); // On met des tirets partout
		
		return $slug;
	}
	
	public static function getUriPattern ($pattern, $exclude = NULL)
	{
		foreach (self::$replaces as $replace => $replacement)
		{
			$replace = ':' . $replace . ':'; 
			
			if ($replace != $exclude)
				$pattern = str_replace($replace, $replacement, $pattern);
		}
		
		return $pattern;
	}
	
	public static function getSlugFromUri ($uri, $pattern, $plr)
	{
		$plr = ':' . $plr . ':';
		$pattern = self::getUriPattern($pattern, $plr);
		
		// Si le permalien correspond au slug du template courant
		if ($pattern == $plr)
			$slug = $uri;
		else
		{
			$pos = strpos($pattern, $plr);
			$left = substr($pattern, 0, $pos);
			$right = substr($pattern, $pos + strlen($plr) + 1);
			$slug = preg_replace('#^' . $left . '#', '', $uri);
			$slug = preg_replace('#' . $right . '$#', '', $slug);
		}
		
		return $slug;
	}
}