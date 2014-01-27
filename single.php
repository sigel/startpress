<?php get_header(); ?>
<section id="content" class="interior">
	<div class="wrapper">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<h1><?php the_title(); ?></h1>
				
			<?php the_content(); ?>

			<?php edit_post_link(__('Edit this entry.'), '<p>', '</p>'); ?>

		<?php endwhile; endif; ?>
	</div><!--End Wrapper-->
</section>
<?php get_footer(); ?>
