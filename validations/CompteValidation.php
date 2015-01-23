<?php namespace tresorerie\Validations;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Factory;
use Lib\Validations\ValidationBase;


class CompteValidation extends ValidationBase
{
	protected $rules = array(
		"numero" => 'required|numeric|not_in:CREATE_FORM_DEFAUT_TXT_COMPTE_NUMERO|digit', 
		'libelle' => 'required|not_in:CREATE_FORM_DEFAUT_TXT_LIBELLE',
		'pere' => 'not_in:CREATE_FORM_DEFAUT_LIST|inclusion|different:thisid',
		/* Afa : Règle "feuille" : un compte de profondeur > 5 ne peu avoir d'enfant */
		);

	protected $messages = array(
		'numero.numeric' => 'Le champs Numéro ne peut contenir que des chiffres.',
		'numero.not_in' => 'Vous n’avez rien saisi de nouveau dans le champs Numéro.',
		'numero.unique' => 'Il existe déjà un compte avec ce numéro.',
		'numero.digit' => 'Un compte ne peut comporter plus de 6 chiffres.',
		'libelle.required' => 'Vous n’avez pas saisi de “Libellé” !',
		'libelle.not_in' => 'Vous n’avez rien saisi de nouveau dans le champs “Libellé” !',
		'pere.not_in' => 'Vous n’avez pas désigné de "compte père" pour ce compte !',
		'pere.inclusion' => "Le numéro d’un compte doit inclure celui du compte parent.",
		'pere.different' => 'Erreur : ce compte et son compte parent portent le même numéro.',
		);

	public function validerStore($inputs){
		$this->rules['numero'] .= '|unique:comptes,numero';
		return $this->valider($inputs);
	}

	public function validerUpdate($inputs, $id){
		$this->rules['numero'] .= "|unique:comptes,numero,$id";
		return $this->valider($inputs);
	}
}