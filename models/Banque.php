<?php
use lib\tresorerie\traits\ModelTrait;

class Banque extends Eloquent {
	/* Accès au listes pour input select */
	use ModelTrait;

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
