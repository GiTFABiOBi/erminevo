<?php
if (!defined( 'ABSPATH')) {
  die;
}


//UPDATE
if(isset($_POST['update-show'])) {
	
	global $wpdb;
	//SAVE SLIDE IMG
	$namefile_slide = '';
	$lastIdS = 0;
	if ( isset($_FILES['es_id_slide']) ) {
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
	if ( isset($_FILES['es_id_locandina']) ) {
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




	$data = [
		'id_slide' => $lastIdS != 0 ? $lastIdS : $_GET["ids"],
		'id_locandina' => $lastIdL != 0 ? $lastIdL : $_GET["idl"],
		'URI' => $_POST["es_uri"],
		'titolo' => htmlentities(stripslashes($_POST["es_titolo"])),
		'autore' => $_POST["es_autore"],
		'sinossi' => htmlentities(stripslashes($_POST["es_sinossi"])),
		'ingresso_libero' => $_POST["es_ingresso"],
		'new' => $_POST["es_new"],
		'stagione' => $_POST["es_stagione"],
		'codice_yt' => $_POST["es_codice_yt"],
		'img_slide' => $lastIdS != 0 ? $namefile_slide : $_POST["hidden_img_slide"],
		'img_locandina' => $lastIdL != 0 ? $namefile_loc : $_POST["hidden_img_locandina"],
	];
	$where = [
		'ID' => $_POST["update-sid"]
	];
	$format = [
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
	];
	$where_format = ['%s'];

	$upd = $wpdb->update( $wpdb->prefix . 'spettacoli', $data, $where, $format );
	if($upd) {
		 echo '<div class="alert-success upd" data-lid="'.$_GET["lid"].'">
		  Spettacolo con ID '.$_GET["lid"].' modificato con successo!
		</div>';
	}
}
?>
<h1>Elenco Spettacoli</h1>
<?php 
//inserisce inputs hidden di sicurezza
settings_fields( 'all_ermispettacoli_page_group' );// deve essere passato al 1° parametro di register_setting() 

do_settings_sections( 'all_ermispettacoli_page' ); // permette di visualizzare questo form grazie al parametro che viene utilizzato come 4° parametro di add_settings_section() inserito nel file class-ermispettacoli-cpt.php 
