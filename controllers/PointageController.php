<?php

class PointageController extends BaseController {

	// Les statuts accessibles (séparés par un "-")
	private $statuts_accessibles = '2-3-4';

	public function __construct(){
		$this->ecritDom = new EcritureDomaine;
		$this->pointageDom = new PointageDomaine;
		$this->banqueDom = new BanqueDomaine;
		$this->statutDom = new StatutDomaine;
	}

	public function index($id = null) //
	{
		/* Si pas d'$id spécifié on utilise celui de la banque courante
		(stocké en session). Si on est en début de session on initialise alors à 1
		qui est l'Id de la banque principale */
		if (is_null($id))
		{
			$id = (Session::get('ParamEnv.tresorerie.banque_id'))? Session::get('ParamEnv.tresorerie.banque_id') : 1;
		}

		/* Si l'édition d’une écriture est demandée depuis cette page, 
		il faut passer (via la session) à EcritureController@update pour la redirection */
		Session::put('page_depart', Request::getUri());

		// Récupérer la collection d'écriture pour la banque demandée
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
		Session::put('ParamEnv.tresorerie.banque_nom', $ecritures[0]->banque->nom);
		Session::put('ParamEnv.tresorerie.banque_id', $ecritures[0]->banque->id);

		// Assigner le tableau de correspondance pour gestion js de l'affichage de l'incrémentation des statuts. 
		$classe_statut = $this->statutDom->setClasseStatut();

		// Afficher la vue pointage pour la banque demandée. 
		return View::make('tresorerie.views.pointage.main')
		->with(compact('ecritures'))
		->with(compact('classe_statut'))
		->with(array('statuts_accessibles' => $this->statuts_accessibles))
		->with(array('titre_page' => "Pointage de ".Session::get('ParamEnv.tresorerie.banque_nom')))
		;
	}


	public function incrementeStatut($id, $statuts_accessibles)
	{
		// return 'pointage de l’écriture n° '.$id.'<br />Statut id : '.$statut_id;  // CTRL
		// return var_dump(Input::all());  // CTRL

		$ecriture = $this->ecritDom->find($id);

		$ecriture->statut_id = $this->statutDom->incremente($statuts_accessibles, $ecriture);

			// return var_dump($new_statut); // CTRL

		$this->ecritDom->save($ecriture);

		return Response::make('', 204);
	}


}