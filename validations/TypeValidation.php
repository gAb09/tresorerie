<?php namespace tresorerie\Validations;

use Lib\Validations\ValidationBase;

class TypeValidation extends ValidationBase
{

	protected $rules = array(
		'nom' => 'required|not_in:CREATE_FORM_DEFAUT_TXT_NOM',
		'description' => 'required|not_in:CREATE_FORM_DEFAUT_TXT_DESCRIPTION',
		'sep_justif' => 'required_with:req_justif|not_in:CREATE_FORM_DEFAUT_TXT_SEPARATEUR',
		);

	public $messages = array(
		'nom.unique' => 'Il existe déjà un type d’écriture portant ce Nom.',
		'nom.not_in' => 'Vous n’avez rien saisi de nouveau dans le champs “Nom”.',
		'description.required' => 'Vous n’avez rien saisi dans le champs “Description”',
		'description.not_in' => 'Vous n’avez rien saisi de nouveau dans le champs “Description”',
		'sep_justif.required_with' => 'Vous n’avez pas saisi de séparateur.',
		'sep_justif.not_in' => 'Vous n’avez rien saisi de nouveau dans le champs “Séparateur”.',
		);

	public function validerStore($inputs){
		$this->rules['nom'] .= '|unique:types,nom';
		return $this->valider($inputs);
	}

	public function validerUpdate($inputs, $id){
		$this->rules['nom'] .= "|unique:types,nom,$id";
		return $this->valider($inputs);
	}

}
