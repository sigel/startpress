<?php
/*
Template Name: Full Width No Sidebar
*/
?>
<?php get_header(); ?>
<section id="content" class="interior">
	<div class="wrap">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>
		<?php endwhile; endif; ?>
	</div><!--End Wrapper-->
</section>
<?php get_footer(); ?>
