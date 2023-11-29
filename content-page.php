<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package storefront
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to storefront_page add_action
	 *
	 * @hooked storefront_page_header          - 10
	 * @hooked storefront_page_content         - 20
	 */
	do_action( 'storefront_page' );

	echo 'ACF Changes';
	$acf_page_fields = get_field('page_options');
	$acf_custom_image = get_field('custom_image');
	print_r( $acf_custom_image );
	?>
</article><!-- #post-## -->
