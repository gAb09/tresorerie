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

	public function createur()
	{
		return $this->belongsTo('Utilisateur', 'created_by');
	}

	public function modificateur()
	{
		return $this->belongsTo('Utilisateur', 'updated_by');
	}

	public function effaceur()
	{
		return $this->belongsTo('Utilisateur', 'deleted_by');
	}


	/* —————————  SCOPES  —————————————————*/
	static public function scopeSelectBanque($q, $banque)
	{
		if ($banque === null) {
			return $q;
		}
		return $q->whereBanqueId($banque);
	}


	static public function scopeTriPar_libelle($query, $critere_tri, $sens_tri)
	{
		return  $query->orderBy('libelle', $sens_tri)->orderBy('libelle_detail', $sens_tri);
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
