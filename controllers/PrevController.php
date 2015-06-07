<?php

class PrevController extends BaseController {

	// Le critère de classement
	private $order = 'date_valeur';

	public function __construct(){
		$this->prevDom = new PrevDomaine;
		$this->banqueDom = new BanqueDomaine;
		$this->statutDom = new StatutDomaine;
		$this->exerciceDom = new ExerciceDomaine;
	}

	public function index($annee = null)
	{
		/* Traitement des arguments */
		$annee = (is_null($annee))? Session::get('tresorerie.exercice_travail') : $annee;
		if ($annee == 'en_cours') {
			$annee = Session::get('tresorerie.exercice_travail');
		}


		/* Rafraichissement en session de l'exercice en cours de travail */
		Session::put('tresorerie.exercice_travail', $annee);


		/* Mise en session du mode courant */
		Session::put('tresorerie.mode_courant', 'previsionnel');


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


		/* vueD/E - Obtenir les exercices, clôturées et non clôturées */
		$exercices_clotured = $this->exerciceDom->getExercicesClotured();
		$exercice = $this->exerciceDom->getExerciceCourant();


		/* vueF - Obtenir les statuts autorisés pour ce mode */
		$statuts_autorised = $this->prevDom->getStatutsAutorised();

		/* vueG - Changer la classe du volet topfoot1 delon les autorisations */
		if(Auth::user()->role_id !== 1){
			$tf1 = "legende";
		}else{
			$tf1 = "";
		}



		/* On peut afficher la vue */ 
		return View::make('tresorerie.views.prev.main')
		->with(compact('banques')) // A
		->with(compact('ecritures')) // B
		->with(compact('classe_statut')) //C
		->with(compact('exercices_clotured')) // D
		->with(compact('exercice')) // E
		->with(compact('statuts_autorised')) // F
		->with(compact('tf1')) // G
		->with(array('titre_page' => "Prévisionnel"))
		;
	}

}