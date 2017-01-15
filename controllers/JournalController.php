<?php

class JournalController extends BaseController {

	public function __construct(){
		$this->journalDom = new JournalDomaine;
		$this->banqueDom = new BanqueDomaine;
		$this->statutDom = new StatutDomaine;
		$this->exerciceDom = new ExerciceDomaine;
	}

	public function index($id = null, $annee = null) //
	{
		/* Mise en session du mode courant */
		Session::put('tresorerie.mode_courant', 'journal');

		/* Si pas d'$id spécifié on utilise celui de la banque courante
		(stocké en session). Si on est en début de session on initialise alors à 1
		qui est l'Id de la banque principale */
		if (is_null($id))
		{
			$id = (Session::get('tresorerie.banque_id'))? Session::get('tresorerie.banque_id') : 1;
		}

		/* Si pas d'$annee spécifié on prend l'année réelle en cours */
		if (is_null($annee))
		{
			$annee = Session::get('tresorerie.annee_reelle');
		}

		/* Rafraichissement en session de l'exercice en cours de travail */
		Session::put('tresorerie.exercice_travail', $annee);

		/* Mise en session de la page de départ pour la redirection depuis EcritureController@update */
		Session::put('page_depart', Request::getUri());

		// vueA - Récupérer la collection d'écriture pour la banque demandée
		$ecritures = $this->journalDom->collectionJournal($id, $annee, 'date_emission');


		/* S'il n'y a pas d'écriture pour la banque demandée : 
		rediriger sur la page pointage par défaut avec un message d'erreur */
		if (!$ecritures){
			$message = 'Il n’y a aucune écriture pour la banque “';
			$message .= $this->banqueDom->nomBanque($id);
			$message .= '" pour l’exercice ';
			$message .= $annee;
			$message .= '.';
			return Redirect::back()->withErrors($message);
		}

		/* Passer le nom et l’id de la banque à la session 
		pour mémorisation de la banque en cours de traitement. */
		Session::put('tresorerie.banque_nom', $ecritures[0]->banque->nom);
		Session::put('tresorerie.banque_id', $ecritures[0]->banque->id);


		/* vueB/C - Obtenir les exercices, clôturées et non clôturées */
		$exercices_clotured = $this->exerciceDom->getExercicesClotured();
		$exercice = $this->exerciceDom->getExerciceCourant();
		$exercice_suivant = $this->exerciceDom->getExerciceSuivant();


		/* Afficher la vue pointage pour la banque demandée. */ 
		return View::make('tresorerie.views.journal.main')
		->with(compact('ecritures')) // A
		->with(compact('exercices_clotured')) // B
		->with(compact('exercice')) // C
		->with(compact('exercice_suivant')) // C
		->with(array('titre_page' => "Journal de ".Session::get('tresorerie.banque_nom')))
		;
	}

}