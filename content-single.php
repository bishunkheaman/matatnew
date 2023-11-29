<?php
/**
 * Template used to display post content on single pages.
 *
 * @package storefront
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	do_action( 'storefront_single_post_top' );

	/**
	 * Functions hooked into storefront_single_post add_action
	 *
	 * @hooked storefront_post_header          - 10
	 * @hooked storefront_post_content         - 30
	 */
	do_action( 'storefront_single_post' );

	$post_options = get_field('post_fields');
	
	$tab1 = get_field('first_tab_fields');
	
	$tab2_sub_selection = get_field('tab2_sub_selection');
	print_r($tab2_sub_selection);

	$tab2old = get_field('tab2old');
	echo '<pre>';
	print_r( $tab2old );
	echo '</pre>';
	$tab2new = get_field('tab2new');
	echo '<pre>';
	print_r( $tab2new );
	echo '</pre>';


	/**
	 * Functions hooked in to storefront_single_post_bottom action
	 *
	 * @hooked storefront_post_nav         - 10
	 * @hooked storefront_display_comments - 20
	 */
	do_action( 'storefront_single_post_bottom' );
	?>

</article><!-- #post-## -->
