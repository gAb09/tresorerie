<?php

class ExportController extends BaseController {

	public function __construct(){
		$this->exportDom = new ExportDomaine;
		$this->banqueDom = new BanqueDomaine;
	}

	public function export($id = null) //
	{
		/* Si pas d'$id spécifié on utilise celui de la banque courante
		(stocké en session). Si on est en début de session on initialise alors à 1
		qui est l'Id de la banque principale. */
		if (is_null($id))
		{
			$id = (Session::get('ParamEnv.tresorerie.banque_id'))? Session::get('ParamEnv.tresorerie.banque_id') : 1;
			
		}

		/* Obtenir le nom en clair de la banque courante et le mettre en session */
		$banque_nom = $this->banqueDom->nomBanque($id);
		Session::put('ParamEnv.tresorerie.banque_nom', $banque_nom);
		// Récupérer la collection d'écriture pour la banque demandée
		$ecritures = $this->exportDom->collectionExport($id, 'date_emission');

		/* S'il n'y a pas d'écriture pour la banque demandée : 
		rediriger sur la page pointage par défaut avec un message d'erreur */
		if (!$ecritures){
			$message = 'Il n’y a aucune écriture pour la banque “';
			$message .= $banque_nom;
			$message .= '”';
			return Redirect::back()->withErrors($message);
		}


		/* Afficher la vue pointage pour la banque demandée. */ 
		return View::make('tresorerie.views.export')
		->with(compact('ecritures'))
		->with(array('titre_page' => "Export de ".$banque_nom))
		;
	}



	public function handle()
	{
	$location =  '/home/gbom/laravel/app/storage/logs/log-fpm-fcgi-2015-02-10.txt';
	
		if(file_exists($location))
		{

			$ressource_fichier = file($location); //Ouvre le fichier en lecture seule, on supposera qu'il existe sous peine d'avoir une erreur
			if($ressource_fichier) //Si $ressource_fichier ne vaut pas FALSE on peut continuer
			{
				/*$contenu_fichier   = '';
				while(!feof($ressource_fichier)) //Tant que l'on est pas à la fin du fichier
				{
					$contenu_fichier .= fgetc($ressource_fichier); //Récupère le caractère en cours et l'ajoute au contenu de la variable $contenu_fichier
				}
			fclose($ressource_fichier); */
			return var_dump($ressource_fichier[0]);
			}

		}else
		{
		return "Le fichier $location n\'existe pas";
		} 		

	}
}