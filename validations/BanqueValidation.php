<?php namespace tresorerie\Validations;

use Lib\Validations\ValidationBase;

class BanqueValidation extends ValidationBase
{

	protected $rules = array(
		'nom' => 'required|not_in:CREATE_FORM_DEFAUT_TXT_NOM',
		'description' => 'required|not_in:CREATE_FORM_DEFAUT_TXT_DESCRIPTION', 
		);

	protected $messages = array(
		'nom.required' => 'Vous n’avez pas renseigné de nom.',
		'nom.unique' => 'Il existe déjà une banque portant ce nom.',
		'nom.not_in' => 'Oups… Vous n’avez rien saisi de nouveau dans le champs “Nom” !',
		'description.not_in' => 'Il vaut mieux, soit laisser le champs :attribute vide, soit y saisir une description.',
		);

	public function validerStore($inputs){
		$this->rules['nom'] .= '|unique:banques,nom';
		return $this->valider($inputs);
	}

	public function validerUpdate($inputs, $id){
		$this->rules['nom'] .= "|unique:banques,nom,$id";
		return $this->valider($inputs);
	}

}
