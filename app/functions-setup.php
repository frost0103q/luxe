<?php
/**
 * Theme setup functions.
 *
 * This file holds basic theme setup functions executed on appropriate hooks with
 * some opinionated priorities based on theme dev, particularly working with child
 * theme devs/users, over the years. I've also decided to use anonymous functions
 * to keep these from being easily unhooked. WordPress has an appropriate API for
 * unregistering, removing, or modifying all of the things in this file.  Those APIs
 * should be used instead of attempting to use `remove_action()`.
 *
 * @package    Luxe
 * @subpackage Includes
 * @author     Brett Mason <brettsmason@gmail.com>
 * @copyright  Copyright (c) 2018, Brett Mason
 * @link       https://github.com/brettsmason/luxe/
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Luxe;

/**
 * Set up theme support. This is where calls to `add_theme_support()` happen.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
add_action( 'after_setup_theme', function() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Switch default core markup for search form, comment form, and comments
	// to output valid HTML5.
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	] );

	// Add title tag support.
	add_theme_support( 'title-tag' );

	// Add selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for editor color palette.
	add_theme_support( 'editor-color-palette',
		[
			'name'  => 'primary',
			'color' => '#2516c7',
		],
		[
			'name'  => 'secondary',
			'color' => '#06031f',
		],
		[
			'name'  => 'tertiary',
			'color' => '#ff6600',
		],
		[
			'name'  => 'black',
			'color' => '#000000',
		],
		[
			'name'  => 'white',
			'color' => '#ffffff',
		]
	);

	// Add support for align wide blocks.
	add_theme_support( 'align-wide' );
}, 5 );

/**
 * Adds support for the custom background feature. This is in its own function
 * hooked to `after_setup_theme` so that we can give it a later priority. This
 * is so that child themes can more easily overwrite this feature. Note that
 * overwriting the background should be done *before* rather than after.
 *
 * @link   https://developer.wordpress.org/reference/functions/add_theme_support/#custom-background
 * @since  1.0.0
 * @access public
 * @return void
 */
add_action( 'after_setup_theme', function() {
	add_theme_support( 'custom-background' );
}, 15 );

/**
 * Register menus.
 *
 * @link   https://developer.wordpress.org/reference/functions/register_nav_menus/
 * @since  1.0.0
 * @access public
 * @return void
 */
add_action( 'init', function() {

	register_nav_menus( [
		'primary' => esc_html_x( 'Primary', 'nav menu location', 'luxe' ),
	] );

}, 5 );

/**
 * Register image sizes.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
add_action( 'init', function() {
	// Set the `post-thumbnail` size.
	set_post_thumbnail_size( 200, 200, true );

	add_image_size( 'custom-thumbnail', 800, 600, true );
}, 5 );

/**
 * Register sidebars.
 *
 * @link   https://developer.wordpress.org/reference/functions/register_sidebar/
 * @since  1.0.0
 * @access public
 * @return void
 */
add_action( 'widgets_init', function() {
	$args = [
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget__title h6">',
		'after_title'   => '</h2>',
	];

	register_sidebar( [
		'id'   => 'primary',
		'name' => esc_html_x( 'Primary', 'sidebar', 'luxe' ),
	] + $args );
}, 5 );
