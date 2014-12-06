<?php
use lib\tresorerie\traits\ModelTrait;

class Statut extends Eloquent {
	use ModelTrait;

	protected static $unguarded = true; // AFA

	/* —————————  RELATIONS  —————————————————*/

	public function ecriture()
	{
		return $this->hasMany('Ecriture');
	}

	protected static $default_values_for_create = [
		'nom' => 'rehja,ea',
		'classe' => 'Saisir un libellé',
		'description' => 'Éventuellement le compléter',
		];

	/* —————————  Validation : règles et messages  —————————————————*/

	public static function StoreRules(){
		// $rules = array(
		// 	'nom' => 'unique:banques,nom|required|not_in:CREATE_FORM_DEFAUT_TXT_NOM',
		// 	'description' => 'not_in:CREATE_FORM_DEFAUT_TXT_DESCRIPTION', // inférieure à 500 caractères
		// 	);
		// return $rules;
	}

	public static function UpdateRules(){
		// $rules = array(
		// 	'nom' => 'required|not_in:CREATE_FORM_DEFAUT_TXT_NOM',
		// 	'description' => 'not_in:CREATE_FORM_DEFAUT_TXT_DESCRIPTION',
		// 	);
		// return $rules;
	}

	public static function Messages(){
		$messages = array(
		// 	'nom.unique' => 'Il existe déjà une banque portant ce nom.',
		// 	'nom.not_in' => 'Oups… Vous n’avez rien saisi de nouveau dans le champs :attribute !',
		// 	'description.not_in' => 'Il vaut mieux, soit laisser le champs :attribute vide, soit y saisir une description.',
			);
		return $messages;
	}




	/* —————————  ACCESSORS  —————————————————*/


	/* —————————  MUTATORS  —————————————————*/


}