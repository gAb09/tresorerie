<?php

class PrevController extends BaseController {

	// Le critère de classement
	private $order = 'date_valeur';

	public function __construct(){
		$this->prevDom = new PrevDomaine;
		$this->banqueDom = new BanqueDomaine;
		$this->statutDom = new StatutDomaine;
	}

	public function index($annee = null)
	{
		Session::put('ParamEnv.tresorerie.mode_courant', 'previsionnel');

		/* Si l'année n'est pas spécifiée on utilise l'année courante */
		$annee = (is_null($annee))? Session::get('ParamEnv.tresorerie.annee_courante') : $annee;

		/* Si l'édition d’une écriture est demandée depuis cette page, 
		il faut passer (via la session) à EcritureController@update pour la redirection */
		Session::put('page_depart', Request::getUri());

		$banques = $this->banqueDom->isPrevisionnel();

		// Récupérer la collection d'écriture
		$ecritures = $this->prevDom->collectionPrev($banques, $annee);

		/* S'il n'y a pas d'écriture pour la banque demandée : 
		rediriger sur la page pointage par défaut avec un message d'erreur */
		if (!$ecritures){
			$message = 'Il n’y a aucune écriture pour l’année “';
			$message .= $annee;
			$message .= '”';
			return Redirect::back()->withErrors($message);
		}

		/* Puisqu'il y a des écritures */

		/* On peut passer cette année en année courante */
		Session::put('ParamEnv.tresorerie.annee_courante', $annee);

		/* On calcule les reports de l'année précédente */

		/* On peut afficher la vue "prévisionnel" */ 
		return View::make('tresorerie.views.prev.main')
		->with(compact('banques'))
		->with(compact('ecritures'))
		->with(array('titre_page' => "Prévisionnel"))
		;
	}

}