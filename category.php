<?php get_header(); ?>
<section id="content" class="interior">
	<div class="wrap">
		<div class="content">
<?php 
$current_cat = get_query_var('cat');
    $args=array(
	  'post_type' => 'bookmark',
	  'post_status' => array( 'publish' ),
	  'posts_per_page' => -1,
      'order' => 'ASC',
      'orderby' => 'menu_order',
      'category__in' => $current_cat
    );
    // Show Private Bookmarks if logged in
    if ( is_user_logged_in() ) {
		$args['post_status'][] = 'private';
	}
	query_posts($args);
	if ( have_posts() ) : ?>
		<h3 class="cat"><?php single_cat_title(); ?></h3>
		<ul class="cat">
			<?php while ( have_posts() ) : the_post(); ?>
			<li<?php if ( !has_post_thumbnail() ) {  echo ' class="noimg"'; } ?>><a <?php if ( get_post_meta($post->ID, 'sp_modal', true) == "on" ) {  ?><?php echo 'class="modal" data-fancybox-type="iframe" ' ?><?php } ?>href="<?php if (get_post_meta($post->ID, 'sp_anon', true) == "on") :  ?><?php echo "http://www.dereferer.org/?"; ?><?php endif; ?><?php if ( get_post_meta($post->ID, 'sp_url', true) ) {  ?><?php echo get_post_meta($post->ID, 'sp_url', true); ?><?php } ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" <?php if ( get_post_meta($post->ID, 'sp_new', true) == "on" ) {  ?><?php echo 'target="_blank"'; ?><?php } ?>><?php if ( has_post_thumbnail() ) {  echo get_the_post_thumbnail($post_id, 'bookmark-icon'); } ?><span><?php the_title(); ?></span></a></li>
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
