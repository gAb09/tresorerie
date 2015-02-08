<?php

class Banque extends Eloquent {

	/* Accès au listes pour input select */

	protected $guarded = array('id'); // AFA
	protected $softDelete = true; // AFA



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
