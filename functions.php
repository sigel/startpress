<?php
// Theme Setup (based on twentythirteen: http://make.wordpress.org/core/tag/twentythirteen/)
	function html5reset_setup() {
		load_theme_textdomain( 'html5reset', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );	
		add_theme_support( 'structured-post-formats', array( 'link', 'video' ) );
		register_nav_menu( 'primary', __( 'Navigation Menu', 'html5reset' ) );
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 150, 150 );
	}
	add_action( 'after_setup_theme', 'html5reset_setup' );
	
// Scripts & Styles (based on twentythirteen: http://make.wordpress.org/core/tag/twentythirteen/)
	add_action( 'wp_enqueue_scripts', 'html5reset_scripts_styles' );
	function html5reset_scripts_styles() { global $wp_styles; }
	if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
	function my_jquery_enqueue() {
		wp_deregister_script('jquery');
		wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js?ver=3.7.1", false, null);
		wp_enqueue_script('jquery');
	}
	if (!is_admin()) add_action("wp_enqueue_scripts", "my_fancybox_enqueue", 11);
	function my_fancybox_enqueue() {
		wp_deregister_script('fancybox');
		wp_register_script('fancybox', get_template_directory_uri() . '/js/jquery.fancybox.pack.js', false, null);
		wp_enqueue_script('fancybox');
	}

// Custom Logo
function sp_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/img/logo.png);
            padding-bottom: 30px;
            background-size: 267px 46px;
            width: 267px;
            height: 46px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'sp_login_logo' );
	
// Dash Cleanup
	add_action('wp_dashboard_setup', 'dashboard_cleanup');
	function dashboard_cleanup()
	{  
	 remove_meta_box( 'dashboard_primary',       'dashboard', 'side' );      //WordPress.com Blog
     remove_meta_box( 'dashboard_secondary',     'dashboard', 'side' );      //Other WordPress News
	 remove_meta_box( 'dashboard_incoming_links','dashboard', 'normal' );    //Incoming Links
     remove_meta_box( 'dashboard_plugins',       'dashboard', 'normal' );    //Plugins
	}
	

// Enable Links
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// Register Bookmarks Post Type
function bookmarks() {

	$labels = array(
		'name'                => 'Bookmarks',
		'singular_name'       => 'Bookmark',
		'menu_name'           => 'Bookmarks',
		'parent_item_colon'   => 'Parent Bookmark:',
		'all_items'           => 'All Bookmarks',
		'view_item'           => 'View Bookmark',
		'add_new_item'        => 'Add New Bookmark',
		'add_new'             => 'New Bookmark',
		'edit_item'           => 'Edit Bookmark',
		'update_item'         => 'Update Bookmark',
		'search_items'        => 'Search Bookmarks',
		'not_found'           => 'No Bookmarks found',
		'not_found_in_trash'  => 'No Bookmarks found in Trash',
	);
	$args = array(
		'label'               => 'bookmark',
		'description'         => 'Create your custom Bookmarks',
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		'taxonomies'          => array( 'category' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'bookmark', $args );

}

// Hook into the 'init' action
add_action( 'init', 'bookmarks', 0 );

// Custom Meta Boxes
add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add()
{
	add_meta_box( 'startpress', 'Start Press Options', 'cd_meta_box_cb', 'bookmark', 'normal', 'high' );
}

function cd_meta_box_cb( $post )
{
	$values = get_post_custom( $post->ID );
	$sp_url = isset( $values['sp_url'] ) ? esc_attr( $values['sp_url'][0] ) : '';
	$sp_new = isset( $values['sp_new'] ) ? esc_attr( $values['sp_new'][0] ) : '';
	$sp_modal = isset( $values['sp_modal'] ) ? esc_attr( $values['sp_modal'][0] ) : '';
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>
	<p>
		<label for="sp_url">Website URL </label>
		<input type="text" name="sp_url" id="sp_url" size="100%" value="<?php echo $sp_url; ?>" placeholder="http://" />
	</p>
	<p>
		<input type="checkbox" name="sp_new" id="sp_new" <?php checked( $sp_new, 'on' ); ?> />
		<label for="sp_new">Open In New Window</label>
	</p>
	<p>
		<input type="checkbox" name="sp_modal" id="sp_modal" <?php checked( $sp_modal, 'on' ); ?> />
		<label for="sp_modal">Open In Modal</label>
	</p>
	<?php	
}


add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;
	
	// now we can actually save the data
	$allowed = array( 
		'a' => array( // on allow a tags
			'href' => array() // and those anchords can only have href attribute
		)
	);
	
	// Probably a good idea to make sure your data is set
	if( isset( $_POST['sp_url'] ) )
		update_post_meta( $post_id, 'sp_url', wp_kses( $_POST['sp_url'], $allowed ) );
		
	// This is purely my personal preference for saving checkboxes
	$chk = ( isset( $_POST['sp_new'] ) && $_POST['sp_new'] ) ? 'on' : 'off';
	update_post_meta( $post_id, 'sp_new', $chk );
	$chk2 = ( isset( $_POST['sp_modal'] ) && $_POST['sp_modal'] ) ? 'on' : 'off';
	update_post_meta( $post_id, 'sp_modal', $chk2 );
}

// Order Categories by Wessley Roche
function wpguy_category_order_init(){
	
	function wpguy_category_order_menu(){
		if (function_exists('add_submenu_page')) {
			add_submenu_page("edit.php?post_type=bookmark", 'Order', 'Order', 4, "wpguy_category_order_options", 'wpguy_category_order_options');
		}
	}

	function wpguy_category_order_scriptaculous() {
		if($_GET['page'] == "wpguy_category_order_options"){
			wp_enqueue_script('scriptaculous');
		} 
	}
	
	add_action('admin_head', 'wpguy_category_order_options_head'); 
	add_action('admin_menu', 'wpguy_category_order_menu');
	add_action('admin_menu', 'wpguy_category_order_scriptaculous');
	
	add_filter('get_terms', 'wpguy_category_order_reorder', 10, 3);
	
	// This is the main function. It's called every time the get_terms function is called.
	function wpguy_category_order_reorder($terms, $taxonomies, $args){
		
		// No need for this if we're in the ordering page.
		if(isset($_GET['page']) && $_GET['page'] == "wpguy_category_order_options"){ 
			return $terms;
		}
		
		// Apply to categories only and only if they're ordered by name.
		if($taxonomies[0] == "category" && $args['orderby'] == 'name'){ // You may change this line for: `if($taxonomies[0] == "category" && $args['orderby'] == 'custom'){` if you wish to still be able to order by name.
			$options = get_option("wpguy_category_order");
		
			if(!empty($options)){
				
				// Put all the order strings together
				$master = "";
				foreach($options as $id => $option){
					$master .= $option.",";
				}
				
				$ids = explode(",", $master);
				
				// Add an 'order' item to every category
				$i=0;
				foreach($ids as $id){
					if($id != ""){
						foreach($terms as $n => $category){
							if(is_object($category) && $category->term_id == $id){
								$terms[$n]->order = $i;
								$i++;
							}
						}
					}
					
					// Add order 99999 to every category that wasn't manually ordered (so they appear at the end). This just usually happens when you've added a new category but didn't order it.
					foreach($terms as $n => $category){
						if(is_object($category) && !isset($category->order)){
							$terms[$n]->order = 99999;
						}
					}
				
				}
				
				// Sort the array of categories using a callback function
				usort($terms, "wpguy_category_order_compare");
			}
		
		}
		
		return $terms;
	}
	
	// Compare function. Used to order the categories array.
	function wpguy_category_order_compare($a, $b) {
		
		if ($a->order == $b->order) {
			
			if($a->name == $b->name){
				return 0;
			}else{
				return ($a->name < $b->name) ? -1 : 1;
			}
			
		}
		
	    return ($a->order < $b->order) ? -1 : 1;
	}
	
	function wpguy_category_order_options(){
		if(isset($_GET['childrenOf'])){
			$childrenOf = $_GET['childrenOf'];
		}else{
			$childrenOf = 0;
		}
		
		
		$options = get_option("wpguy_category_order");
		$order = $options[$childrenOf];
		
		
		if(isset($_GET['submit'])){
			$options[$childrenOf] = $order = $_GET['category_order'];
			update_option("wpguy_category_order", $options);
			$updated = true;
		}
		
		// Get the parent ID of the current category and the name of the current category.
		$allthecategories = get_categories("hide_empty=0");
		if($childrenOf != 0){
			foreach($allthecategories as $category){
				if($category->cat_ID == $childrenOf){
					$father = $category->parent;
					$current_name = $category->name;
				}
			}
			
		}
		
		// Get only the categories belonging to the current category
		$categories = get_categories("hide_empty=0&child_of=$childrenOf");
		
		// Order the categories.
		if($order){
			$order_array = explode(",", $order);
		
			$i=0;
		
			foreach($order_array as $id){
				foreach($categories as $n => $category){
					if(is_object($category) && $category->term_id == $id){
						$categories[$n]->order = $i;
						$i++;
					}
				}
				
				
				foreach($categories as $n => $category){
					if(is_object($category) && !isset($category->order)){
						$categories[$n]->order = 99999;
					}
				}

			}
			
			usort($categories, "wpguy_category_order_compare");
			
			
		}
		
		?>
		
		<div class='wrap'>
			
			<?php if(isset($updated) && $updated == true): ?>
				<div id="message" class="fade updated"><p>Changes Saved.</p></div>
			<?php endif; ?>
			
			<form action="<?php bloginfo("wpurl") ?>/wp-admin/edit.php" class="GET">
				<input type="hidden" name="post_type" value="bookmark" />
				<input type="hidden" name="page" value="wpguy_category_order_options" />
				<input type="hidden" id="category_order" name="category_order" size="500" value="<?php echo $order; ?>">
				<input type="hidden" name="childrenOf" value="<?php echo $childrenOf; ?>" />
			<h2>Order</h2>
			<?php if($childrenOf != 0): ?>
			<p><a href="<?php bloginfo("wpurl"); ?>/wp-admin/edit.php?page=wpguy_category_order_options&amp;childrenOf=<?php echo $father; ?>">&laquo; Back</a></p>
			<h3><?php echo $current_name; ?></h3>
			<?php else: ?>
			<?php endif; ?>
			
			<div id="container">
				<div id="order">
					<?php
					foreach($categories as $category){
						
						if($category->parent == $childrenOf){
							
							echo "<div id='item_$category->cat_ID' class='lineitem'>";
							if(get_categories("hide_empty=0&child_of=$category->cat_ID")){
								echo "<span class=\"childrenlink\"><a href=\"".get_bloginfo("wpurl")."/wp-admin/edit.php?page=wpguy_category_order_options&childrenOf=$category->cat_ID\">More &raquo;</a></span>";
							}
							echo "<h4>$category->name</h4>";
							echo "</div>\n";
							
						}
					}
					?>
				</div>
				<p>Drag to change order</p>
				<p class="submit"><input type="submit" name="submit" Value="Save Order"></p>
			</div>
			</form>
		</div>

		<?php
	}
	
	// The necessary CSS and Javascript
	function wpguy_category_order_options_head(){
		if(isset($_GET['page']) && $_GET['page'] == "wpguy_category_order_options"){
		?>
		<style>
			#container{
				list-style: none;
				width: 225px;
			}
			
			#order{
			}
			
			.childrenlink{
				float: right;
				font-size: 12px;
			}
			
			.lineitem {
				background-color: #ddd;
				color: #000;
				margin-bottom: 5px;
				padding: .5em 1em;
				width: 200px;
				font-size: 13px;
				-moz-border-radius: 3px;
				-khtml-border-radius: 3px;
				-webkit-border-radius: 3px;
				border-radius: 3px;
				cursor: move;
			}
			
			.lineitem h4{
				font-weight: bold;
				margin: 0;
			}
		</style>

		<script language="JavaScript">
			window.onload = function(){
				Sortable.create('order',{tag:'div', onChange: function(){ refreshOrder(); }});
			
				function refreshOrder(){
					$("category_order").value = Sortable.sequence('order');
				}
			}
		</script>
		<?php
		}
	}
	
}

add_action('init', 'wpguy_category_order_init');

// Sidebar
add_action( 'widgets_init', 'spsidebar' );
function spsidebar() {
	register_sidebar(
		array(
			'id' => 'spsidebar',
			'name' => __( 'Sidebar' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
}

// Navigation
	function post_navigation() {
		echo '<div class="navigation">';
		echo '	<div class="next-posts">'.get_next_posts_link('&laquo; Older Entries').'</div>';
		echo '	<div class="prev-posts">'.get_previous_posts_link('Newer Entries &raquo;').'</div>';
		echo '</div>';
	}

// Posted On
	function posted_on() {
		printf( __( '<span class="sep">Posted </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a> by <span class="byline author vcard">%5$s</span>', '' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_author() )
		);
	}

//Move plugins to footer
function footer_enqueue_scripts() {
   remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);
    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
}
add_action('after_setup_theme', 'footer_enqueue_scripts');
?>
