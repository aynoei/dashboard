<div class="content-wrapper">
	<?php get_template_part('parts/content_header'); ?>

    <!-- Main content -->
    <section class="content"> 
 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
			the_content(); 
		endwhile; endif; 
	?>      
    </section>
    <!-- /.content -->
</div>