<?php
/*
Plugin Name: FFLA Show Posts
Plugin URI: http://github.com/mybecks/show_posts
Description: Display posts from different categories on a specific page
Version: 0.3.1
Author: Andre Becker
Author URI: la.ffbs.de
License: GPL2
*/

/**
 * Display posts from categories using [show_posts] shortcode
 * Display sticky posts using [show_posts sticky="true"] shortcode
 * Display latests posts using [show_posts latest="true" count="5"] shortcode
 * Display latests missions using [show_posts missions="true" count="5"] shortcode
 * Display last post using [show_posts latest="true" template="full" count="1"] shortcode
 *
 * @author Andre Becker
 * */
function show_posts_handler( $atts, $content=null, $code="" ) {
	
	//code 4 displaying
	extract( shortcode_atts( array(
				'sticky' => 'false',
				'latest' => 'false',
				'missions' => 'false',
				'template' => 'list',
				'count' => '0',
			), $atts ) );

	ob_start();

	if ( 'false' !== $sticky ) {
		show_sticky_posts();
	}
	else if ( 'false' !== $latest ) {
		show_latest_posts( $count, $template );
	}
	else if ( 'false' !== $missions ) {
		show_latest_missions( $count );
	}
	else {
		show_all_posts_from_categories( $template );
	}

	$output_string = ob_get_contents();
	ob_end_clean();

	return $output_string;
}

add_shortcode( 'show_posts', 'show_posts_handler' );

function show_all_posts_from_categories( $display_as ) {

	$args = array(
		'category__in' => array( 1, 4, 5 ),
		'ignore_sticky_posts' => 1,
		'orderby' => 'date',
		'posts_per_page' => -1
	);

	$extra_posts = new WP_Query( $args );

	if( 'list' === $display_as )
		echo "<ul>";

	if ( $extra_posts->have_posts() ) {
		while ( $extra_posts->have_posts() ) {
			$extra_posts->the_post();
			get_template_part( select_template( $display_as ), get_post_format() );
		}

		wp_reset_postdata();
	}
	
	if( 'list' === $display_as)
		echo "</ul>";

}

function show_sticky_posts() {
	$sticky = get_option( 'sticky_posts' );
	$args = array(
		'post__in'  => $sticky,
		'ignore_sticky_posts' => 1,
		'orderby' => 'date',
		'posts_per_page' => 5
	);

	$show_sticky_posts = new WP_Query( $args );
	echo "<ul>";
	if ( $show_sticky_posts->have_posts() ) {
		while ( $show_sticky_posts->have_posts() ) {
			$show_sticky_posts->the_post();
			get_template_part( 'content-list', get_post_format() );
		}

		wp_reset_postdata();
	}
	echo "</ul>";
}

function show_latest_posts( $count, $display_as ) {
	if ( 0 === $count )
		$count = 5;

	$sticky = get_option( 'sticky_posts' );

	$args = array(
		'category__in' => array( 1, 4, 5 ),
		'ignore_sticky_posts' => 1,
		'orderby' => 'date',
		'post__not_in' => $sticky,
		'posts_per_page' => $count
	);

	$latest_posts = new WP_Query( $args );

	if( 'list' === $display_as)
		echo "<ul>";

	if ( $latest_posts->have_posts() ) {
		while ( $latest_posts->have_posts() ) {
			$latest_posts->the_post();
			get_template_part( select_template( $display_as ), get_post_format() );
		}

		wp_reset_postdata();
	}

	if( 'list' === $display_as )
		echo "</ul>";
}

function show_latest_missions( $count ) {
	if ( 0 === $count )
		$count = 5;

	$sticky = get_option( 'sticky_posts' );
	$args = array(
		'post_type' => 'mission',
		'ignore_sticky_posts' => 1,
		'orderby' => 'date',
		'post__not_in' => $sticky,
		'posts_per_page' => $count
	);

	$latest_missions = new WP_Query( $args );

	echo "<ul>";
	if ( $latest_missions->have_posts() ) {
		while ( $latest_missions->have_posts() ) {
			$latest_missions->the_post();
			get_template_part( 'content-list', get_post_format() );
		}

		wp_reset_postdata();
	}
	echo "</ul>";
}

function select_template( $display_as ) {
	if ( $display_as === 'list' ) {
		return 'content-list';
	}

	if ( $display_as === 'full' ) {
		return 'content';
	}

	return 'content-list';
}
?>
