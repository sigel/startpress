<?php get_header(); ?>
<section id="content" class="interior">
	<div class="wrap">
		<div class="content">
<?php
//for each category, show all posts
$cat_args=array(
  'order' => 'ASC'
   );
$categories=get_categories($cat_args);
  foreach($categories as $category) {
    $args=array(
      'post_type' => 'bookmark',
      'post_status' => array( 'publish' ),
      'posts_per_page' => -1,
      'category__in' => array($category->term_id),
      'order' => 'ASC',
      'orderby' => 'menu_order'
    );
    // Show Private Bookmarks if logged in
    if ( is_user_logged_in() ) {
		$args['post_status'][] = 'private';
	}
    $posts=get_posts($args);
      if ($posts) {
		echo '<div class="anchor"><a name="' . $category->slug . '"></a></div>';
        echo '<h3 class="cat"><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a></h3> ';
        echo '<ul class="cat">';
        foreach($posts as $post) {
          setup_postdata($post); ?>
          <li<?php if ( !has_post_thumbnail() ) {  echo ' class="noimg"'; } ?>><a <?php if (get_post_meta($post->ID, 'sp_modal', true) == "on" ) {  ?><?php echo 'class="modal" data-fancybox-type="iframe" ' ?><?php } ?>href="<?php if ( get_post_meta($post->ID, 'sp_url') ) :  ?><?php echo get_post_meta($post->ID, 'sp_url', true); ?><?php endif; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" <?php if (get_post_meta($post->ID, 'sp_new', true) == "on") {  ?><?php echo 'target="_blank"'; ?><?php } ?>><?php if ( has_post_thumbnail() ) {  echo get_the_post_thumbnail($post_id, 'bookmark-icon'); } ?><span><?php the_title(); ?></span></a></li>
          <?php
        } // foreach($posts
        echo '</ul>';
      } // if ($posts
    } // foreach($categories
?>
		</div><!-- .content -->
		<?php get_sidebar(); ?>
	</div><!-- .wrap-->
</section>
<?php get_footer(); ?>
