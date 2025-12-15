<?php

/**
 * Defines the settings sections "Spettacoli" page
 */

require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-info.php';


class Ermispettacoli_section_page
{
	protected $values = [];
	protected $check = false;
	
	public function __construct() {
		add_action('admin_menu', array($this, 'add_menu_ermispettacoli'));
		add_action( 'admin_init', array($this, 'ermispettacoli_settings_sections_init') );
		//ajax elenco personaggi dal select spettacoli
		add_action('wp_ajax_get_char_list', array($this, 'get_char_list_cb'));
		add_action('wp_ajax_nopriv_get_char_list', array($this, 'get_char_list_cb'));
		//ajax aggiungi nuovo attore/personaggio
		add_action('wp_ajax_new_char', array($this, 'new_char_cb'));
		add_action('wp_ajax_nopriv_new_char', array($this, 'new_char_cb'));
		//ajax modifica attore/personaggio
		add_action('wp_ajax_edit_char', array($this, 'edit_char_cb'));
		add_action('wp_ajax_nopriv_edit_char', array($this, 'edit_char_cb'));
		//ajax elimina attore/personaggio
		add_action('wp_ajax_delete_char', array($this, 'delete_char_cb'));
		add_action('wp_ajax_nopriv_delete_char', array($this, 'delete_char_cb'));
		//ajax elenco spettacoli
		add_action('wp_ajax_get_shows_list', array($this, 'get_shows_list_cb'));
		add_action('wp_ajax_nopriv_get_shows_list', array($this, 'get_shows_list_cb'));
		
	}
	
	/*public function ajax_load_scripts($hook) {
		wp_enqueue_script('my-ajax', '/wp-content/plugins/ermispettacoli/admin/js/ermispettacoli-admin.js', array('jquery'));
	}*/
	
	public function add_menu_ermispettacoli() {
		add_menu_page( 
			'Ermispettacoli',//$page_title
			'Spettacoli', //$menu_title
			'manage_options', //$capability
			'new_ermispettacoli_page', //$menu_slug
			array($this, 'add_new_page_ermispettacoli_cb'), //$callback
			'dashicons-star-filled', //$icon_url
			50 //$position
		);
		add_submenu_page(
			'new_ermispettacoli_page', 
			'Ermispettacoli', 
			'Elenco spettacoli', 
			'manage_options', 
			'all_ermispettacoli_page',
			array($this, 'add_all_page_ermispettacoli_cb')
		);
		add_submenu_page(
			'new_ermispettacoli_page', 
			'Ermipersonaggi', 
			'Gestione attori/personaggi', 
			'manage_options', 
			'ermipersonaggi_page',
			array($this, 'add_page_ermipersonaggi_cb')
		);
	}
	public function add_new_page_ermispettacoli_cb() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'templates/ermispettacoli_settings_form.php';
	}
	public function add_all_page_ermispettacoli_cb() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'templates/all_ermispettacoli_settings_form.php';
	}
	public function add_page_ermipersonaggi_cb() {
		include plugin_dir_path( dirname( __FILE__ ) ) . 'templates/ermipersonaggi_settings_form.php';
	}
	
	public function ermispettacoli_settings_sections_init() {
		//Section: Aggiungi Spettacolo
		add_settings_section(
			'ermispettacoli_section_id',
			'',
			array($this, 'ermispettacoli_section_id_cb'),
			'ermispettacoli_page'
		);
		
		
		
		//Usa callback per recuperare valori dei campi e metterli nell'array $this->values
		add_settings_field(
			'es_campiedit',
			'',
			array($this, 'es_campiedit_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id'
		);
		//Titolo
		add_settings_field(
			'es_titolo',
			'Titolo spettacolo<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_titolo_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_titolo_tr'
			)
		);
		//Uri
		add_settings_field(
			'es_uri',
			'Uri spettacolo<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_uri_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_uri_tr'
			)
		);
		//Autore
		add_settings_field(
			'es_autore',
			'Autore spettacolo<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_autore_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_autore_tr'
			)
		);
		//Sinossi
		add_settings_field(
			'es_sinossi',
			'Sinossi spettacolo<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_sinossi_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_sinossi_tr'
			)
		);
		//Ingresso
		add_settings_field(
			'es_ingresso',
			'Ingresso libero<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_ingresso_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_ingresso_tr'
			)
		);
		//New
		add_settings_field(
			'es_new',
			'Nuovo spettacolo<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_new_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_new_tr'
			)
		);
		//Stagione
		add_settings_field(
			'es_stagione',
			'Stagione<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_stagione_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_stagione_tr'
			)
		);
		//codice YT
		add_settings_field(
			'es_codice_yt',
			'Codice youtube',
			array($this, 'es_codice_yt_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_codice_yt_tr'
			)
		);
		//img slide
		add_settings_field(
			'es_id_slide',
			'Immagine slide',
			array($this, 'es_id_slide_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_id_slide_tr'
			)
		); 
		//img locandina
		add_settings_field(
			'es_id_locandina',
			'Immagine locandina',
			array($this, 'es_id_locandina_cb'),
			'ermispettacoli_page',
			'ermispettacoli_section_id',
			array(
				'class' => 'es_id_locandina_tr'
			)
		); 
		//Spettacoli Form register
		register_setting( 
			'ermispettacoli_page_group', 
			'ermispettacoli_titolo',
		);
		
		
		//Sezione: Elenco Spettacoli
		add_settings_section(
			'all_ermispettacoli_section_id',
			'',
			array($this, 'all_ermispettacoli_section_id_cb'),
			'all_ermispettacoli_page'
		);
		//Page: Elenco Spettacoli
		add_settings_field(
			'ermispettacoli_list',
			'',
			array($this, 'ermispettacoli_list_cb'),
			'all_ermispettacoli_page',
			'all_ermispettacoli_section_id'
		);
		//Elenco Spettacoli Form register
		register_setting( 
			'all_ermispettacoli_page_group', 
			'ermispettacoli_list',
		);
		
		
		//Sezione: Gestione Attori/personaggi
		add_settings_section(
			'ermipersonaggi_section_id',
			'',
			array($this, 'ermipersonaggi_section_id_cb'),
			'ermipersonaggi_page'
		);
		//Section: Contenitore per alert inputs attore/personaggio
		add_settings_section(
			'ermipersonaggi_section_alert_box_id',
			'',
			array($this, 'ermipersonaggi_section_alert_box_id_cb'),
			'ermipersonaggi_page',
		);
		//Section: Lista Personaggi/Attori Spettacolo
		add_settings_section(
			'ermipersonaggi_section_actors_id',
			'',
			array($this, 'ermipersonaggi_section_actors_id_cb'),
			'ermipersonaggi_page',
		);
		//id spettacolo
		add_settings_field(
			'es_id_spettacolo',
			'ID spettacolo<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_id_spettacolo_cb'),
			'ermipersonaggi_page',
			'ermipersonaggi_section_id',
			array(
				'class' => 'es_id_spettacolo_tr me-5'
			)
		);
		//Ruolo
		add_settings_field(
			'es_ruolo',
			'Ruolo<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_ruolo_cb'),
			'ermipersonaggi_page',
			'ermipersonaggi_section_id',
			array(
				'class' => 'es_ruolo_tr'
			)
		);
		//Cognome
		add_settings_field(
			'es_cognome',
			'Cognome<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_cognome_cb'),
			'ermipersonaggi_page',
			'ermipersonaggi_section_id',
			array(
				'class' => 'es_cognome_tr'
			)
		);
		//Nome
		add_settings_field(
			'es_nome',
			'Nome<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_nome_cb'),
			'ermipersonaggi_page',
			'ermipersonaggi_section_id',
			array(
				'class' => 'es_nome_tr'
			)
		);
		//Personaggio/Attività
		add_settings_field(
			'es_personaggio_attivita',
			'Nome Personaggio<sup style="font-size:13px;color:#c71515">*</sup>',
			array($this, 'es_personaggio_attivita_cb'),
			'ermipersonaggi_page',
			'ermipersonaggi_section_id',
			array(
				'class' => 'es_personaggio_attivita_tr'
			)
		);
		//Box Elenco Personaggio/Attività
		add_settings_field(
			'es_characters_list',
			'',
			array($this, 'es_characters_list_cb'),
			'ermipersonaggi_page',
			'ermipersonaggi_section_actors_id'
		);
		//Elenco Spettacoli Form register
		register_setting( 
			'ermipersonaggi_page_group', 
			'ermipersonaggi_page',
		);
		
	}

  public function all_ermispettacoli_section_id_cb() {//Sezione Elenco spettacoli
		return null;
	}


	public function ermispettacoli_section_id_cb() {//Sezione aggiungi spettacolo
		return null;
	}
	
  public function ermipersonaggi_section_alert_box_id_cb() { //Sezione Contenitore per alert inputs attore/personaggio
		return null;
	}
  public function ermipersonaggi_section_actors_id_cb() {// Sezione lista pers./attori spettacolo
		return null;
	}
	
  public function ermipersonaggi_section_id_cb() {// Sezione Gestione Personaggi/attori
		echo "<h3 class='sec-att mt-5'>Dati Personaggi/Attori</h3>";
		echo "<ol class='mb-5'>";
		echo "<li>Seleziona uno spettacolo</li>";
		echo "<li>Compila i campi obbligatori ( <sup style='font-size:13px;color:#c71515;vertical-align: baseline;'>*</sup> ) e fai click su <button type='button' class='button button-primary' style='line-height: 0;min-height: 20px;height: 10px;padding: 0;cursor:default;'><span class='dashicons dashicons-plus-alt2' style='font-size: 13px;height: 15px;'></span></button> per creare/aggiungere una coppia <b><em>attore/personaggio</em></b></li></li>";
		echo "<li>Oppure seleziona uno spettacolo e dalla <em>Lista che appare</em> modifica o cancella i dati relativi ad una persona</li>";
		echo "</ol>";
	}
	/* public function ermispettacoli_section_alert_box_id() {
		return null;
	}
	public function ermispettacoli_section_characters_id() {
		return null;
	} */
	
	public function es_campiedit_cb() {
		if(isset($_GET["sid"]) && $_GET["sid"]>0) {
			$this->check = true;
			global $wpdb;
			$row = $wpdb->get_results(
					$wpdb->prepare("SELECT * FROM {$wpdb->prefix}spettacoli WHERE ID=%s", $_GET["sid"]), ARRAY_A
				   );
			foreach($row as $val) {
				foreach($val as $k => $v) {
					/*if($k!='URI' || $k!='ingresso_libero' || $k!='new') {
						$this->values[$k] = preg_replace('/[\'-_]/g', '', $v);
					} else {
						$this->values[$k] = $v;
					}*/
					$this->values[$k] = $v;
				}
			}
		}
	}
	// start callback fields
	public function es_titolo_cb() {?>
			<input type="text" name="es_titolo" value="<?= (($this->check && $this->values["titolo"]!='')?$this->values["titolo"]:"") ?>" required />
	<?php }
	public function es_uri_cb() {?>
	    <input type="text" name="es_uri" value="<?= (($this->check && $this->values["URI"]!='')?$this->values["URI"]:"") ?>" readonly />
	<?php }
	public function es_autore_cb() {?>
	    <input type="text" name="es_autore" value="<?= (($this->check && $this->values["autore"]!='')?$this->values["autore"]:"") ?>" required />
	<?php }
	public function es_sinossi_cb() {?>
		<textarea name="es_sinossi" rows="4" cols="100" required><?= (($this->check && $this->values["sinossi"]!='')?$this->values["sinossi"]:"") ?></textarea>
	<?php }
	public function es_ingresso_cb() {?>
		<input type="radio" id="es_ingresso1" name="es_ingresso" value="1" <?= (($this->check && $this->values["ingresso_libero"]!='' && $this->values["ingresso_libero"]=="1")?"checked":"") ?> required />
      	<label for="es_ingresso1">Si</label>
		<input type="radio" id="es_ingresso2" name="es_ingresso" value="0" <?= (($this->check && $this->values["ingresso_libero"]!='' && $this->values["ingresso_libero"]=="0")?"checked":"") ?> required />
      	<label for="es_ingresso2">No</label>
	<?php }
	public function es_new_cb() {?>
		<input type="radio" id="es_new1" name="es_new" value="1" <?= (($this->check && $this->values["new"]!='' && $this->values["ingresso_libero"]=="1")?"checked":"") ?> required />
      	<label for="es_new1">Si</label>
		<input type="radio" id="es_new2" name="es_new" value="0" <?= (($this->check && $this->values["new"]!='' && $this->values["new"]=="0")?"checked":"") ?> required />
      	<label for="es_new2">No</label>
	<?php }
	 public function es_stagione_cb() {?>
	    <input type="text" name="es_stagione" value="<?= (($this->check && $this->values["stagione"]!='')?$this->values["stagione"]:"") ?>" required />
	<?php }
	public function es_codice_yt_cb() {?>
	    <input type="text" name="es_codice_yt" value="<?= (($this->check && $this->values["codice_yt"]!='')?$this->values["codice_yt"]:"") ?>" />
	<?php }
	public function es_id_slide_cb() {?>
		<input type="file" name="es_id_slide" value="<?= (($this->check && $this->values["id_slide"]!='')?$this->values["id_slide"]:"") ?>" />
		<input type="hidden" name="hidden_img_slide" value="<?= (($this->check && $this->values["img_slide"]!='')?$this->values["img_slide"]:"") ?>" />
<?php }
	public function es_id_locandina_cb() {?>
			<input type="file" name="es_id_locandina" value="<?= (($this->check && $this->values["id_locandina"]!='')?$this->values["id_locandina"]:"") ?>" />
			<input type="hidden" name="hidden_img_locandina" value="<?= (($this->check && $this->values["img_locandina"]!='')?$this->values["img_locandina"]:"") ?>" />
	<?php }


	
	public function es_id_spettacolo_cb() {
		$results = Info::allShows(); ?>
		<select class="es_id_spettacolo" <?= (!$this->check?"required":"") ?>>
			<option value="---">---Scegli uno spettacolo---</option>
			<?php 
		
				foreach($results as $key => $value) { 
					//if($key=="URI" || $key=="autore" || $key=="sinossi" || $key=="ingresso_libero" || $key=="new") continue;
			?>
			<option value="<?= $value["ID"] ?>"><?= $value["titolo"] ?></option>	    
			<?php 
					
				}
			?>
		</select>
	<?php	
	}
	
	public function es_ruolo_cb() { ?>
		<select class="es_ruolo" <?= (!$this->check?"required":"") ?>>
			<option value="---">---Scegli un ruolo---</option>
			<option value="attore">attore</option>
			<option value="attrice">attrice</option>
			<option value="regista">regista</option>
			<option value="tecnico">tecnico</option>
		</select>
		<input type="hidden" name="es_ruolo_h" value="">
	<?php
	}
	
	public function es_cognome_cb() { ?>
		<input type="text" name="es_cognome" value="" <?= (!$this->check?"required":"") ?> />
	<?php
	}
	
	public function es_nome_cb() { ?>
		<input type="text" name="es_nome" value="" <?= (!$this->check?"required":"") ?> />
	<?php
	}
	
	public function es_personaggio_attivita_cb() { ?>
		<input type="text" name="es_personaggio_attivita" value="" class="d-no" />
		<button type="button" class="button button-primary create-char" title="aggiungi"><span class="dashicons dashicons-plus-alt2"></span></button>
	<?php
	}
	
	public function es_box_alert_cb() { ?>
		<small class="box-alert-inputs d-no">Devi compilare tutti i campi per proseguire</small>
	<?php
	}
	
	public function es_characters_list_cb() { ?>
		<div class="es_characters_list"></div>
	<?php
	}
	
	public function get_char_list_cb() {
		$results;
		$res = Info::allChars($_GET["id"]);
		if($res) {
			$results = $res;
		} else {
			$results = false;
		}
		echo json_encode($results);
		die();	
	}
	
	public function new_char_cb() {
		$arr = $_POST["arrChar"];
		$results;
		$res = Info::createChar($arr);
		if($res) {
			$results = $res;
		} else {
			$results = false;
		}
		echo json_encode($results);
		die();	
	}
	
	public function edit_char_cb() {
		$arr = $_POST["updateChar"];
		$results = false;
		$res = Info::updateChar($arr);
		if($res) {
			$results = $res;
		}
		echo json_encode($results);
		die();	
	}
	
	public function delete_char_cb() {
		$id = $_POST["idChar"];
		$results = false;
		$res = Info::deleteChar($id);
		if($res) {
			$results = $res;
		}
		echo json_encode($results);
		die();	
	}
	
	public function get_shows_list_cb() {
		$results = Info::allShows();
		echo json_encode($results);
		die();
	}
	
	///PAGINA ELENCO SPETTACOLI
	public function ermispettacoli_list_cb() {
        $results = Info::allShows(); ?>
			<input type="hidden" name="ermispettacoli_list" value="">
			<!--table class="wp-list-table widefat striped table-view-list"-->
			<div class="table-responsive">
				<table class="table widefat striped wp-list-table table-view-list">
				  <thead>
					<tr>
					  <th scope="col">#</th>
					  <th scope="col">ID slide</th>
					  <th scope="col">ID locandina</th>
					  <th scope="col">URI</th>
					  <th scope="col">Titolo</th>
					  <th scope="col">Autore</th>
					  <th scope="col">Sinossi</th>
					  <th scope="col">Ingresso</th>
					  <th scope="col">Stagione</th>
					  <th scope="col">Codice YT</th>
					  <th scope="col">IMG Slide</th>
					  <th scope="col">IMG Locandina</th>
					  <th scope="col">Action</th>
					</tr>
				  </thead>
					<tbody>
						<?php 
						$i = 1; $_id = ''; 
						foreach($results as $key => $val) :  ?>
						<tr>
						  <?php 

							foreach($val as $k => $v) : 
							  if($k=="ingresso_libero"){
								 if($v=="1"){
									 $v = 'libero';
								 } else {
									 $v = 'a pagamento';
								 }
							  }
							  if($k=="new") {
								  continue;
							  }
							  if($k=="ID") {
								  $_id = $v;
								  $v = $i;
							  }

								/*if($k=='titolo') {
									$v = preg_replace("/['\\]+/g", '', $v);
								}*/

							  $this->values[$k] = $v;
						  ?>

							
							<td <?= ($k=="ingresso_libero")?"style='text-align:center'":"" ?> >
								
								<?php
								
								if($k=="id_slide") { ?>
									<a href="<?= wp_get_attachment_image_url($val["id_slide"]) ?>" target="_blank"><?= $v ?></a>
								<?php 
								} elseif($k=="id_locandina") { ?>
									<a href="<?= wp_get_attachment_image_url($val["id_locandina"]) ?>" target="_blank"><?= $v ?></a>
								<?php 
								} elseif($k=="titolo" || $k=="sinossi") {
									echo html_entity_decode(htmlentities($v));
								} else { 
									echo $v;
								} ?>
							
							</td>

						  <?php 
							endforeach;
							$i++; 
						  ?>
						  <!--td width="3%"-->
						  <td>
								<a href="<?= get_bloginfo('url') ?>/wp-admin/admin.php?page=new_ermispettacoli_page&sid=<?= $_id ?>&lid=<?= ($i - 1) ?>&ids=<?= $val["id_slide"] ?>&idl=<?= $val["id_locandina"] ?>" class="button edit-show"><span class="dashicons dashicons-edit"></span></a>
						  </td>
							</tr>
							<?php 
						endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php
	}
}