<?php
class ReportDomaine {

	protected $default_values_for_create = array(
		'banque_id' => 0,
		'date_valeur' => CREATE_FORM_DEFAUT_TXT_DATE,
		'date_emission' => CREATE_FORM_DEFAUT_TXT_DATE,
		'montant' => 0,
		'type_id' => 0,
		'libelle' => CREATE_FORM_DEFAUT_TXT_LIBELLE,
		'libelle_detail' => CREATE_FORM_DEFAUT_TXT_LIBELLE_COMPL,
		'compte_id' => 0,
		'is_double' => false,
		);

	public function report()
	{
		return $ecritures = Ecriture::whereBetween('date_valeur', ['2015-01-01 00:00:00', '2015-12-31 00:00:00'])->orderBy("date_valeur")->get();
	}


	public function create()
	{
		return new Ecriture($this->default_values_for_create);
	}

	public function find($id)
	{
		return Ecriture::find($id);
	}


	public function save($ecriture)
	{
		$ecriture->save();
	}

	public function all()
	{
		return $ecriture = Ecriture::orderBy("date_valeur")->get();
	}

}
