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
		/* Traitement des arguments */
		$annee = (is_null($annee))? Session::get('ParamEnv.tresorerie.annee_courante') : $annee;


		/* Rafraichissement en session de l'année courante */
		Session::put('ParamEnv.tresorerie.annee_courante', $annee);


		/* Mise en session du mode courant */
		Session::put('ParamEnv.tresorerie.mode_courant', 'previsionnel');


		/* Mise en session de la page de départ pour la redirection depuis EcritureController@update */
		Session::put('page_depart', Request::getUri());


		/* vueA - Récupérer les banques prises en compte dans le mode previsionnel */
		$banques = $this->banqueDom->isPrevisionnel();


		/* vueB - Récupérer la collection d'écriture.
		Rediriger (back) si pas d'écritures */
		$ecritures = $this->prevDom->collectionPrev($banques, $annee);


		if (!$ecritures){
			$message = 'Il n’y a aucune écriture pour l’année “';
			$message .= $annee;
			$message .= '”';
			return Redirect::back()->withErrors($message);
		}


		/* vueC - Assigner le tableau de correspondance 
		pour gestion js de l'affichage de l'incrémentation des statuts. */
		$classe_statut = $this->statutDom->getListeClasseStatut();


		/* vueD/E - Obtenir les années, clôturées et non clôturées */
		$annees_clotured = $this->prevDom->getAnneesClotured();
		$annees_non_clotured = $this->prevDom->getAnneesNonClotured();


		/* vueF - Obtenir les stauts autorisés pour ce mode */
		$statuts_autorised = $this->prevDom->getStatutsAutorised();


		/* On peut afficher la vue */ 
		return View::make('tresorerie.views.prev.main')
		->with(compact('banques')) // A
		->with(compact('ecritures')) // B
		->with(compact('classe_statut')) //C
		->with(compact('annees_clotured')) // D
		->with(compact('annees_non_clotured')) // E
		->with(compact('statuts_autorised')) // F
		->with(array('titre_page' => "Prévisionnel"))
		;
	}

}