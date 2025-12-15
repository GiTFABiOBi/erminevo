<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style(
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		genesis_get_theme_version()
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

}

add_action( 'after_setup_theme', 'genesis_sample_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'genesis_sample_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Adds image sizes.
//add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'genesis-post-singular', 702, 526, true );

//REMOVE IMAGE SIZE (FABiO)
add_filter( 'intermediate_image_sizes_advanced', 'prefix_remove_default_images' );
// This will remove the default image sizes.
function prefix_remove_default_images( $sizes ) {
 unset( $sizes['small']); // 150px
 unset( $sizes['medium_large']); // 768px
 unset( $sizes['large']); // 1024px
 return $sizes;
}

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
//remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}

function add_font() {
    $font_script = <<<'EOD'
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
EOD;
    echo $font_script;
}
add_action('wp_head', 'add_font');

/**
 * Enqueue Bootstrap scripts and styles - modifica Fabio
 */
 function your_script_enqueue() {
    wp_enqueue_script( 'bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js');

    wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', false, NULL, 'all' );

    wp_enqueue_script( 'flickity', get_stylesheet_directory_uri() . '/js/flickity.pkgd.min.js', false, NULL, 'all');
    wp_enqueue_script( 'js_splide', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js');
   	wp_enqueue_style( 'flickity', get_stylesheet_directory_uri() . '/css/flickity.min.css', false, NULL, 'all' );
    wp_enqueue_style( 'hamburger_menu', get_stylesheet_directory_uri() . '/css/hamburgers.min.css');
    wp_enqueue_style( 'css_splide', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css');
 }
 add_action( 'wp_enqueue_scripts', 'your_script_enqueue' );

 //carica  CDN Bootstrap nell'admin
function wpdocs_enqueue_custom_admin_bootsrap() {
    wp_enqueue_script( 'bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', false, NULL, 'all' );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_bootsrap' );


add_filter( 'genesis_seo_title', 'custom_genesis_seo_title', 10, 1 );
/**
 * Replace Site Title text entered in Settings > Reading with custom HTML.
 * @author Fabio Basile
 *
 * @param string original title text
 * @return string modified title HTML
 */
function custom_genesis_seo_title( $title ) {
	$title = '<h1 itemprop="headline" class="site-title">
							<a class="header__logo" title="Homepage" href="' . get_bloginfo('url') . '">
								<img src=' . get_bloginfo('url') . '/wp-content/uploads/2022/05/logo_header.png>
							</a>
						</h1>';
	return $title;
}


//add_action('get_header', 'pages_remove_genesis_nav');
/**
* Remove Primary Navigation Menu from specific Pages
* @author Fabio Basile
*/
function pages_remove_genesis_nav() {
	//$pages = array( 1847, 1849 );
	$pages = array();
	if ( isset($pages) && (count($pages)>0) ) {
		if ( is_page($pages) ) {
			remove_action('genesis_after_header', 'genesis_do_nav');
		}
	}
}

/**
 * 10.05.2022
 * @author Fabio Basile
 * Shortcode per i posts in Homepage.
 * shortcode da utilizzare: [postsFromDB]
 */
add_shortcode('postsFromDB', 'getPostsFromDB');

function getPostsFromDB() {
	$ajaxUrl = admin_url('admin-ajax.php');
	$html = "";
	$html .= "<div class='wrap col-12'>";
	$html .= "<div id='primary' class='content-area'>";
	  $html .= "<div class='col-md-12 content'>";
		$html .= "<div class = 'inner-box content no-right-margin darkviolet'>";
		$html .= "<script type='text/javascript'>";
		$html .= "jQuery(document).ready(function($) {
			  // This is required for AJAX to work on our page
			  var ajaxurl = '" . $ajaxUrl . "'
			  function load_all_posts(page){
				  var data = {
				  page: page,
				  action: 'pagination_posts'
				};
				// Send the data
				$.post(ajaxurl, data, function(response) {
				  $('.pagination_container').html(response);
				});
			  };
			  load_all_posts(1); // Load page 1 as the default
			  $(document).on('click','.pagination-link ul li',function(){
				var page = $(this).attr('p');
				load_all_posts(page);
			  });
			});";
			$html .= "</script>";
		  $html .= "<div class = 'pag_loading'>";
			$html .= "<div class = 'pagination_container'>";
			  $html .= "<div class='post-content'></div>";
			$html .= "</div>";
		  $html .= "</div>";
		$html .= "</div>";
	  $html .= "</div>";
	$html .= "</div>";
  $html .= "</div>";

  return $html;
}

/**
 * Carica Posts con AJAX
 */
add_action( 'wp_ajax_nopriv_pagination_posts', 'pagination_posts' );
add_action( 'wp_ajax_pagination_posts', 'pagination_posts' );

function pagination_posts() {
	global $wpdb;
	$msg = '';
	if(isset($_POST['page'])){
		$page = sanitize_text_field($_POST['page']);
		$cur_page = $page;
		$page -= 1;
		$per_page = 2;
		$previous_btn = true;
		$next_btn = true;
		$start = $page * $per_page;
		// Set the table where we will be querying data
		$table_name = $wpdb->prefix . "posts";
		// Query the posts
		$all_blog_posts = $wpdb->get_results($wpdb->prepare("
			SELECT * FROM " . $table_name . " WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC LIMIT %d, %d", $start, $per_page ) );
		// At the same time, count the number of queried posts
			$count = $wpdb->get_var($wpdb->prepare("
			SELECT COUNT(ID) FROM " . $table_name . " WHERE post_type = 'post' AND post_status = 'publish'", array() ) );
		// Loop into all the posts
		foreach($all_blog_posts as $key => $post){
			// Get Posts' Categories
			$categories = $wpdb->get_results($wpdb->prepare("
			SELECT t.name
			FROM wp_terms t
			LEFT JOIN wp_term_taxonomy tt
			ON tt.term_id = t.term_id
			LEFT JOIN wp_term_relationships tr
			ON tr.term_taxonomy_id = tt.term_taxonomy_id
			WHERE tt.taxonomy = 'category' AND tr.object_id = " . $post->ID . ";" ), ARRAY_A );
			$cats = [];
			foreach ($categories as $key => $val) {
				foreach ($val as $k => $v) {
					$cats[] = $v;
				}
			}
			$cat = implode('|', $cats);
			
			$thumbM = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
			$date = substr($post->post_date, 0, 10);
			$excerpt = substr($post->post_content, 0, 70);
			$msg .= '<div class="post-box col-12 col-md-4 mb-3 mb-md-1 py-2 px-0 px-md-2">';
			$href = 'href="' . $post->guid . '"';
				$msg .= '<a ' . ($post->ID == 2615 || $post->ID == 2617 ? "" : $href) . '><div class="card mx-auto">';
				  $msg .= '<div class="img-post-box col-4 col-lg-5 p-0">';
			
					$msg .= '<img src="'. $thumbM[0] .'" alt="' . $post->post_name . '" class="rounded">';
			
					$msg .= '</div>';
					$msg .= '<div class="card-body col-8 col-lg-7 p-2 pl-3">';
						$msg .= '<small class="d-flex flex-column mb-2 text-secondary"><span class="d-flex align-items-center me-2"><span class="dashicons dashicons-clock"></span>&nbsp;<span style="font-size:12px;">' . $format = date("d.m.Y", strtotime($date)) . '</span></span>
						<span class="d-flex align-items-center">
							<span class="dashicons dashicons-archive"></span>&nbsp;
							<span style="font-size: 12px;">' . $cat . '</span>
						</span>
						</small>';
						$msg .= '<h4 class="card-title mb-0">' . $post->post_title . '</h4>';
					$msg .= '</div>';
				$msg .= '</div></a>';
			$msg .= '</div>';
	  	};
	 
	  $no_of_paginations = ceil($count / $per_page);
	  if ($cur_page >= 7) {
		$start_loop = $cur_page - 3;
		if ($no_of_paginations > $cur_page + 3)
		  $end_loop = $cur_page + 3;
		else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
		  $start_loop = $no_of_paginations - 6;
		  $end_loop = $no_of_paginations;
		} else {
		  $end_loop = $no_of_paginations;
		}
	  } else {
		$start_loop = 1;
		if ($no_of_paginations > 7)
		  $end_loop = 7;
		else
		  $end_loop = $no_of_paginations;
	  }
	  // Pagination Buttons     
	  $pag_container .= "
	  <div class='pagination-link'>
		<ul>";
		  if ($previous_btn && $cur_page > 1) {
			$pre = $cur_page - 1;
			$pag_container .= "<li p='$pre' class='active'><small>Precedente</small></li>";
		  } else if ($previous_btn) {
			$pag_container .= "<li class='inactive'><small>Precedente</small></li>";
		  }
		  for ($i = $start_loop; $i <= $end_loop; $i++) {
			if ($cur_page == $i)
			  $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
			else
			  $pag_container .= "<li p='$i' class='active'>{$i}</li>";
		  }
		  if ($next_btn && $cur_page < $no_of_paginations) {
			$nex = $cur_page + 1;
			$pag_container .= "<li p='$nex' class='active'><small>Successivo</small></li>";
		  } else if ($next_btn) {
			$pag_container .= "<li class='inactive'><small>Successivo</small></li>";
		  }
		  $pag_container = $pag_container . "
		</ul>
	  </div>";
	  echo 
	  '<div class = "pagination-content d-md-flex justify-content-center">' . $msg . '</div>' . 
	  '<div class = "pagination-nav">' . $pag_container . '</div>';
	}
	die();
  }


// remove auto tag <p> from text-editor
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

/** 
 * adds shortlink in topbar to css editor
 */
function add_menu_css_item_c ($wp_admin_bar) {
    $root_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://". $_SERVER['HTTP_HOST'];
    $args = array (
            'id'        => 'css',
            'title'     => 'CSSeditor',
            'href'      => $root_url . '/wp-admin/theme-editor.php'
    );

    $wp_admin_bar->add_node( $args );
}
add_action('admin_bar_menu', 'add_menu_css_item_c', 90);

/** 
 * adds shortlink in topbar to functions.php editor
 */

function add_book_menu_item_f ($wp_admin_bar) {
    $root_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://". $_SERVER['HTTP_HOST'];
    $args = array (
            'id'        => 'myfunctions',
            'title'     => 'functions.php',
            'href'      => $root_url . '/wp-admin/theme-editor.php?file=functions.php'
    );

    $wp_admin_bar->add_node( $args );
}
add_action('admin_bar_menu', 'add_book_menu_item_f', 91);

/** 
 * adds shortlink in topbar to Home(front-page.php)
 */
function add_menu_functions_item_h ($wp_admin_bar) {
    $root_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://". $_SERVER['HTTP_HOST'];
    $args = array (
            'id'        => 'myFrontPage',
            'title'     => 'Home',
            'href'      => $root_url . '/wp-admin/theme-editor.php?file=front-page.php'
    );

    $wp_admin_bar->add_node( $args );
}
add_action('admin_bar_menu', 'add_menu_functions_item_h', 92);

/** 
 * adds shortlink in topbar to scheda tecnica(scheda_tecnica.php)
 */
function add_menu_functions_item_st ($wp_admin_bar) {
$root_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://". $_SERVER['HTTP_HOST'];
    $args = array (
            'id'        => 'SchedaTecnica',
            'title'     => 'Scheda tecnica',
            'href'      => $root_url . '/wp-admin/theme-editor.php?file=page-templates/scheda_tecnica.php&theme=ErmiTheme'
    );

    $wp_admin_bar->add_node( $args );
}
add_action('admin_bar_menu', 'add_menu_functions_item_st', 93);


/**
 * 01.01.2023
 * @author Fabio Basile
 * Shortcode per form "Contattaci" in Homepage.
 * shortcode da utilizzare: [contattaciForm]
 */