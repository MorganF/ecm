<?php
require_once('../includes.php');

Ecm_QueryPosts::find();

include ('includes/header.php');
?>

<div class="postList">	
	<?php
	while ($post = Ecm_QueryPosts::next()) :
	?>
		<div class="post">
			<div class="item">
				<a href="post-edit.php?post-id=<?php echo $post->getId(); ?>" title="Editer <?php echo $post->getTitle(); ?>"><?php echo $post->getTitle(); ?></a>
			</div>
			
			<div class="item">
				<?php echo $post->getAuthor()->getLogin(); ?>
			</div>
			
			<div class="item">
				<?php echo $post->getDatePublication('d/m/Y'); ?>
			</div>
		</div>
	<?php
	endwhile;
	?>
</div>

<?php include ('includes/footer.php'); ?>