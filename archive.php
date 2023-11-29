<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		$term = get_queried_object();
		echo $taxonomy = $term->taxonomy;
		echo $term_id  = $term->term_id;		
		$image_field = get_field('category_image', $term);		
		$post_ids = $GLOBALS['wp_embed']->post_ID = $taxonomy . '_' . $term_id;

		echo '<pre>';
		print_r( $image_field['url'] );
		echo '</pre>';
		?>
		<img src="<?php echo $image_field['url'] ?>">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			get_template_part( 'loop' );

		else :

			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
