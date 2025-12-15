<?php
/**
* Template Name: pagina test
*
* @package WordPress
* @subpackage ErmiTheme
* @since Genesis Sample
*/
 
get_header(); 

// stores Gallery imgs
$echoG = '';
$echoI = '';
$uri = '/coppie-scoppiate/';
$dir = str_replace("/", "", $uri);
$handle = opendir($_SERVER["DOCUMENT_ROOT"].'/wp-content/gallery/'.$dir);
if($handle) {
	while($file = readdir($handle)){
		if($file !== '.' && $file !== '..') {
			if((str_contains($file, ".webp"))) continue;
			$_arrName = explode(".", $file);
			$echoG .= '<div><img class="rounded" src="https://www.erminevo.it/wp-content/gallery/'. $dir . '/' . str_replace(".jpg", "_t.webp", $file) . '" data-name="'.$_arrName[0].'" /></div>';
			$echoI .= '<div class="carousel-item"><img class="d-block w-100 '.$_arrName[0].'" src="https://www.erminevo.it/wp-content/gallery/'. $dir . '/' . $file . '" data-name="'.$_arrName[0].'" /></div>';
		}
	}
	
	
	
	
	
	
} else {
	$echoG .= '<div class="d-flex justify-content-center align-items-center p-4 w-100"><h5 class="mb-0" style="color:#333">Nessuna foto!</h5></div>';
}

?>

<style>
	.page-id-2711 .site-inner {padding: 100px 0;}
	.gallery-container-d {
		padding: 100px;
	}
	.gallery-container-d > div {
		width: 150px;
		height: 100px;
	}
	#carouselExampleFade {width: 100%}
</style>


<div class="gallery-container-d d-flex flex-wrap justify-content-center justify-content-lg-start" style="height:auto;<?php empty($photos)?'border-color:#555!important;':'' ?>">
	<?= $echoG ?>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  
	<div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="false">
		  <div class="carousel-inner">
			  <?= $echoI ?>
		  </div>
		  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		  </button>
		  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		  </button>
		</div>
    </div>
  </div>
	
</div>





	
	

<script>
	jQuery(document).ready(function($) {
		var myModal = new bootstrap.Modal($('#exampleModal'), {
		  keyboard: false
		});
		var imgSrc = '';
		$(".gallery-container-d img").on("click", function () {
			myModal.show();
			imgSrc = $(this).attr("src");
			let _thumbname = $(this).data("name");
			var _modalname = $(".carousel-inner .carousel-item img");
			$(".carousel-inner .carousel-item img").each(function () {
				if($(this)[0].dataset.name == _thumbname) {
					$(this).parent(".carousel-item").addClass("active");
					console.log($(this)[0].dataset.name+ ' è uguale a ' + _thumbname, $(this)[0].currentSrc, $(this).addClass("active"));
				}
			});
		});
		/*
		$(".carousel-control-next").on("click", function () {
			let imgSrc = $(this).attr("src");
			$(".carousel-item.active img").attr("src", imgSrc);
		});*/
	});
</script>
	

<?php get_footer();