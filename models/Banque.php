<?php
use Lib\traits\ModelTrait;

class Banque extends Eloquent {
  use ModelTrait;
	/* Accès au listes pour input select */

	protected $guarded = array('id'); // AFA
	protected $softDelete = true; // AFA


	protected static $default_values_for_create = array(
		'nom' => CREATE_FORM_DEFAUT_TXT_NOM,
		'description' => CREATE_FORM_DEFAUT_TXT_DESCRIPTION,
		);


	/* —————————  RELATIONS  —————————————————*/

	public function ecriture()
	{
		return $this->hasMany('Ecriture');
	}


	/* —————————  SCOPES  —————————————————*/

	public function scopeIsPrevisionnel($query)
	{
		return $query->where('id', '!=', 0)->get();
	}
}
