<?php
/** 
 * Un anuncio; incluye metodos para leerlo y escribirlo a la BD.
 * 
 * "Tablon", aplicacion de ejemplo para el Seminario de Tecnologias Web
 * autor: manuel.freire@fdi.ucm.es, 2011
 * licencia: dominio publico
 */
 
require_once('inc/common.php');
 
class Anuncio {

	public $id;
	public $autor;
	public $titulo;
	public $texto;
	public $fecha;
	
	/**
	 * los constructores no pueden devolver valores; pero un metodo estatico
	 * si que puede.
	 */
	public static function cargaResultado($resultado, $anuncio = null) {
		$array = $resultado->fetchRow(MDB2_FETCHMODE_ASSOC);
		if ( ! $array) return false;
		if ( ! $anuncio) $anuncio = new Anuncio();
		foreach ($array as $key => $value) {
			// efectivamente, estamos usando el valor de una variable
			// como el nombre del campo; ¿no es maravilloso PHP?
			$anuncio->$key = $value;
		}
		return $anuncio;
	}
	
	/**
	 * carga usando un ID
	 */
	public static function porId($mdb2, $id, $anuncio = null) {
		$st = $mdb2->prepare('SELECT * FROM anuncios WHERE id = ?;',
			array('text'));
		db_die_if_error($st);
		$result = $st->execute(array($id));
		db_die_if_error($result);
		return self::cargaResultado($result, $anuncio);
	}
	
	/**
	 * salva este anuncio
	 * si no estalla, es que funciona
	 */
	public function salva($mdb2) {
		$st = $mdb2->prepare('UPDATE anuncios SET' . 
			' (autor = ?, titulo = ?, texto = ?) WHERE id = ?;', 
			array('text', 'text', 'text', 'text'));
		db_die_if_error($st);
		$result = $st->execute(
			array($this->autor, $this->titulo, $this->texto, $this->id));
		db_die_if_error($result);
	}
	
	/**
	 * borra el anuncio actual
	 * si no estalla, es que funciona
	 */
	public function borra($mdb2) {
	 	$st = $mdb2->prepare('DELETE FROM anuncios WHERE id = ?;', array('text'));
		db_die_if_error($st);
		$result = $st->execute(array($this->id));
		db_die_if_error($result);		 
	}
	
	/**
	 * carga todos los anuncios (ojo, que esto habria que paginarlo)
	 * si no estalla, es que funciona
	 */	
	public static function solicitaTodos($mdb2) {
		$st = $mdb2->prepare('SELECT * FROM anuncios ORDER BY id DESC;');
		db_die_if_error($st);
		$result = $st->execute();
		db_die_if_error($result);
		return $result;		
	} 
	
	/**
	 * publica este anuncio en la bd; si no estalla, es que ha funcionado
	 * -- y devuelve la ID del resultado
	 */
	public static function crea($mdb2, $autor, $titulo_, $texto_) {
		$mdb2->beginTransaction();	
		$st = $mdb2->prepare('INSERT INTO anuncios (autor, titulo, texto) ' . 
			' VALUES (?, ?, ?);', array('text', 'text', 'text'));
		db_die_if_error($st);
		$result = $st->execute(array($autor, $titulo_, $texto_));
		db_die_if_error($result);
		$result = $mdb2->lastInsertID('anuncios');
		$mdb2->commit();
		return $result;
	}	 	 
}
