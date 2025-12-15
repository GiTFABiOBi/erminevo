<?php
/**
* Template Name: scheda-tecnica
*
* @package WordPress
* @subpackage ErmiTheme
* @since Genesis Sample
*/

get_header();

global $wpdb;
global $post;
$uri = $_SERVER['REQUEST_URI'];
$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}spettacoli WHERE URI = %s", $uri);
$rowData = $wpdb->get_row($query, ARRAY_A);
if ( $wpdb->last_error ) {
  echo 'wpdb error: ' . $wpdb->last_error;
}
$ytID = $rowData["codice_yt"];

$_currentDate = strtotime(date('Y-m-d H:i:s'));
?>

<div class="cont-outer position-relative" style="width:90%;margin:0 auto">

  <div class="yt-frame position-absolute" style="z-index:1;">
		<?php if ($ytID != '' && $ytID != '---'): ?>
		  <!-- 1. The <iframe> (and video player) will replace this <div id="player"> tag. -->
    	<div id="player"></div>
		<?php else: ?>
		  <div class="w-100 h-100 bg-secondary d-flex justify-content-center align-items-center"><span class="text-white"><i class="fa fa-video-slash d-block text-center" style="font-size:30px;"></i>Ancora nessun Video!</span></div>
		<?php endif; ?>
	</div>
  <div class="card" style="margin: 280px auto 100px;">
    <div class="box-cbd card-body pt-5 col-12 text-justify">
      <h3 class="card-title mb-0 fst-italic pt-5 mt-5 pt-lg-0 mt-lg-0">
		  <?php if(get_the_id() == 3273) { ?>
				<!--div class="text-red" style="font-size: 20px; line-height: 20px; font-weight: 800;">IN REPLICA Sabato 22 Febbraio 2025 al  Teatro C. Torri di Cologne - Brescia</div-->
		<?php } ?>
		  <?= $rowData["titolo"] ?></h3>
      <h6 class="card-title mb-4 fst-italic"><?= $rowData["autore"] ?></h6>
      <p class="card-text text-justify w-100"><?= $rowData["sinossi"] ?></p>
    </div>
    <?php
    // queries characters/actors
    $queryC = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}spettacoli_personaggi_interpreti WHERE id_spettacolo = %s", $rowData["ID"]);
    $characters = $wpdb->get_results($queryC, ARRAY_A);
    if ( $wpdb->last_error ) {
      echo 'wpdb error: ' . $wpdb->last_error;
    }
    $echoC = '';
    // stores characters/actors
    foreach ($characters as $char) {
      if ($char["ruolo"] == 'regista') {
        $echoC .= "<br><li><strong>Regia</strong> - ".$char["nome_cognome"]."</li>";
        continue;
      }
	  if ($char["ruolo"] == 'tecnico') {
        $echoC .= "<li><strong>Tecnico</strong> - ".$char["nome_cognome"]."</li>";
        continue;
      }
      $echoC .= "<li><strong>".$char["personaggio_attivita"]."</strong> - ".$char["nome_cognome"]."</li>";
    }
    // queries date
    $queryD = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}spettacoli_data_localita WHERE id_spettacolo = %s", $rowData["ID"]);
    $dates = $wpdb->get_results($queryD, ARRAY_A);
    if ( $wpdb->last_error ) {
      echo 'wpdb error: ' . $wpdb->last_error;
    }
    $months = ["01" => "Gennaio","02" => "Febbraio","03" => "Marzo","04" => "Aprile","05" => "Maggio","06" => "Giugno","07" => "Luglio","08" => "Agosto","09" => "Settembre", "10" => "Ottobre","11" => "Novembre","12" => "Dicembre"];
    $m = "";
    $echoD = '';
    // stores date
    foreach ($dates as $date) {
      $dateS = explode('-', $date["data_spettacolo"]);
      foreach ($months as $k => $v) {
        if ($dateS[1] == $k) {
          $m = $v;
        }
      }
      $redText = '';
      $gtclass = '';
      $nctaClass = '';
	  
	  $strDateSHOW = strtotime($date["data_spettacolo"]);
		
      if ($date["debutto"] == "1" && $date["note"] == 'replica_past') {
        $redText = 'text-red';
      }
	  if ($date["debutto"] != "1" && $uri=='/non-ce-tempo-amore/' && $date["note"] == 'replica' && $strDateSHOW > $_currentDate) {
        $nctaStyle = 'style="font-size: 18px; font-weight: 700; color: #f95c37;"';
      }
      /* if($uri=='/beyond-therapy/' && $d_l->data_spettacolo=='2023-10-21') {
        $gtclass = 'gttext';
        echo '<div class="alertBiglietti justBtn">';
      } */
      $echoD .= '<li class="mb-3 '. $redText .'" '.$nctaStyle.'><strong>'. $dateS[2] . " " . $m . " " . $dateS[0] .'</strong>, ' . $date["localita"] . ' ' . ($date["debutto"] === "1" ? "(data di debutto)" : "") . '<br><small><em class="'.($gtclass!=""?$gtclass:"").'">' . ($date["note"] != 'replica' && $date["note"] != 'replica_past' ? $date["note"] : "") . '</em></small></li>';
      /* if($uri=='/beyond-therapy/' && $d_l->data_spettacolo=='2023-10-21') {
        $html .= "<a href='https://secure.webtic.it/angwt/webtic.aspx?pu=aHR0cHM6Ly93d3cud2VidGljLml0L2luZGV4Lmh0bSMvaG9tZT9hY3Rpb249bG9hZExvY2FsJmxvY2FsSWQ9NTU1Ng==&rnd=0.4744693918684473&lng=it&lid=5556&tpl=blue&lvs=bnVsbA==&kid=33&cc=W10=#/event/it/33/5556/386' target='_blank'><span class='ermilink' style='color:var(--ermiyellow);font-weight:600;font-size:16px;'>ACQUISTA I TUOI BIGLIETTI</span></a></div>";
      } */
    }
    // stores Press imgs
    $echoP = '';
    $dir = str_replace("/", "", $uri);
    $pathfile_p = $_SERVER["DOCUMENT_ROOT"].'/wp-content/gallery/'.$dir.'-press';
		  
    //echo "<pre style='color:#000;'>";var_dump($uri, $dir, file_exists($pathfile_p));echo "</pre>";

    if(file_exists($pathfile_p)) {
      $handlePress = opendir($pathfile_p);
      if($handlePress) {
        while($file = readdir($handle)){
          if($file !== '.' && $file !== '..') {
            $arrTitle = explode(".", $file);
            $title = $arrTitle[0];
            if((str_contains($file, ".webp"))) continue;
            $echoP .= '<a title="'.$title.'" href="#" data-featherlight="https://www.erminevo.it/wp-content/gallery/'. $dir .'-press/' . $file . '"><img class="rounded" src="https://www.erminevo.it/wp-content/gallery/'. $dir .'-press/' . str_replace(".jpg", "_t.webp", $file) . '" alt="'.$title.'" /></a>';
          }
        }	  
      } else {
        $echoP .= '<div class="d-flex justify-content-center align-items-center p-0 w-100"><h5 class="mb-0" style="color:#333">Nessun articolo!</h5></div>';
      }
    }
	// stores Gallery imgs
    $echoG = '';
    $echoI = '';
    $dir = str_replace("/", "", $uri);
    $pathfile_g = $_SERVER["DOCUMENT_ROOT"].'/wp-content/gallery/'.$dir;
    if(file_exists($pathfile_g)) {
      $handle = opendir($pathfile_g);
      if($handle) {
        while($file = readdir($handle)){
          if($file !== '.' && $file !== '..') {
            if( (str_contains($file, ".webp")) ) continue;
            
              $_arrName = explode(".", $file);
              $echoG .= '<div>
              <img class="rounded" src="https://www.erminevo.it/wp-content/gallery/'. $dir . '/' . $file . '" data-name="'.$_arrName[0].'" />
              </div>';
              $echoI .= '<div class="carousel-item"><img class="d-block w-100 '.$_arrName[0].'" src="https://www.erminevo.it/wp-content/gallery/'. $dir . '/' . $file . '" data-name="'.$_arrName[0].'" /></div>';
            
            /*$echoG .= '<div><a href="#" data-featherlight="https://www.erminevo.it/wp-content/gallery/'. $dir .'/' . $file . '" class="d-block w-100"><img class="rounded" src="https://www.erminevo.it/wp-content/gallery/'. $dir . '/' . str_replace(".jpg", "_t.webp", $file) . '" /></a></div>';*/
          }
        }	  
      } else {
        $echoG .= '<div class="d-flex justify-content-center align-items-center p-4 w-100"><h5 class="mb-0" style="color:#333">Nessuna foto!</h5></div>';
      }  
    }  
    ?>

    <!--  MOBILE  -->
    <div class="accordion d-block d-lg-none" id="accordionMobile">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Personaggi ed Interpreti
        </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionMobile">
          <div class="accordion-body">
            <ul class="p-0 actors">
              <?= $echoC ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
          <button class="<?= get_the_id() == 3273 ? 'accordion-button' : 'accordion-button collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Date Spettacoli
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse <?= get_the_id() == 3273 ? 'show' : '' ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionMobile">
          <div class="accordion-body">
            <ul class="p-0 replicas">
            <?= $echoD ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Rassegna Stampa
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionMobile">
          <div class="accordion-body">
            <div class="card-columns d-flex flex-wrap" style="<?php if(empty($res4)) echo 'column-count:1!important;' ?>">
              <?= $echoP ?>
            </div>
          </div>
        </div>
      </div>
      <h2 class="gallery__title accordion-button mb-0">Gallery</h2>
      <div class="gallery-cont-m my-3" style="height:auto;">
        <div class="gallery-m d-flex flex-wrap justify-content-center">
          <?= $echoG ?>
        </div>
      </div>
    </div><!-- end Mobile -->
  
    <!--  DESKTOP  -->
    <div class="dataDesktop d-none d-lg-block">
      <div class="d-flex flex-wrap">
        <!-- personaggi/attori + date -->
        <div class="p_i_desktop col-4">
          <h5>
            Personaggi ed Interpreti
          </h5>
          <ul class="p-0 actors">
            <?= $echoC ?>
          </ul>
        </div>
        <div class="d_s_desktop col-8">
            <h5>Date Spettacoli</h5>
            <ul class="p-0 replicas">
              <?= $echoD ?>
              </ul>
        </div>
      </div>
      <!-- rassegna + gallery -->
      <div class="d-flex flex-wrap">
        <div class="r_s_desktop col-4">
          <h5 class="mb-3">Rassegna Stampa</h5>
          <div class="carousel-articles-container d-flex flex-wrap" style="height:auto;">
            <?= $echoP ?>
          </div>
        </div>
        <div class="g_desktop col-8">
          <h5 class="text-center mb-3">Gallery</h5>
          <div class="gallery-container-d d-flex flex-wrap justify-content-center justify-content-lg-start" style="height:auto;">
              <?= $echoG ?>
          </div>
        </div>
      </div>
    </div><!-- end Desktop -->

	<!-- Modal -->
	<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<!-- carousel gallery -->
			<div id="carouselGalleryFade" class="carousel slide carousel-fade p-0 w-100" data-bs-ride="false">
				<div class="carousel-inner">
					<?= $echoI ?>
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#carouselGalleryFade" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#carouselGalleryFade" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
			</div>
			<!-- end carousel gallery -->
		</div>
	  </div>
	</div>
	  
  </div>
</div>

<script>
	/** 
	* Attiva al click il fullscreen sui video di youtube tramite l' API di YouTube
	*/
	// This code loads the IFrame Player API code asynchronously.
	var tag = document.createElement('script');

	tag.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

	// This function creates an <iframe> (and YouTube player)
	// after the API code downloads.
	var player;
	function onYouTubeIframeAPIReady() {
		player = new YT.Player('player', {
		  height: '310',
		  width: '640',
		  videoId: "<?= $ytID ?>",
		  events: {
			//'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		  }
		});
	  }

	  // When playing a video (state=1),
	  // the player should go to fullscreen.
	  function onPlayerStateChange(event) {
		if (event.data == YT.PlayerState.PLAYING) {
		  player.getIframe().requestFullscreen();
		}
	  }
	
		jQuery(document).ready(function($) {
			var myModal = new bootstrap.Modal($('#galleryModal'), {
				keyboard: false
			});
			$(".gallery-container-d img, .gallery-m img").on("click", function () {
				let _thumbname = $(this).data("name");
				var _modalname = $(".carousel-inner .carousel-item img");
				$(".carousel-inner .carousel-item img").each(function () {
					if($(this)[0].dataset.name == _thumbname) {
						$(".carousel-inner .carousel-item").removeClass("active");
						$(this).parent(".carousel-item").addClass("active");
					}
				});
				myModal.show();
			});
		});
</script>



<?php
$query = $wpdb->prepare("SELECT ID, URI, titolo FROM {$wpdb->prefix}spettacoli 
WHERE URI <> %s", $uri);
$shows = $wpdb->get_results($query, ARRAY_A);
//var_dump($shows);
if ( $wpdb->last_error ) {
  echo 'wpdb error: ' . $wpdb->last_error;
}
$othersShow = '';

$othersShow .= '<!-- TONDI ALTRI SPETTACOLI -->';
$othersShow .= '<div class="other__show d-flex flex-wrap justify-content-evenly mb-5">';

foreach ($shows as $key => $show) {
    $othersShow .= '<a href="https://www.erminevo.it'.$show["URI"].'" class="show-id_'.$show["ID"].' mb-2">
                      <div class="card__sch-tec text-center d-flex justify-content-center align-items-center flex-column">
                      <span>vai a</span>
                      <h4>'.html_entity_decode(htmlentities($show["titolo"])).'</h4>
                      </div>
                    </a>';
}

$othersShow .= '</div>';

echo $othersShow;

get_footer();