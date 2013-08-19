<?php
require_once('../includes.php');

$pagePost = NULL;

// Modification d'un article
if (isset($_GET['post-id']))
{
	Ecm_QueryPosts::find(array('post-id' => $_GET['post-id']));
	$post = Ecm_QueryPosts::next();
	
	// On vérifie que l'utilisateur courant a le droit de modifier cet article
	if ($post && $post->getAuthorId() == $Ecm_session->getUser()->getId())
	{
		// Modification de l'article
		if (isset($_POST['post']))
		{
			$post->setTitle($_POST['post-title']);
			$post->setContent($_POST['post-content']);
			$post->save();
		}
		
		$pagePost = $post;
	}
}
// Rédaction d'un nouvel article
else if (isset($_POST['post']))
{
	$post = new Ecm_Post();
	$post->setSlug(Ecm_Urls::slugIt($_POST['post-title']));
	$post->setTitle($_POST['post-title']);
	$post->setContent($_POST['post-content']);
	$post->setAuthorId($Ecm_session->getUser()->getId());
	
	if ($post->save())
	{
		header ('Location: post-edit.php?post-id=' . $post->getId());
		exit();
	}
	
	var_dump($post);
}
?>
<html>
	<head>
		<meta charset="UTF-8" />
	</head>
	
	<body>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/tinymce/tinymce.min.js"></script>
		<script>
		tinymce.init({
		    selector: "textarea",
		    theme: "modern",
		    plugins: [
		         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		         "save table contextmenu directionality emoticons template paste textcolor"
		   ],
		   content_css: "css/content.css",
		   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons", 
		   style_formats: [
		        {title: 'Bold text', inline: 'b'},
		        {title: 'Example 1', inline: 'span', classes: 'example1'},
		        {title: 'Example 2', inline: 'span', classes: 'example2'},
		        {title: 'Table styles'},
		        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
		    ]
		 });
		</script>
		
		<form method="post">
			<input type="text" name="post-title" value="<?php echo ($pagePost ? $pagePost->getTitle() : ''); ?>" />

			<textarea name="post-content"><?php echo ($pagePost ? $pagePost->getContent() : ''); ?></textarea>

			<p><input type="submit" name="post" value="Valider" /></p>
		</form>
	</body>
</html>