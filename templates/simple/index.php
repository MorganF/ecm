<?php include ('header.php'); ?>

<div id="contentWrapper">
	<div id="content">
		<?php
		Ecm_QueryPosts::find();
		
		while ($post = Ecm_QueryPosts::next()) :
		?>
			<div class="post">
				<p class="title"><?php echo $post->getTitle(); ?>, par <?php echo $post->getAuthor()->getLogin(); ?></p>
				
				<?php echo $post->getContent(); ?>
				
				<p>Le <?php echo $post->getDatePublication('d/m/Y'); ?></p>
			</div>
		<?php
		endwhile;
		?>
	</div>
	
	<?php include ('sidebar.php'); ?>
</div>

<?php include ('footer.php'); ?>