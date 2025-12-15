<?php

class Info
{
	const PREFIX = 'es-';
	
	public static function connDB() {
		global $wpdb;
		return $wpdb;
	}
	
	public static function allShows() {
		$conn = self::connDB();
		$res = $conn->get_results("SELECT * FROM {$conn->prefix}spettacoli ORDER BY ID DESC", ARRAY_A );
		return $res;
	}
	
	public static function allChars($id) {
		$conn = self::connDB();
		$res = $conn->get_results(
			$conn->prepare("SELECT ID, id_spettacolo, nome_cognome as attore, personaggio_attivita as personaggio, ruolo FROM {$conn->prefix}spettacoli_personaggi_interpreti WHERE id_spettacolo = %s", $id), ARRAY_A
		);
		return $res;
	}
	
	public static function createChar($arrChar) {
		$conn = self::connDB();
		$res = $conn->insert($conn->prefix.'spettacoli_personaggi_interpreti', $arrChar);
		return $res;
	}
	
	public static function updateChar($arr) {
		$idShow = '';
		if($arr["old_sid"]==$arr["id_spettacolo"]) {
			$idShow = $arr["old_sid"];
		} else {
			$idShow = $arr["id_spettacolo"];
		}
		$data = [
			"id_spettacolo" => $idShow,
			"ruolo" => $arr["ruolo"],
			"nome_cognome" => $arr["nome_cognome"],
			"personaggio_attivita" => $arr["personaggio_attivita"],
		];
		$where = [
			"ID" => $arr["id"]
		];
		$conn = self::connDB();
		$res = $conn->update(
			$conn->prefix.'spettacoli_personaggi_interpreti',
			$data,
			$where
		);
		return $res;
	}
	
	public static function deleteChar($id) {
		$conn = self::connDB();
		$res = $conn->delete(
			$conn->prefix.'spettacoli_personaggi_interpreti',
			array(
				"ID" => $id
			)
		);
		return $res;
	}
	
}