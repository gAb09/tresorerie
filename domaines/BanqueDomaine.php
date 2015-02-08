<?php

class BanqueDomaine {

	protected  $default_values_for_create = array(
		'nom' => CREATE_FORM_DEFAUT_TXT_NOM,
		'description' => CREATE_FORM_DEFAUT_TXT_DESCRIPTION,
		);


	public function create()
	{
		return new Banque($this->default_values_for_create);
	}

	public function getListNom()
	{
		$model = new Banque;
		return DomHelper::listForSelect($model, 'nom');
	}

	public function nomBanque($id)
	{
		return Banque::find($id)->nom;
	}

	public function isPrevisionnel()
	{
		return Banque::all();
	}
}