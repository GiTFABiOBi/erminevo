<?php
/**
 * This file adds the Front Page Template to any Genesis Child Theme.
 */

get_header();

global $wpdb;
$allShows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}spettacoli ORDER BY ID DESC", ARRAY_A);
$currentshow = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}spettacoli WHERE new = 1 ORDER BY ID DESC;", ARRAY_A);
$shows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}spettacoli WHERE new != 1 ORDER BY ID DESC", ARRAY_A);
$html = '<div class="container row mx-auto">';

?>

  <header id="home" class="masthead w-100">
    <div class="container w-100 h-100 p-0">
      <div class="main-carousel w-100 h-100 position-relative">
        <div class="static-banner"></div>  
        <section class="splide" aria-label="Splide Basic HTML Example">
          <div class="splide__track">
            <ul class="splide__list">

              <?php foreach ($allShows as $kS => $vS) : ?>
                <li class="splide__slide position-relative">
                    <img src="<?= wp_get_attachment_image_url($vS["id_slide"]) ?>" alt="non c'è tempo, amore 2024">
					
                  <?php if($kS == 0): ?><div class="title__inScena position-absolute">IN REPLICA Sabato 17 Gennaio 2026!</div><?php endif; ?>
					
                  <div class="title__show position-absolute">
                    <?= $vS["titolo"] ?><br>
                    <span><?= $vS["autore"] ?></span>
                  </div>
                  <div class="link__show">
                    <a href="<?= get_bloginfo('url') . $vS["URI"] ?>" class="go_info">scopri <span class="dashicons dashicons-arrow-right-alt go-icon"></span></a>
                  </div>
                </li>
              <?php endforeach; ?>

            </ul>
          </div>
        </section>
      </div>
    </div>
  </header>

  <section id="news" class="page-section py-section"><!-- sezione Novità -->
    <div class="col-lg-12 text-center">
      <h2 class="section-heading text-uppercase">ErmiBlog</h2>
      <h3 class="section-subheading">News, eventi e spettacoli</h3>
      <div class="container px-0">
        <div class="row pt-5 me-0">
			<?php echo apply_shortcodes( '[postsFromDB]' ); ?>
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="page-section py-section"><!-- sezione ermiStoria -->
    <div class="container sez_2 px-0">

		<div class="row mx-0 w-100">
			<div class="sez_2_tit mb-5 col-lg-12 text-center px-0 w-100">
				<h2 class="section-heading text-uppercase">Ermistoria</h2>
				<p>
					Dal cuore pulsante della creatività, in un'indimenticabile sera d’estate del 2014, prende vita la nostra Compagnia Teatrale.<br> 

					Il nostro nome deriva dal greco "interpretare", poiché crediamo che il teatro sia l'arte di dare vita a storie e personaggi attraverso la magia dell'interpretazione.<br>

					Grazie anche all'amicizia che ci unisce abbiamo da quel momento dato vita a spettacoli indimenticabili che hanno incantato il pubblico proprio con la nostra interpretazione.<br>
					Ogni performance è un viaggio unico tra parole e gesti, complicità ed emozioni. <br>
					Noi siamo qui per trasformare il mondo attraverso il potere del palcoscenico e per lasciare un'impronta indelebile nel cuore di chi ci guarda.<br><br>
					Noi siamo Erminévo.
				</p>
				<h3 class="section-subheading">Percorri insieme a noi i nostri successi</h3>
			</div>
		</div>

		<!-- current show -->
		<div class="cont-flip">
			<div class="cont-show current-show d-flex align-items-center">
				<div class="_left">
					<div class="locandina">
						<img src="<?= wp_get_attachment_image_url($currentshow["id_locandina"]) ?>">
					</div>
					<div class="stagione">
						<span><?= $currentshow["stagione"] ?></span>
					</div>
				</div>
				<div class="_right position-relative">
					
					<div class="inScena_sub"><span>IN REPLICA: 17 Gennaio 2026</span></div>
					
					<div class="titolo-show">
						<h3 class="mb-0 subheading"><?= html_entity_decode(htmlentities($currentshow["titolo"])) ?></h3>
					</div>
					<div class="autore-show">
						<h6 class="autore__flip-card">di <?= $currentshow["autore"] ?></h6>
					</div>
					<a class="link-show ermibtn" href="<?= get_bloginfo('url') . $currentshow["URI"] ?>">
						scopri 
						<span class="dashicons dashicons-arrow-right-alt"></span>
					</a>
				</div>
			</div>

			<!-- old shows -->
			<?php
	foreach($shows as $i => $show) { 
		//$locandina = $wpdb->get_row("SELECT guid FROM {$wpdb->prefix}posts WHERE post_type = 'attachment' AND guid LIKE '%".$find."%' AND post_mime_type = 'image/webp'", ARRAY_A);
		//$find = str_replace("/", "", $show["URI"]).".";
			?>
			<!-- old shows -->
			<div class="cont-show <?= $i==0?"_first-top":"" ?> mb-5 d-flex align-items-center">
				<div class="_left">
					<div class="locandina">
						<img src="<?= wp_get_attachment_image_url($show["id_locandina"]) ?>">
					</div>
					<div class="stagione">
						<span><?= $show["stagione"] ?></span>
					</div>
				</div>
				<div class="_right">
					<div class="titolo-show"><h3 class="mb-0"><?= html_entity_decode(htmlentities($show["titolo"])) ?></h3></div>
					<div class="autore-show"><h6>di <?= $show["autore"] ?></h6></div>
					<a class="link-show ermibtn" href="<?= get_bloginfo('url') . $show["URI"] ?>">scopri <span class="dashicons dashicons-arrow-right-alt"></span></a>
				</div>
			</div>

			<?php } ?>
		</div>
		
	</div>
  </section>

  <section id="team" class="page-section py-section"><!-- sezione ermiTeam -->
  <div class="container">
  <div class="row pb-5">
  <div class="col-lg-12 text-center">
  <h2 class="section-heading text-uppercase">Ermiteam</h2>
  <h3 class="section-subheading">I componenti della compagnia</h3>
  </div>
  </div>
  <div class="row">
  <div class="col-6 col-md-4 mb-5">
  <div class="team-member text-center">
  <div class="img-container-1 mx-auto mr-md-auto position-relative">
  <img class="mx-auto rounded-circle" src="https://www.erminevo.it/wp-content/uploads/2020/07/marianna.jpg" />
  <div class="overSocial position-absolute">
  <a href="https://www.instagram.com/marianna_sabadini/" target="_blank" class="text-center w-100" rel="noopener noreferrer"><span class="dashicons dashicons-instagram text-white"></span></a>
  </div>
  </div>
  <h4 class="mt-3">Marianna Sabadini</h4>
  </div>
  </div>
  <div class="col-6 col-md-4 mb-5">
    <div class="team-member text-center">
    <div class="img-container-2 mx-auto ml-md-auto position-relative">
    <img class="mx-auto rounded-circle" src="https://www.erminevo.it/wp-content/uploads/2025/11/emanuela.png" />
    <div class="overSocial position-absolute">
    <a href="https://www.facebook.com/emanuela.sanzogni" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook text-white"></span></a>
    <a href="https://www.instagram.com/emanuelasanzogni/" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-instagram text-white"></span></a>
    </div>
    </div>
    <h4 class="mt-3">Emanuela Sanzogni</h4>
    </div>
  </div>
  <div class="col-6 col-md-4 mb-5">
    <div class="team-member text-center">
    <div class="img-container-2 mx-auto ml-md-auto position-relative">
    <img class="mx-auto rounded-circle" src="https://www.erminevo.it/wp-content/uploads/2020/07/elisa2-1.jpg" />
    <div class="overSocial position-absolute">
		<a href="https://www.facebook.com/eli.ghidini" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook text-white"></span></a>
    <a href="https://www.instagram.com/elisaghidini_/" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-instagram text-white"></span></a>
    </div>
    </div>
    <h4 class="mt-3">Elisa Ghidini</h4>
    </div>
  </div>
  <div class="col-6 col-md-4 mb-5">
    <div class="team-member text-center">
    <div class="img-container-2 mx-auto mr-md-auto position-relative">
    <img class="mx-auto rounded-circle" src="https://www.erminevo.it/wp-content/uploads/2020/07/serena.jpg" />
    <div class="overSocial position-absolute">
    <a href="https://www.facebook.com/sere.nella.391" target="_blank" class="text-center w-100" rel="noopener noreferrer"><span class="dashicons dashicons-facebook text-white"></span></a>
    </div>
    </div>
    <h4 class="mt-3">Serena Zobbio</h4>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="team-member text-center">
    <div class="img-container-2 mx-auto position-relative">
    <img class="mx-auto rounded-circle text-center" src="https://www.erminevo.it/wp-content/uploads/2020/07/claudio.jpg" />
    <div class="overSocial position-absolute">
    <a href="https://www.facebook.com/claudio.garbelli.9" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook text-white"></span></a>
    <a href="https://www.instagram.com/p/CCatyCklhMd/" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-instagram text-white"></span></a>
    </div>
    </div>
    <h4 class="mt-3">Claudio Garbelli</h4>
    </div>
  </div>
  <div class="col-6 col-md-4">
  <div class="team-member text-center">
  <div class="img-container-2 mx-auto mr-md-auto position-relative">
  <img class="mx-auto rounded-circle" src="https://www.erminevo.it/wp-content/uploads/2020/07/andrea.jpg" />
  <div class="overSocial position-absolute">
  <a href="https://www.facebook.com/andrea.tempini" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook text-white"></span></a>
  <a href="https://www.instagram.com/atempini/" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-instagram text-white"></span></a>
  </div>
  </div>
  <h4 class="mt-3">Andrea Tempini</h4>
  </div>
  </div>
  <div class="col-6 col-md-4">
  <div class="team-member text-center">
  <div class="img-container-2 mx-auto ml-md-auto position-relative">
  <img class="mx-auto rounded-circle" src="https://www.erminevo.it/wp-content/uploads/2020/07/fabio2.jpg" />
  <div class="overSocial position-absolute">
  <a href="https://www.facebook.com/fabio.e.basile/" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook text-white"></span></a>
  <a href="https://www.instagram.com/fabbio111/" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-instagram text-white"></span></a>
  </div>
  </div>
  <h4 class="mt-3">Fabio Basile</h4>
  </div>
  </div>
  <div class="col-6 col-md-4 mb-5">
    <div class="team-member text-center">
    <div class="img-container-2 mx-auto mr-md-auto position-relative">
    <img class="mx-auto rounded-circle" src="https://www.erminevo.it/wp-content/uploads/2020/07/pietro.jpg" />
    <div class="overSocial position-absolute">
    <p class="text-white mb-0 w-100" style="font-size: 100px;color: red !important;">&hearts;</p>
    </div>
    </div>
    <h4 class="mt-3">Pietro Cometti</h4>
    </div>
  </div>
  <div class="col-6 col-md-4 mb-5">
    <div class="team-member text-center">
    <div class="img-container-2 mx-auto position-relative">
    <img class="mx-auto rounded-circle text-center" src="https://www.erminevo.it/wp-content/uploads/2020/07/massimo.jpg" />
    <div class="overSocial position-absolute">
    <a href="https://www.facebook.com/massimo.pedrotti.90" target="_blank" class="text-center w-100" rel="noopener noreferrer"><span class="dashicons dashicons-facebook text-white"></span></a>
    </div>
    </div>
    <h4 class="mt-3">Massimo Pedrotti</h4>
    </div>
  </div>

  </div>
  </div>
  </section>
  <section id="friends" class="page-section py-section"><!-- sezione ermiAmici -->
  <div class="container">
  <div class="col-lg-12 text-center">
  <h2 class="section-heading text-uppercase">Ermiamici</h2>
  <h3 class="section-subheading">Le nostre collaborazioni</h3>
  </div>
  <div class="row justify-content-center justify-content-md-between py-5 position-relative">
    <div class="slider">
      <div class="slider-inside">
        <div class="arrowL position-absolute">
          <span class="dashicons dashicons-arrow-left-alt2"></span>
        </div>
        <div class="arrowR position-absolute">
          <span class="dashicons dashicons-arrow-right-alt2"></span>
        </div>
        <div class="slide">
          <div class="card">
            <img class="img-fluid d-block mx-auto" src="https://www.erminevo.it/wp-content/uploads/2020/07/logoBandaSA.png" />
            <a href="https://www.erminevo.it/corpo-musicale-s-apollonio/" class="text-center info-link d-flex align-items-center justify-content-between"><span class="pe-2">scopri</span> <span class="dashicons dashicons-arrow-right-alt scopri-coll"></span></a>
          </div>
        </div>
        <div class="slide">
          <div class="card">
            <img class="img-fluid d-block mx-auto" src="https://www.erminevo.it/wp-content/uploads/2020/07/logoComuneLumezzane.png" />
            <a href="https://www.erminevo.it/comune-di-lumezzane/" class="text-center info-link d-flex align-items-center justify-content-between"><span class="pe-2">scopri</span> <span class="dashicons dashicons-arrow-right-alt scopri-coll"></a>
          </div>
        </div>
        <div class="slide">
          <div class="card">
            <img class="img-fluid d-block mx-auto" src="https://www.erminevo.it/wp-content/uploads/2020/07/logoDM-old.png" />
            <a href="https://www.erminevo.it/dreamusical/" class="text-center info-link d-flex align-items-center justify-content-between"><span class="pe-2">scopri</span> <span class="dashicons dashicons-arrow-right-alt scopri-coll"></a>
          </div>
        </div>
        <div class="slide">
          <div class="card">
            <img class="img-fluid d-block mx-auto" src="https://www.erminevo.it/wp-content/uploads/2020/07/labirintiTeatrali.png" />
            <a href="https://www.erminevo.it/labirinti-teatrali/" class="text-center info-link d-flex align-items-center justify-content-between"><span class="pe-2">scopri</span> <span class="dashicons dashicons-arrow-right-alt scopri-coll"></a>
          </div>
        </div>
        <div class="slide">
          <div class="card">
            <img class="img-fluid d-block mx-auto" src="https://www.erminevo.it/wp-content/uploads/2020/07/amicianzianilogo.png" />
            <a href="https://www.erminevo.it/amici-degli-anziani/" class="text-center info-link d-flex align-items-center justify-content-between"><span class="pe-2">scopri</span> <span class="dashicons dashicons-arrow-right-alt scopri-coll"></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </section>
  <section id="contact" class="page-section py-section"><!-- Contacts -->
  <div class="container">
  <div class="row">
  <div class="col-lg-12 text-center mb-5">
  <h2 class="section-heading text-uppercase">Contatta Erminevo</h2>
  <h3 class="section-subheading">Per info e collaborazioni</h3>
  </div>
  </div>
  <small class="req-field-txt">I campi con <sup style="font-size:18px;vertical-align:-9px;color:rgba(0,0,0,.45)">*</sup> sono obbligatori</small>
	  <?php echo apply_shortcodes( '[contact-form-7 id="2623" title="Contattaci"]' ); ?>
  </div>
  </section>
  <div class="ontop w-100">
    <div class="innerBox d-none">
      <a href="#home"><span class="dashicons dashicons-arrow-up-alt2"></span></a>
    </div>
  </div>
<?php
get_footer();
?>