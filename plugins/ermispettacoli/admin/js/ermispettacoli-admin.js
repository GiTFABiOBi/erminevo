(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( window ).load(function() {
		// handles titolo and uri fields
		$("[name=es_titolo]").on("keyup", function() {
			let _titoloVal = $(this).val(), 

				//_uriVal = '/'+_titoloVal.normalize("NFKD").replace(/\p{Diacritic}/gu, "");//dovrebbe trasformare è & é in e!

				_uriVal = '/'+_titoloVal.normalize("NFKD").replace(/[\:;.+"_\s]/g, "-")+'/';

				_uriVal = _uriVal.replace(/[',]+/g, "");
				
				_uriVal = _uriVal.normalize("NFKC").replace(/[\xE8\xE9]/g, "e");


			$("[name=es_uri]").val(_uriVal.toLowerCase());
		});
		// fade out alert div
		$('#wpbody-content .alert-success').delay(5000).fadeOut('slow');
		//fetch actors data list from DB on change event
		$(".es_id_spettacolo").on('change', function() {
    		var valueSelected = $(this).val();
            $.ajax({
				url: ajaxurl ,
				type: 'get',
				data: { 
					action: 'get_char_list',
					id: valueSelected
				},
				success: function(data) {
					
					let chars = JSON.parse(data), _html = '';
					if(typeof chars == 'object'){
						_html += '<h6><b>Lista che appare</b></h6>';
						_html += '<ol>';
						$.each(chars, (i, el) => {
							   _html += '<li>'+(el.ruolo=="regista"?"Regia ":"")+el.attore+(el.ruolo=="regista"?"":" - ")+el.personaggio+'</li>&nbsp;&nbsp;<button type="button" class="button edit-char" data-id="'+el.ID+'" data-sid="'+el.id_spettacolo+'" data-toggle="modal" data-target="#charEdit-'+el.ID+'"><span class="dashicons dashicons-edit"></span></button>&nbsp;<button type="button" class="button delete-char" data-id="'+el.ID+'" data-toggle="modal" data-target="#charDelete-'+el.ID+'"><span class="dashicons dashicons-trash"></span></button><br>'+
						'<!-- EDIT Modal -->'+
						'<div class="modal fade bd-example-modal-sm" id="charEdit-'+el.ID +'" tabindex="-1" aria-labelledby="charEditLabel" aria-hidden="true">'+
						  '<div class="modal-dialog modal-sm">'+
							'<div class="modal-content">'+
							  '<div class="modal-header">'+
								'<h1 class="modal-title fs-5" id="charEditLabel">Modifica</h1>'+
								'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
							  '</div>'+
							  '<div class="modal-body">'+
								   '<div class="form-group">'+
								   	'<select class="showshows">';
									   $.ajax({
											url: ajaxurl ,
											type: 'get',
											data: { 
												action: 'get_shows_list'
											},
											success: function(data) {
												if(data) {
													let ret = JSON.parse(data),
														_value = '',
														_title = '',
														_options = '';
													$.each(ret, function( index, value ) {
														$.each(value, function( i, v ) {
															if(i=="ID"){
																_value = '<option value="'+ v +'" '+(el.id_spettacolo==v?"selected":"")+'>';
																_options += _value;
															}
															if(i=="titolo"){
																_title = v+'</option>';
																_options += _title;
															}
														});
													});
													$(".showshows").html('<option>scegli uno spettacolo</option>'+_options);
												}
											}
										});
									_html += '</select>'+
									'</div>'+
									  '<div class="form-group">'+
										'<label for="recipient-actor" class="col-form-label">Nome e cognome</label>'+
										'<input type="text" name="actor_edit_'+el.ID+'" class="form-control" id="recipient-actor" value="'+el.attore+'">'+
									  '</div>'+
									  '<div class="form-group">'+
										'<label for="recipient-role" class="col-form-label">Ruolo</label>'+
								   		'<input type="text" name="role_edit_'+el.ID+'" class="form-control" id="recipient-role" value="'+el.ruolo+'">'+
									  '</div>';
							console.log(el.ruolo);
								   if((el.ruolo!="regista") && (el.ruolo!="tecnico")) {
								   _html += '<div class="form-group">'+
										'<label for="recipient-char" class="col-form-label">Nome personaggio</label>'+
								   		'<input type="text" name="char_edit_'+el.ID+'" class="form-control" id="recipient-char" value="'+el.personaggio+'">'+
									  '</div>';
								   }
							_html += '</div>'+
							  '<div class="modal-footer">'+
								'<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>'+
								'<button type="button" class="btn btn-primary ms-0 update-char" data-id="'+el.ID+'" data-sid="'+el.id_spettacolo+'">Aggiorna</button>'+
							  '</div>'+
							'</div>'+
						  '</div>'+
						'</div>'+
						'<!-- DELETE Modal -->'+
						'<div class="modal fade" id="charDelete-'+el.ID +'" tabindex="-1" aria-labelledby="charDeleteLabel" aria-hidden="true">'+
						  '<div class="modal-dialog">'+
							'<div class="modal-content">'+
							  '<div class="modal-header">'+
								'<h1 class="modal-title fs-5" id="charDeleteLabel">Elimina</h1>'+
								'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'+
							  '</div>'+
							  '<div class="modal-body">'+
								'Stai per eliminare "'+el.attore+' / '+el.ruolo+'", l\'azione è irreversibile.<br>'+
								'Sicuro di voler continuare?'+
								'<input type="hidden" name="id" value="'+el.ID+'">'+	   
							  '</div>'+
							  '<div class="modal-footer">'+
								'<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>'+
								'<button type="button" class="btn btn-danger ms-0 erase-char" data-id="'+el.ID+'">Elimina</button>'+
							  '</div>'+
							'</div>'+
						  '</div>'+
						'</div>';
						});
						_html += '</ol>';
						$(".es_characters_list").html(_html);
					} else {
						$(".es_characters_list").html('');
						if($(".es_id_spettacolo").val()!='---') {
						$(".es_characters_list").html("<h4>Dati ancora non presenti</h4><p>Compilare i campi per aggiungere nuovi attore/ice: personaggio!</p>");
						}
					}
					// select on change
					var new_sid = '';
					$(".showshows").on('change', function() {
						new_sid = $("option:selected", this).val();
					});
					
					// Modifica attore/personaggio
					$(".es_characters_list .edit-char").on('click', function(e) {
						var _id = $(this).data("id");
						$("#charEdit-"+_id).modal('toggle');
						
						$(".update-char").on('click', function(e) {
							var _id = $(this).data("id"),
							    old_sid = $(this).data("sid"),
							    _newsid = new_sid,
								_actor = $("[name=actor_edit_"+_id+"]").val(),
								_char = $("[name=char_edit_"+_id+"]").val(),
								_role = $("[name=role_edit_"+_id+"]").val();
							console.log(_id, old_sid, _newsid);
							const dati = {'id':_id, 'old_sid':old_sid, 'id_spettacolo':+_newsid, 'nome_cognome':_actor,'personaggio_attivita':_char,'ruolo':_role}
							$.ajax({
								url: ajaxurl ,
								type: 'post',
								data: { 
									action: 'edit_char',
									updateChar: dati
								},
								success: function(data) {
									console.log(data);
									$(".update-success").text("modifica effettuata con successo!").toggleClass("d-none").delay(5000).fadeOut('slow');
									$("#charEdit-"+_id).modal('toggle');
								}
							});
						});
					});
					
					// Elimina attore/personaggio
					$(".es_characters_list .delete-char").on('click', function(e) {
						var _id = $(this).data("id");
						$("#charDelete-"+_id).modal('toggle');
						$(".erase-char").on("click", function() {
							$.ajax({
								url: ajaxurl ,
								type: 'post',
								data: { 
									action: 'delete_char',
									idChar: _id
								},
								success: function(data) {
									$(".update-success").text("Eliminazione effettuata con successo!").toggleClass("d-none").delay(5000).fadeOut('slow');
									$("#charDelete-"+_id).modal('toggle');
								}
							});
						});
						
					});
					
				}
            });
        });
		//Adds new actors data on List after plus icon has been clicked
		$(".create-char").on('click', function() {
			if($(".es_id_spettacolo option:selected").val()!='---') {
				var id_spettacolo = $(".es_id_spettacolo option:selected").val(),
					ruolo = $(".es_ruolo option:selected").val(),
					nome = $("[name=es_nome]").val(),
					cognome = $("[name=es_cognome]").val(),
					
					personaggio = $("[name=es_personaggio_attivita]").val(),
					str = nome+' '+cognome,
					$personaggio = personaggio.toUpperCase();
					if(undefined!==str) {
						var attore = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
							return letter.toUpperCase();
						});
					}
				const inputs = {"id_spettacolo":id_spettacolo, "ruolo": ruolo, "nome_cognome": attore, "personaggio_attivita": $personaggio};
				
				if(id_spettacolo!='---' && ruolo!='---' && nome.length !== 0 && cognome.length !== 0) {
					//if(!$("[name=es_personaggio_attivita]").hasClass("d-no") && personaggio.length!==0) {//input attivato e filled
						$.ajax({
							url: ajaxurl ,
							type: 'post',
							data: { 
								action: 'new_char',
								arrChar: inputs
							},
							success: function(data) {
								$(".es_ruolo").val('---').trigger("change");
								$("[name=es_nome]").val('');
								$("[name=es_cognome]").val('');
								$("[name=es_personaggio_attivita]").val('');
								$(".es_id_spettacolo").val(id_spettacolo).trigger("change");
								$(".update-success").text("Creazione effettuata con successo!").toggleClass("d-none").delay(5000).fadeOut('slow');
							}
						});
					//}
					
				} else {
					$(".box-alert-inputs").toggleClass("d-no");
					/*if($(".box-alert-inputs").hasClass("d-no")) {
						$(".box-alert-inputs").removeClass("d-no");
					}*/
				}
				
				
			} else {
				$(".box-alert-inputs").toggleClass("d-no");
			}
		});
		
		$(".es_personaggio_attivita_tr").addClass("m-b");
		$(".es_personaggio_attivita_tr th").addClass("d-no");
		
		//check empty input on change
		$(".es_id_spettacolo, .es_ruolo").on('change', () => {
			if($(".es_id_spettacolo").val()!='---' || $(".es_ruolo").val()!='---') {
				if(!$(".box-alert-inputs").hasClass("d-no")) {
					$(".box-alert-inputs").addClass("d-no");
				}
			}
		});
		//check empty input on blur
		$("[name=es_nome], [name=es_cognome], [name=es_personaggio_attivita]").on('blur', () => {
			if($("[name=es_nome]").val().length!==0 || $("[name=es_cognome]").val().length!==0) {
				if(!$(".box-alert-inputs").hasClass("d-no")) {
					$(".box-alert-inputs").addClass("d-no");
				}
			}
		});
		//show or not "es_personaggio_attivita" input on "es_ruolo" change
		$(".es_ruolo").on('change', () => {
			if($(".es_ruolo").val()=='attore' || $(".es_ruolo").val()=='attrice') {
				$("[name=es_personaggio_attivita]").removeClass("d-no");
				$("[name=es_personaggio_attivita]").attr("required", "required");
				$(".es_personaggio_attivita_tr th").removeClass("d-no");
				$(".es_personaggio_attivita_tr").removeClass("m-b");
			} else {
				$("[name=es_personaggio_attivita]").addClass("d-no");
				$("[name=es_personaggio_attivita]").removeAttr("required");
				$(".es_personaggio_attivita_tr th").addClass("d-no");
				$(".es_personaggio_attivita_tr").addClass("m-b");
			}
		});
		
		
		
		// hides element for edit show-page
		if($(location).attr("href").indexOf("new")>0 && $(location).attr("href").indexOf("&")>0) {
			$(".sec-att").hide();
			$(".sec-att + ol").hide();
			$(".sec-att ~ table").hide();
		}


		// shows/hides file-name slide (edit-page)
		if($("[name=hidden_img_slide]").val() != '') {
			$("[name=es_id_slide]").after("<span><strong>"+$("[name=hidden_img_slide]").val()+"</strong></span>");
		}
		// shows/hides file-name locandina (edit-page)
		if($("[name=hidden_img_locandina]").val() != '') {
			$("[name=es_id_locandina]").after("<span><strong>"+$("[name=hidden_img_locandina]").val()+"</strong></span>");
		}
		
		//add wrapper div to input ID spettacolo (ermipersonaggi page)
		$(".es_id_spettacolo_tr").wrap( "<div class='col-12 mb-5'></div>" );
		
	});
})( jQuery );
