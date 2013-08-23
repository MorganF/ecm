<?php 
$post = Ecm_QueryPosts::getCurrentPost();
?>

<div id="sidebar">
	<div class="sideBlock">
		<?php echo $post->getAuthor()->getLogin(); ?>
	</div>
</div>