<?php

class PointageController extends BaseController {

	public function __construct(){
		$this->ecritDom = new EcritureDomaine;
		$this->pointageDom = new PointageDomaine;
		$this->banqueDom = new BanqueDomaine;
		$this->statutDom = new StatutDomaine;
	}

	public function index($id = null) //
	{
		/* Mise en session du mode courant */
		Session::put('tresorerie.mode_courant', 'pointage');

		/* Si pas d'$id spécifié on utilise celui de la banque courante
		(stocké en session). Si on est en début de session on initialise alors à 1
		qui est l'Id de la banque principale */
		if (is_null($id))
		{
			$id = (Session::get('tresorerie.banque_id'))? Session::get('tresorerie.banque_id') : 1;
		}

		/* Mise en session de la page de départ pour la redirection depuis EcritureController@update */
		Session::put('page_depart', Request::getUri());

		/* vueA - Récupérer la collection d'écriture.
		Rediriger (back) si pas d'écritures */
		$ecritures = $this->pointageDom->collectionPointage($id, 'date_valeur');


		/* S'il n'y a pas d'écriture pour la banque demandée : 
		rediriger sur la page pointage par défaut avec un message d'erreur */
		if (!$ecritures){
			$message = 'Il n’y a aucune écriture pour la banque “';
			$message .= $this->banqueDom->nomBanque($id);
			$message .= '”';
			return Redirect::back()->withErrors($message);
		}

		/* Passer le nom et l’id de la banque à la session 
		pour mémorisation de la banque en cours de traitement. */
		Session::put('tresorerie.banque_nom', $ecritures[0]->banque->nom);
		Session::put('tresorerie.banque_id', $ecritures[0]->banque->id);


		/* vue B - Assigner le tableau de correspondance 
		pour gestion js de l'affichage de l'incrémentation des statuts. */
		$classe_statut = $this->statutDom->getListeClasseStatut();


		/* vue C - Obtenir les stauts autorisés pour ce mode */
		$statuts_autorised = $this->pointageDom->getStatutsAutorised();


		// Afficher la vue pointage pour la banque demandée. 
		return View::make('tresorerie.views.pointage.main')
		->with(compact('ecritures')) // A
		->with(compact('classe_statut')) // B
		->with(compact('statuts_autorised')) // C
		->with(array('titre_page' => "Pointage de ".Session::get('tresorerie.banque_nom')))
		;
	}

}