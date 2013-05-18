<?php get_header(); ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="post">

		<h3><?php the_title(); ?><?php edit_post_link('Edit', '<span class="edit">', '</span>'); ?></h3>

		<p class="byline">In <?php the_category(', ') ?> on <?php the_time('j F Y') ?>
		with <?php comments_popup_link('no comments', '1 comment', '% comments'); ?>
		<img class="noline" alt='flame_ico' src='<?php echo get_bloginfo('template_directory') . '/img/flame-icon20x12-aligned-right.png'; ?>' />
		<a href="<?php echo get_option( 'depotwk-post-stats-desc-page' ); ?>" title="<?php echo get_option( 'depotwk-post-stats-href-title' ); ?>">
		<?php echo get_post_meta( get_the_ID(), 'views', true ); ?> views</a>
		<div class="tags-block">
		<?php the_tags('tags: ', ' ', ''); ?></p>
		</div>

		<?php the_content(); ?>

		<br />

	</div><!-- end post -->

			<?php comments_template(); ?>
			
		<?php endwhile; else: ?>
		
			<h2 class="page_header">Uh oh.</h2>
			<div class="entry">
				<p>Sorry, no posts matched your criteria. Wanna search instead?</p>
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</div>
			
		<?php endif; ?>
		
<div class="postnoline">
	<span class="previous"><?php previous_post_link('&larr; %link') ?></span>
	<span class="next"><?php next_post_link('%link &rarr;') ?></span>
</div>

<?php get_footer(); ?>
