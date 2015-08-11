<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<html class="no-js" lang="en">
    <head>
        <title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title>
        <!--[if IE 6]><![endif]--> <!-- http://bit.ly/abHSdO -->
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1">
        <meta name="robots" content="noodp,noydir,index,follow">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!-- Win8 -->
        <meta name="msapplication-TileImage" content="<?php bloginfo('template_directory'); ?>/img/apple-touch-icon-144x144-precomposed.png">
        <meta name="msapplication-TileColor" content="#000">
        <meta http-equiv="cleartype" content="on">
        <!-- iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <!-- CSS -->
        <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/mobile.css">
        <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/non-mobile.css" media="only screen and (min-width: 768px)">
        <!-- Icons -->
        <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico"> <!-- 16x16 -->
        <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.png"> <!-- 32x32 -->
        <link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_directory'); ?>/img/apple-touch-icon-precomposed.png"> <!-- 57x57 -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/img/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/img/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php bloginfo('template_directory'); ?>/img/apple-touch-icon-144x144-precomposed.png">
        <link href='http://fonts.googleapis.com/css?family=Raleway|Roboto:500' rel='stylesheet' type='text/css'>

        <!-- Rel Author <link href="https://plus.google.com/123"rel="publisher"/>-->
        <!-- RSS <link rel="alternate"type="application/rss+xml"title="" href="/feed"/>-->
        <!-- Opensearch <link rel="search"type="application/opensearchdescription+xml" href="/opensearch.xml"title="">-->
        <!-- Polyfills -->
        <!--[if (lt IE 9) & (!IEMobile)]>
                <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="<?php bloginfo('template_directory'); ?>/js/selectivizr-min.js"></script>
            <script src="<?php bloginfo('template_directory'); ?>/js/respond.min.js"></script>
    <![endif]-->
        <!-- Adaptive Images -->
        <noscript>
        <style>
            @media only screen and (max-device-width: 479px) {
                html { background-image:url(/ai-cookie.php?maxwidth=479); } }
            @media only screen and (min-device-width: 480px) and (max-device-width: 767px) {
                html { background-image:url(/ai-cookie.php?maxwidth=767); } }
            @media only screen and (min-device-width: 768px) and (max-device-width: 991px) {
                html { background-image:url(/ai-cookie.php?maxwidth=991); } }
            @media only screen and (min-device-width: 992px) and (max-device-width: 1381px) {
                html { background-image:url(/ai-cookie.php?maxwidth=1381); } }
            @media only screen and (min-device-width: 1382px) {
                html { background-image:url(/ai-cookie.php?maxwidth=unknown); } }
            </style>
            </noscript>
            <script>document.cookie = 'resolution=' + Math.max(screen.width, screen.height) + ("devicePixelRatio"in window ? "," + devicePixelRatio : ",1") + '; path=/';</script>
            <?php wp_head(); ?>
        </head>

        <body>
            <header>
                <?php get_search_form(); ?> 
                <div class="wrap">
                <div class="logo"><a href="#" class="fx top-item"><img src="<?php bloginfo('template_directory'); ?>/img/logo.png"></a></div>
                <ul class="myaccount">
                    <li><a href="#" class="fx btnSearch"><span class="icon-search"></span> <em>Search</em></a></li>
                    <?php if (is_user_logged_in()) { ?>
                        <li><a href="/wp-admin/edit.php?post_type=bookmark" class="fx modal" data-fancybox-type="iframe"><span class="icon-cog"></span> <em>Manage</em></a></li>
                        <li><a href="/wp-login.php?action=logout" class="fx"><span class="icon-off"></span> <em>Logout</em></a></li>
                        <? } else { ?>
                        <li><a href="/wp-admin/" class="fx modal" data-fancybox-type="iframe"><span class="icon-off"></span> <em>Login</em></a></li>
                        <? } ?>
                    </ul>
                    <ul class="sub-menus">
                        <h3>Categories</h3>
                        <li class="close-nav"><a title="Home" class="fx" href="<?php echo get_site_url(); ?>"><span class="icon-home"></span> Home</a></li>
                        <?php
                        //for each category, show all posts
                        $cat_args = array(
                            'type' => 'bookmark',
                            'hide_empty' => 0,
                            'orderby' => 'name',
                            'order' => 'ASC'
                        );
                        $categories = get_categories($cat_args);
                        foreach ($categories as $category) {
                            $args = array(
                                'post_type' => 'bookmark',
                                'post_status' => array('publish'),
                                'posts_per_page' => -1,
                                'category__in' => array($category->term_id),
                                'order' => 'ASC',
                                'orderby' => 'menu_order'
                            );
                            // Show Private Bookmarks if logged in
                            if (is_user_logged_in()) {
                                $args['post_status'][] = 'private';
                            }
                            $posts = get_posts($args);
                            if ($posts) {
                                echo '<li class="close-nav"><a class="fx" href="/#' . $category->slug . '" title="' . sprintf(__("View all Bookmarks in %s"), $category->name) . '" ' . '><span class="icon-right-dir"></span> ' . $category->name . '</a></li> ';
                            } // if ($posts
                        } // foreach($categories
                        ?>
                        <li class="close-nav"><a title="Quicklinks" class="fx" href="#quicklinks"><span class="icon-globe"></span> Quicklinks</a></li>
                        <div class="pageList"><?php
                    $args = array(
                        'title_li' => '<h3>Pages</h3>',
                        'link_before' => '<span class="icon-right-dir"></span> ',
                        'post_type' => 'page',
                        'post_status' => 'publish',
                        'sort_column' => 'menu_order, post_title'
                    );
                    wp_list_pages($args);
                        ?>
                        <a href="#" class="fx close-nav"><span class="icon-up-open"></span> CLOSE </a>
                    </div>
            </div><!--Wrap-->
        </header>
                    <div class="page-wrap">
                        <?php } //end if user is logged in ?>