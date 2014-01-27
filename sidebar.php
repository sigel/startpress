<div class="sidebar">
	<?php if(is_active_sidebar('spsidebar')) : ?>
	<?php dynamic_sidebar('spsidebar'); ?>
	<?php endif; ?>
	<?php 
	$args = array(
    'orderby'          => 'name',
    'order'            => 'ASC',
    'limit'            => -1,
    'hide_invisible'   => 1,
    'show_updated'     => 0,
    'echo'             => 1,
    'categorize'       => 1,
    'title_li'         => __('Quick Links'),
    'title_before'     => '<h3>',
    'title_after'      => '</h3>',
    'category_orderby' => 'name',
    'category_order'   => 'ASC',
    'category_before'  => '<li id="quicklinks" class="quicklinks">',
    'category_after'   => '</li>' );	
	wp_list_bookmarks( $args ); ?> 
</div>
