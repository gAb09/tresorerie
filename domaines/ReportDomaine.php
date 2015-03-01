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


	public function create()
	{
		return new Report($this->default_values_for_create);
	}

	public function find($id)
	{
		return Report::find($id);
	}


	public function save($report)
	{
		$report->save();
	}

	public function all()
	{
		return $reports = Report::orderBy("date_emission")->get();
	}

}
