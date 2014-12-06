<?php
use Carbon\Carbon;

Validator::extend('afteremission', function($field, $value, $params)
{
	if ($test = substr_count($value, '-') == 2) {
		$parties = explode('-', $value);
		$valeur = Carbon::createFromDate($parties[2], $parties[1], $parties[0]);
		// var_dump($valeur);
	}
	if ($test = substr_count(Input::get('date_emission'), '-') == 2) {
		$parties = explode('-', Input::get('date_emission'));
		$emission = Carbon::createFromDate($parties[2], $parties[1], $parties[0]);
		// dd($emission);
	}
	if( !isset($valeur) OR !isset($emission) ){
		return false;
	}
	if ($valeur < $emission) {
		return false;
	}else{return true;}
});


Validator::extend('fnumeric', function($field, $value, $params)
{
	$value = NombresFr::sauv($value);
	if (!is_numeric($value)) {
		return false;
	}else{return true;}
});

Validator::extend('positif', function($field, $value, $params)
{
	$value = NombresFr::sauv($value);
	if ($value < 0) {
		return false;
	}else{return true;}
});


Validator::extend('notnull', function($field, $value, $params)
{
	$value = NombresFr::sauv($value);
	settype($value, 'float');
	if ($value == 0) {
		return false;
	}else{return true;}
});

