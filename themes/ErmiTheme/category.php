<?php

get_header();

?>


<div class="container" style="min-height:100vh;margin-top:50px;">

	<h1 style="color:#ff9500;">Tutti i post della categoria <span style="text-transform:uppercase"><?php single_cat_title(); ?></span></h1>
	
	
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
	?>
			
		
			<div class="card w-100 mb-3">
				<div class="card-body d-sm-flex">
					<div class="box-image col-2 rounded overflow-hidden" style="width: 100px;">
						<a href="<?= get_the_permalink(); ?>"><?php the_post_thumbnail( 'thumb' ); ?></a>
					</div>

					<div class="box-content col-lg-10 ms-2">
						<a href="<?= get_the_permalink(); ?>" class="card-text text-dark">
							<h5 class="card-title"><?php the_title(); ?></h5>
							<p><?php the_excerpt(); ?></p>
						</a>
					</div>
				</div>
			</div>
		
	<?php
		}
	}
	?>
	
	<div class="d-flex justify-content-center my-5">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ff9500" viewBox="0 0 24 24"><path d="M17.026 22.957c10.957-11.421-2.326-20.865-10.384-13.309l2.464 2.352h-9.106v-8.947l2.232 2.229c14.794-13.203 31.51 7.051 14.794 17.675z"/></svg>
		<a href="/" style="color:#ff9500;margin-left:10px;">Torna alla HOME</a>
	</div>
	

</div>

<?php
get_footer();