<div id="content">
	<?php
	Ecm_QueryPosts::find();
	
	while ($post = Ecm_QueryPosts::next()) :
	?>
		<div class="post">
			<p class="title"><?php echo $post->getTitle(); ?></p>
			
			<?php echo $post->getContent(); ?>
		</div>
	<?php
	endwhile;
	?>
</div>