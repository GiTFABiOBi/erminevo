<?php
if (!defined( 'ABSPATH')) {
  die;
}
?>


<h1 class="my-4">Elenco Attore/Personaggio</h1>
<div class="update-success alert-success d-none"></div>
<!-- inserisce inputs hidden di sicurezza -->
<?php settings_fields( 'ermipersonaggi_page_group' );// deve essere passato al 1° parametro di register_setting() 

do_settings_sections( 'ermipersonaggi_page' ); // permette di visualizzare questo form grazie al parametro che viene utilizzato come 4° parametro di add_settings_section() inserito nel file class-ermispettacoli-section-page.php ?>

