<?php
/*
 * Template Name: ErmiPost
 * Template Post Type: post
 */
  
get_header();





    echo '<div id="ermipost-template" class="d-lg-flex justify-content-lg-between pt-5">';

        // ARTICOLO
        echo '<div id="ermipost" class="mx-0 mx-lg-auto py-5 pr-md-5">';
        global $wpdb;
        
        $author_name  =  get_the_author_meta( 'display_name' );
        $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $postID = url_to_postid($current_url);
        $rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts WHERE ID = {$postID}", OBJECT);
        $autore = get_the_author_meta( 'display_name', $rows[0]->post_author);
        echo '<div class="mb-3 post__container" style="background-image:url(\''.get_the_post_thumbnail_url( $postID ).'\');"></div>';
        echo '<h2 class="mb-1" style="color:#ff9500;font-size:40px;">'.$rows[0]->post_title.'</h2>';
        echo '<small class="text-secondary"><span class="dashicons dashicons-clock"></span>&nbsp;<b>'.date('d.m.Y', strtotime($rows[0]->post_date)).'</b></small>';
        echo '<small class="text-secondary">&nbsp;&nbsp;articolo di <em>' . $autore . '</em></small><br>';
        $categories = get_the_category($postID);
        $tags = get_the_tags($postID);
        
        echo '<small class="text-secondary cats"><span class="dashicons dashicons-archive"></span>&nbsp;<strong>categorie:</strong> ';
        foreach ($categories as $key => $value) {
            echo '<a style="color:#ff9500;" href="' . get_category_link($value->term_id) . '"><strong>' . $value->name . '</strong></a>';
            if ($value->term_id > 1 && $key !== (count($categories) - 1)) {
                echo '&nbsp;|&nbsp;';
            }
        }
        echo '</small>';
        echo '<br>';
        echo '<small class="text-secondary tags">
				<div class="d-flex flex-wrap">
				<span class="dashicons dashicons-tag"></span>&nbsp;<strong>tags:&nbsp;&nbsp;</strong> ';
        foreach ($tags as $key => $value) {
            echo '<span style="border:1.5px solid #ff9500;color:#ff9500;padding:2px 8px;border-radius:3px;border-top-right-radius:6px;margin-right:4px;white-space:nowrap;margin-bottom:.5rem;"><em>#' . $value->name . '</em></span>';
            // if ($value->term_id > 1 && $key !== (count($tags) - 1)) {
            //     echo '&nbsp;|&nbsp;';
            // }
        }
        echo '</div></small>';
        
        echo '<div class="pt-5 text-justify">'. $rows[0]->post_content . '</div>';
        // var_dump($autore, $postID);
        // echo "<br><br>";
        // var_dump($rows);
        echo '</div>';

        // ULTIMI ARTICOLI
        echo '<div id="ermipost-lastposts" class="py-5 text-right">';

            echo '<div class="last-posts ml-auto" style="display:inline-block;text-align:left">';
                echo '<h4 class="mb-0" style="color:#202944">Articoli recenti</h4>';
                echo '<hr class="my-0 mt-2 mb-5" style="border-color:#202944">';
                echo '<ul id="slider-id" class="slider-class ps-0">';

                    $recent_posts = wp_get_recent_posts(array(
                        'numberposts' => 4, // Number of recent posts thumbnails to display
                        'post_status' => 'publish' // Show only the published posts
                    ));

                    foreach( $recent_posts as $post_item ) {
                        if ($post_item['ID'] != $postID) {
                            echo '<li class="mb-3">';
                            if ($post_item['ID'] != 2615 && $post_item['ID'] != 2617) {
                                echo '<a href="'. get_permalink($post_item['ID']) . '">';
                            }
                                    echo '<div id="ermipost-headerimg" style="position:relative;height:50px;border:1px solid #eee;border-radius:2px;background-image:url(\''. get_the_post_thumbnail_url($post_item['ID']) .'\');background-size:cover;background-position:center;"><p style="transition:.5s;font-weight:900;line-height:16px;background: rgba(0,0,0,.5);border-radius:2px;" class="position-absolute mb-0 text-white text-center w-100 h-100 d-flex justify-content-center align-items-center">' . $post_item['post_title'] . '</p></div>';
                                    //echo '<img src="' . get_the_post_thumbnail_url($post_item['ID']) . '" style="height:50px">';
                                    //echo  '<p style="color:#bf2031;">' . $post_item['post_title'] . '</p>';
                            if ($post_item['ID'] != 2615 && $post_item['ID'] != 2617) {   
                                echo '</a>';
                            }
                            echo '</li>';
                        }
                    }
                echo '</ul>';

            echo '</div>';

        echo '</div>';
    echo '</div>';



get_footer();