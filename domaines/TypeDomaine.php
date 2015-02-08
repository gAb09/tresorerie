<?php

class TypeDomaine {

	protected $default_values_for_create = array(
		'nom' => CREATE_FORM_DEFAUT_TXT_NOM,
		'description' => CREATE_FORM_DEFAUT_TXT_DESCRIPTION,
		'req_justif' => 0,
		'sep_justif' => CREATE_FORM_DEFAUT_TXT_SEPARATEUR,
		);


	public function create()
	{
		return new Type($this->default_values_for_create);
	}

	public function getListNom()
	{
		$model = new Type;
		return DomHelper::listForSelect($model, null, 'ByRang');
	}


}