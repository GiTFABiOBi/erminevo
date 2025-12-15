<?php
if (!defined( 'ABSPATH')) {
  die;
}

//$action = '/wp-admin/admin.php?page=new_ermispettacoli_page';
global $wpdb;

//CREATE
if(isset($_POST['submit-new'])) 
{
	//SAVE SLIDE IMG
	$namefile_slide = '';
	$lastIdS = 0;
	//var_dump($_FILES['es_id_slide']["name"] != '', $_FILES['es_id_locandina']["name"] != '');die;
	if ( $_FILES['es_id_slide']["name"] != '' ) {
		$upload_dir = wp_upload_dir();
		$_yearDir = date('Y/m');
		$target_dir_location = $upload_dir["basedir"]. '/' .$_yearDir .'/';
		$namefile_slide = $_FILES['es_id_slide']['name'];
		$tmp_name = $_FILES['es_id_slide']['tmp_name'];
		$post_name = array_shift(explode(".", $namefile_slide));

		$table = $wpdb->prefix.'postmeta';
		$postmeta_guid = $_yearDir .'/'. $namefile_slide;

		$checkFile = file_exists($target_dir_location . $namefile_slide);


		
		if (!$checkFile) {
			// Dati passati a funzione wp_insert_post()
			$myFile = array(
				'post_title' => $namefile_slide,
				'post_status' => 'inherit',
				'ping_status' => 'closed',
				'post_author' => get_current_user_id(),
				'post_name' => $post_name,
				'post_type' => 'attachment',
				'post_mime_type' => $_FILES['es_id_slide']['type'],
				//'guid' => $_guid,
			);
			//salva dati su tabella wp_posts
			wp_insert_post($myFile);

			$lastIdS = $wpdb->insert_id;

			//salva dati su tabella wp_postmeta
			update_post_meta($lastIdS,'_wp_attached_file',$postmeta_guid);

			////$wpdb->insert($table,$metadata,$format);

			//salva file nei media (cartella /uploads)
			move_uploaded_file( $tmp_name, $target_dir_location . $namefile_slide );
		}
	}

	//SAVE LOCANDINA IMG
	$namefile_loc = '';
	$lastIdL = 0;
	if ( $_FILES['es_id_locandina']["name"] != '' ) {
		$upload_dir = wp_upload_dir();
		$_yearDir = date('Y/m');
		$target_dir_location = $upload_dir["basedir"]. '/' .$_yearDir .'/';
		$namefile_loc = $_FILES['es_id_locandina']['name'];
		$tmp_name = $_FILES['es_id_locandina']['tmp_name'];
		$post_name = array_shift(explode(".", $namefile_loc));

		$table = $wpdb->prefix.'postmeta';
		$postmeta_guid = $_yearDir .'/'. $namefile_loc;

		$checkFile = file_exists($target_dir_location . $namefile_loc);


		
		if (!$checkFile) {
			// Dati passati a funzione wp_insert_post()
			$myFile = array(
				'post_title' => $namefile_loc,
				'post_status' => 'inherit',
				'ping_status' => 'closed',
				'post_author' => get_current_user_id(),
				'post_name' => $post_name,
				'post_type' => 'attachment',
				'post_mime_type' => $_FILES['es_id_locandina']['type'],
				//'guid' => $_guid,
			);
			//salva dati su tabella wp_posts
			wp_insert_post($myFile);

			$lastIdL = $wpdb->insert_id;

			//salva dati su tabella wp_postmeta
			update_post_meta($lastIdL,'_wp_attached_file',$postmeta_guid);

			////$wpdb->insert($table,$metadata,$format);

			//salva file nei media (cartella /uploads)
			move_uploaded_file( $tmp_name, $target_dir_location . $namefile_loc );
		}
	}


	$ins = $wpdb->insert(
		$wpdb->prefix."spettacoli",
		array(
			'id_slide' => $lastIdS,
			'id_locandina' => $lastIdL,
			'URI' => $_POST["es_uri"],
			'titolo' => htmlentities(stripslashes($_POST["es_titolo"])),
			'autore' => $_POST["es_autore"],
			'sinossi' => htmlentities(stripslashes($_POST["es_sinossi"])),
			'ingresso_libero' => $_POST["es_ingresso"],
			'new' => $_POST["es_new"],
			'stagione' => $_POST["es_stagione"],
			'codice_yt' => $_POST["es_codice_yt"],
			'img_slide' => $namefile_slide,
			'img_locandina' => $namefile_loc,
		),
		array(
			'%d',
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%d',
			'%s',
			'%s',
			'%s',
			'%s'
		)
	);
	
	//create and publish post "page" about show
	$args = array(
	  'post_title'    => wp_strip_all_tags( $_POST["es_titolo"] ),
	  'post_type'	  => 'page',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'page_template' => 'page-templates/scheda_tecnica.php'
	);

	// Insert the post into the database
	wp_insert_post( $args );

	
	if($ins) {
	  echo '<div class="alert-success">
			  Spettacolo aggiunto con successo!
			</div>';
	}

}


if(isset($_GET["sid"]) && $_GET["sid"]>0) { ?>
	<h1 class="my-4">Modifica Spettacolo</h1>
	<h3>Dati Spettacolo</h3>
	<ol>
	<li>Modifica i campi interessati</li>
	<li>Fai click su <button type='button' class='button' style='line-height: 0;min-height: 20px;height: 10px;padding: 3px;cursor:default;'>Aggiorna</button> per salvare le modifiche</li>
	</ol>
	<form method="post" action="<?= get_bloginfo('url') ?>/wp-admin/admin.php?page=all_ermispettacoli_page&sid=<?= $_GET["sid"] ?>&lid=<?= $_GET["lid"] ?>&ids=<?= $_GET["ids"] ?>&idl=<?= $_GET["idl"] ?>" enctype="multipart/form-data">
<?php } 
else 
{ ?>
	<h1 class="my-4">Nuovo Spettacolo</h1>
	<h3>Dati Spettacolo</h3>
	<ol>
	<li>Compila i campi obbligatori ( <sup style='font-size:13px;color:#c71515;vertical-align: baseline;'>*</sup> )</li>
	<li>Fai click su <button type='button' class='button' style='line-height: 0;min-height: 20px;height: 10px;padding: 3px;cursor:default;'>Salva</button> per aggiungere un nuovo spettacolo</li>
	</ol>
	<form method="post" action="<?= get_bloginfo('url') ?>/wp-admin/admin.php?page=new_ermispettacoli_page" enctype="multipart/form-data">
<?php } ?>
 <!-- inserisce inputs hidden di sicurezza -->
<?php settings_fields( 'ermispettacoli_page_group' );// deve essere passato al 1° parametro di register_setting() 

	do_settings_sections( 'ermispettacoli_page' ); // permette di visualizzare questo form grazie al parametro che viene utilizzato come 4° parametro di add_settings_section() inserito nel file class-ermispettacoli-section-page.php ?>
		<p>
		<?php if(isset($_GET["sid"]) && $_GET["sid"]>0) { ?>
			<input type="submit" name="update-show" class="button" value="Aggiorna">
			<input type="hidden" name="update-sid" value="<?= $_GET["sid"] ?>">
			<a href="/wp-admin/admin.php?page=all_ermispettacoli_page" class="button">torna a elenco spettacoli</a>
		<?php } else { ?>
			<input type="submit" name="submit-new" class="button" value="Salva">
		<?php } ?>
		</p>
	</form>