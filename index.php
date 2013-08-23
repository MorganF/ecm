<?php
require_once('includes.php');

$requestUri = Ecm_Urls::getRequestUri();
$templateType = NULL;

if (empty($requestUri))
	$templateType = 'index';
else
{
	// On teste si c'est une catégorie
	if (preg_match('#^' . Ecm_Urls::getPermaStructurePattern($permaStructureCategories) . '$#', $requestUri))
	{
		// Ca ressemble à une catégorie, on va chercher en base
		$slug = Ecm_Urls::getSlugFromUri($requestUri, $permaStructureCategories, Ecm_Urls::PLR_CATEGORY_TITLE);
		Ecm_QueryCategories::find(array('category-slug' => $slug));
		$category = Ecm_QueryCategories::next();

		if ($category)
			$templateType = 'category';
	}
	
	// On teste si c'est un article
	if (!$templateType && preg_match('#^' . Ecm_Urls::getPermaStructurePattern($permaStructurePosts) . '$#', $requestUri))
	{
		// Ca ressemble à un article, on va chercher en base
		$slug = Ecm_Urls::getSlugFromUri($requestUri, $permaStructurePosts, Ecm_Urls::PLR_POST_TITLE);
		Ecm_QueryPosts::find(array('post-slug' => $slug), TRUE);
		$post = Ecm_QueryPosts::next();
		
		if ($post)
			$templateType = 'post';
	}
}

if ($templateType)
	include ('templates/' . $currentTemplate . '/' . $templateType . '.php');
else
	header("HTTP/1.0 404 Not Found");