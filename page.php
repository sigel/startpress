<?php get_header(); ?>
<section id="content" class="interior">
	<div class="wrap">
		<div class="content">
	<?php 
    $args=array(
	  'post_type' => 'page',
      'p' => get_the_ID()
    );
	query_posts($args); if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
			<?php edit_post_link(__('<span class="icon-cog">EDIT PAGE</span>'), '<h2>', '</h2>'); ?>
		<?php endwhile; endif; ?>
		</div><!-- .content -->
		<?php get_sidebar(); ?>
	</div><!-- .wrap-->
</section>
<?php get_footer(); ?>
