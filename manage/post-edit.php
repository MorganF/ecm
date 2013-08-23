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
			if ($_POST['post-slug'] != $post->getSlug())
				$post->setSlug(Ecm_Urls::slugItUnique($_POST['post-slug'], $post->getSlug()));
			
			$post->setType($_POST['post-type']);
			$post->setTitle($_POST['post-title']);
			$post->setContent($_POST['post-content']);
			
			if (isset($_POST['post-category']))
			{
				$post->clearCategories();
				
				foreach ($_POST['post-category'] as $category)
					$post->addCategory($category);
			}
			
			$post->save();
		}
		
		$pagePost = $post;
	}
}
// Rédaction d'un nouvel article
else if (isset($_POST['post']))
{
	$post = new Ecm_Post();
	$post->setType($_POST['post-type']);
	$post->setSlug(Ecm_Urls::slugItUnique($_POST['post-slug'], NULL));
	$post->setTitle($_POST['post-title']);
	$post->setContent($_POST['post-content']);
	$post->setAuthorId($Ecm_session->getUser()->getId());
	
	if ($post->save())
	{
		header ('Location: post-edit.php?post-id=' . $post->getId());
		exit();
	}
}

include ('includes/header.php');
?>

<div id="contentGlobal">
	<div id="contentWrapper">
		<div id="contentRow">
			<div id="content">
				<div class="contentBlock">
					<form method="post" id="postForm">
						<input type="text" id="postTitleInput" name="post-title" value="<?php echo ($pagePost ? $pagePost->getTitle() : ''); ?>" />
						
						<input type="text" id="postSlugInput" name="post-slug" value="<?php echo ($pagePost ? $pagePost->getSlug() : ''); ?>" />
					
						<textarea name="post-content"><?php echo ($pagePost ? $pagePost->getContent() : ''); ?></textarea>
						
						<input type="hidden" name="post-type" id="postTypeHidden" value="<?php echo ($pagePost ? $pagePost->getType() : Ecm_Post::getDefaultType()); ?>" />
						<input type="hidden" id="postIdHidden" name="post" value="<?php echo ($pagePost ? $pagePost->getId() : ''); ?>" />
						<input type="hidden" id="postSlugHidden" name="postSlugHidden" value="<?php echo ($pagePost ? $pagePost->getSlug() : ''); ?>" />
					</form>
				</div>
			</div>
	            
			<div id="sideRight">
				<div class="sideBlock">
					<?php
					if ($pagePost) :
					?>
						<a href="../<?php echo $pagePost->getPermalink(); ?>" class="buttonGray" title="Prévisualisation de <?php echo $pagePost->getTitle(); ?>" target="_blank">Prévisualiser</a>
					<?php
					endif;
					?>
					
					<p><input type="button" value="Valider" onclick="$('#postForm').submit();" class="buttonBlue" /></p>
				</div>
				
				<div class="sideBlock">
					<p class="title">Type de soumission</p>
				
					<?php
					foreach (Ecm_Post::getTypes() as $type => $data) :
						$selected = $pagePost ? $type == $post->getType() : $type == Ecm_Post::getDefaultType();
					?>
						<p id="radiosPostTypes">
							<input type="radio" name="post-type" id="postType_<?php echo $type; ?>" value="<?php echo $type; ?>" <?php echo ($selected ? 'checked="checked"' : ''); ?> />
							<label for="postType_<?php echo $type; ?>"><?php echo $data['label']; ?></label>
						</p>
					<?php
					endforeach;
					?>
				</div>
				
				<div class="sideBlock">
					<p class="title">Catégories</p>
					
					<?php
					Ecm_QueryCategories::find();
					
					while ($category = Ecm_QueryCategories::next()) :
						$selected = $pagePost->containsCategory($category->getId());
					?>
						<p id="checkboxesPostCategories">
							<input type="checkbox" name="post-category" id="postCategoryInput_<?php echo $category->getId(); ?>" value="<?php echo $category->getId(); ?>" <?php echo ($selected ? 'checked="checked"' : ''); ?> />
							<label for="postCategoryInput_<?php echo $category->getId(); ?>"><?php echo $category->getTitle(); ?></label>
						</p>
					<?php
					endwhile;
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="js/post-edit.js"></script>
<script src="js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
	selector: "textarea",
	theme: "modern",
	height: 300,
	plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
	],
	toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
	style_formats: [
	{
		title: 'Bold text', inline: 'b'},
		{
			title: 'Example 1', inline: 'span', classes: 'example1'},
			{
				title: 'Example 2', inline: 'span', classes: 'example2'},
				{
					title: 'Table styles'},
					{
						title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
						]
});
</script>

<?php include ('includes/footer.php'); ?>