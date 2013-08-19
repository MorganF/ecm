<?php
require_once('core/DbInterface.php');
require_once('core/Session.php');
require_once('core/Urls.php');
require_once('core/Post.php');
require_once('core/Category.php');
require_once('core/Author.php');
require_once('core/QueryPosts.php');
require_once('core/QueryCategories.php');
require_once('core/QueryAuthor.php');

// CFG
$installPath = '/ECM/';
$currentTemplate = 'simple';

$urlPatternCategories	= ':category-title:';
$urlPatternPosts		= ':category-title:/:post-title:\.html';
$urlPatternAuthors		= 'auteur/:author-login:';

// GLOBAL
$Ecm_db = new Ecm_DbInterface();
$Ecm_session = new Ecm_Session();