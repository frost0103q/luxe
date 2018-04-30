<?php
/**
 * SVG icons related functions and filters
 *
 * @package Luxe
 */

namespace Luxe;

/**
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $icon  Required SVG icon filename.
 *     @type string $class Optional SVG class.
 *     @type string $title Optional SVG title.
 *     @type string $desc  Optional SVG description.
 * }
 * @return string SVG markup.
 */
function get_svg( $args = [] ) {
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return esc_html__( 'Please define default parameters in the form of an array.', 'luxe' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return esc_html__( 'Please define an SVG icon filename.', 'luxe' );
	}

	// Set defaults.
	$defaults = [
		'icon'     => '',
		'class'    => '',
		'title'    => '',
		'desc'     => '',
	];

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = ' aria-hidden="true" focusable="false"';

	// Set ARIA.
	$aria_labelledby = '';

	/*
	 * SVGs doesn't use the SVG title or description attributes; non-decorative icons are described with .screen-reader-text.
	 *
	 * However, themes can use the title and description to add information to non-decorative SVG icons to improve accessibility.
	 *
	 * Example 1 with title: <?php echo get_svg( [ 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ] ); ?>
	 *
	 * Example 2 with title and description: <?php echo get_svg( [ 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ] ); ?>
	 *
	 * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
	 */
	if ( $args['title'] ) {
		$aria_hidden     = '';
		$unique_id       = uniqid();
		$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

		if ( $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
		}
	}

	// Sets icon class.
	$class = $args['class'] ? esc_attr( $args['class'] ) : 'icon icon-' . esc_attr( $args['icon'] );

	// Begin SVG markup.
	$svg = file_get_contents( get_theme_file_path( '/public/svg/' . esc_attr( $args['icon'] ) . '.svg' ) );

	// Add ARIA hidden, ARIA labeledby and class markup.
	$svg = str_replace( '<svg', '<svg class="' . $class . '"' . $aria_hidden . $aria_labelledby . 'role="img"', $svg );

	if ( $aria_labelledby ) {
		// Get the intro SVG markup and save as $svg_intro.
		preg_match( '/<svg(.*?)>/', $svg, $svg_intro );

		// Add the title/desc to the markup.
		$svg = str_replace( $svg_intro[0], $svg_intro[0] . $aria_labelledby, $svg );
	}

	return $svg;
}

/**
 * Display SVG icons in social links menu.
 *
 * @param  string  $item_output The menu item output.
 * @param  WP_Post $item        Menu item object.
 * @param  int     $depth       Depth of the menu.
 * @param  array   $args        wp_nav_menu() arguments.
 * @return string  $item_output The menu item output with social icon.
 */
function nav_menu_social_icons( $item_output, $item, $depth, $args ) {
	// Get supported social icons.
	$social_icons = social_links_icons();

	// Change SVG icon inside social links menu if there is supported URL.
	if ( 'social' === $args->theme_location ) {
		foreach ( $social_icons as $attr => $value ) {
			if ( false !== strpos( $item_output, $attr ) ) {
				$item_output = str_replace( $args->link_after, '</span>' . get_svg( [ 'icon' => esc_attr( $value ) ] ), $item_output );
			}
		}
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', __NAMESPACE__ . '\nav_menu_social_icons', 10, 4 );

/**
 * Add dropdown icon if menu item has children.
 *
 * @param  string  $title The menu item's title.
 * @param  WP_Post $item  The current menu item.
 * @param  array   $args  An array of wp_nav_menu() arguments.
 * @param  int     $depth Depth of menu item. Used for padding.
 * @return string  $title The menu item's title with dropdown icon.
 */
function dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {
	if ( 'primary' === $args->theme_location ) {
		foreach ( $item->classes as $value ) {
			if ( 'menu-item-has-children' === $value || 'page_item_has_children' === $value ) {
				$title = $title . get_svg( [ 'icon' => 'angle-down' ] );
			}
		}
	}

	return $title;
}
add_filter( 'nav_menu_item_title', __NAMESPACE__ . '\dropdown_icon_to_menu_link', 10, 4 );

/**
 * Returns an array of supported social links (URL and icon name).
 *
 * @return array $social_links_icons
 */
function social_links_icons() {
	// Supported social links icons.
	$social_links_icons = [
		'facebook.com'  => 'facebook',
		'instagram.com' => 'instagram',
		'twitter.com'   => 'twitter',
	];

	/**
	 * Filter social links icons.
	 *
	 * @param array $social_links_icons Array of social links icons.
	 */
	return apply_filters( 'luxe_social_links_icons', $social_links_icons ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
}