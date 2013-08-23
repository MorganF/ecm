<?php
require_once('../../includes.php');

if (isset($_GET['slug']) && isset($_GET['currentSlug']))
	echo Ecm_Urls::slugItUnique($_GET['slug'], $_GET['currentSlug']);