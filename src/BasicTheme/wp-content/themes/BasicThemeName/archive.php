<?php get_header(); ?>

	<?php 
		while ( have_posts() ) {
			the_post();
			get_template_part( 'templates/', 'content' );
		}
	?>

	<?php post_pagination(); ?>
	
<?php get_footer(); ?>