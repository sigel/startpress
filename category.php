<?php get_header(); ?>
<section id="content" class="interior">
	<div class="wrap">
		<div class="content">
<?php 
$current_cat = get_query_var('cat');
    $args=array(
	  'post_type' => 'bookmark',
      'order' => 'ASC',
      'orderby' => 'menu_order',
      'category__in' => $current_cat
    );
query_posts($args);
if ( have_posts() ) : ?>
		<h3 class="cat"><?php single_cat_title(); ?></h3>
		<ul class="cat">
			<?php while ( have_posts() ) : the_post(); ?>
			<li<?php if ( !has_post_thumbnail() ) {  echo ' class="noimg"'; } ?>><a <?php if ( get_post_meta($post->ID, 'sp_modal', true) == "on" ) {  ?><?php echo 'class="modal" data-fancybox-type="iframe" ' ?><?php } ?>href="<?php if ( get_post_meta($post->ID, 'sp_url', true) ) {  ?><?php echo get_post_meta($post->ID, 'sp_url', true); ?><?php } ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" <?php if ( get_post_meta($post->ID, 'sp_new', true) == "on" ) {  ?><?php echo 'target="_blank"'; ?><?php } ?>><?php if ( has_post_thumbnail() ) {  echo get_the_post_thumbnail($post_id, 'thumbnail'); } ?><span><?php the_title(); ?></span></a></li>
          <?php endwhile; else: ?>
		<h1>Sorry, No Bookmarks Available.</h1>
<?php endif; ?>	
		</ul>
		<h4><a href="/" class="icon-angle-circled-left goback"> GO BACK</a></h4>
		</div><!-- .content -->
		<?php get_sidebar(); ?>
	</div><!-- .wrap-->
</section>
<?php get_footer(); ?>
