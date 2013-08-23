<?php
$post = Ecm_QueryPosts::getCurrentPost();

include ('header.php');
?>

<div id="contentWrapper">
	<div id="content">
		<h1><?php echo $post->getTitle(); ?></h1>
		
		<div class="post">
			<?php echo $post->getContent(); ?>
		</div>
	</div>
	
	<?php include ('sidebar-post.php'); ?>
</div>

<?php include ('footer.php'); ?>