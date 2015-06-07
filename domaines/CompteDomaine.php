<?php

class CompteDomaine {

	protected $default_values_for_create = array(
		'numero' => CREATE_FORM_DEFAUT_TXT_COMPTE_NUMERO,
		'libelle' => CREATE_FORM_DEFAUT_TXT_LIBELLE,
		'description_officiel' => '',
		'actif' => 0,
		);

	public function create()
	{
		return new Compte($this->default_values_for_create);
	}

	public function getListParentables()
	{
		$model = new Compte;
		return DomHelper::listForSelect($model, null, 'Parentable');
	}

	public function getListActifs()
	{
		$model = new Compte;
		return DomHelper::listForSelect($model, null, 'Actif', false);
	}

	public function getListActivables()
	{
		$model = new Compte;
		return DomHelper::listForSelect($model, null,  'Activable', false);
	}

}