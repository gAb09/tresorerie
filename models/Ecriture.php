<?php

class Ecriture extends Eloquent {

	protected $guarded = array('id');
	protected $softDelete = true;


	/* —————————  RELATIONS  —————————————————*/

	public function type()
	{
		return $this->belongsTo('Type');
	}

	public function compte()
	{
		return $this->belongsTo('Compte');
	}

	public function banque()
	{
		return $this->belongsTo('Banque');
	}

	public function ecriture2()
	{
		return $this->belongsTo('Ecriture', 'soeur_id');
	}

	public function signe()
	{
		return $this->belongsTo('Signe');
	}

	public function statut()
	{
		return $this->belongsTo('Statut');
	}


	/* —————————  DATES  —————————————————*/
	public function getDates()
	{
		return array('created_at', 'updated_at', 'deleted_at', 'date_valeur', 'date_emission');
	}


	/* —————————  ACCESSORS  —————————————————*/
	public function getMontantAttribute($value)
	{
		
		return (double) $value;
	}



	/* —————————  MUTATORS  —————————————————*/

	public function setMontantAttribute($value)
	{

		$value = NombresFr::sauv($value);
		$this->attributes['montant'] = $value;
	}

}
