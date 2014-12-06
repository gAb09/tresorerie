<?php
use Carbon\Carbon;


Validator::extend('inclusion', function($field, $value, $params)
{
	// Assigner le numéro du compte Père sélectionné
	$pere =  Compte::find($value);
	$num_pere = $pere->numero;

	// Assigner le numéro saisi pour le compte enfant 
	$num_enfant = Input::get('numero');

	// La règle :
	if (strpos($num_enfant, $num_pere) === false) {
		return false;
	}else{return true;}
});


Validator::extend('digit', function($field, $value, $params)
{
	if (strlen($value) > 6) {
		return false;
	}else{return true;}
});

// aFA Créer règle "feuille" (une feuille ne peut avoir d'enfants)
// Validator::extend('feuille', function($field, $value, $params)
// {
// 	if (strlen($value) > 6) {
// 		return false;
// 	}else{return true;}
// });


