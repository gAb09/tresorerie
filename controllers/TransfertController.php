<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use tresorerie\Validations\EcritureValidation;
use tresorerie\Validations\EcritureDoubleValidation;

class TransfertController extends BaseController {

	/* Attribuer le qualificatif donné à l'écriture n°1 dans les messages (souci de clarté pour l’utilisateur)
	afin de pouvoir le changer globalement on le place dans une variable  */
	private $nommage = 'en cours d’édition';



	public function __construct(
		TransfertDomaine $transfertDom,
		BanqueDomaine $banqueDom,
		CompteDomaine $compteDom,
		TypeDomaine $typeDom
		)
	{
		$this->transfertDom = $transfertDom ;
		$this->banqueDom = $banqueDom;
		$this->compteDom = $compteDom ;
		$this->typeDom = $typeDom ;
	}

	public function index()
	{	
		Session::put('page_depart', Request::getUri());

		$ecritures = $this->transfertDom->transfert();

		$ecritures->each(function($ecriture)
		{
			if($ecriture->transfert == 1)
			{
				$ecriture->classe = "transferable";
			}
		});
		$titre_page = 'Tous les transferts';

		// Créer un tableau pour la construction de la tétière
		$head = array(
			'ids' => 'Id',
			'banque_id' => 'Banque',
			'date_valeur' => 'Date valeur',
			'libelle' => 'Libellé',
			'type_id' => 'Type',
			'montant' => 'Montant',
			'datecrea' => 'Date de création',
			'datemodif' => 'Date de modification',
			);



		return View::Make('tresorerie.views.transferts.index')
		->with(compact('ecritures'))
		->with(compact('titre_page'))
		->with(compact('head'))
		;
	}



	public function setTransferable($id)
	{
		// dd($id);
		$ecriture = Ecriture::where('id', $id)->first();
		if($ecriture->transfert == 1)
		{
			$ecriture->transfert = null;
		}else{
			$ecriture->transfert = 1;
		}
		$ecriture->save();
		return \Response::make('', 204);
	}



	public function DoTransfert()
	{
		$ecritures = Ecriture::where('transfert', 1)->get();
		// dd($ecritures);
		$ecritures = $ecritures->each(function ($item){
			$new = new Ecriture;

			$new->date_valeur = $item->date_valeur;
			$new->date_valeur = $new->date_valeur->addYear();
			$new->date_emission = '2016-12-31 00:00:00';
			$new->banque_id = $item->banque_id;
			$new->type_id = 10;
			// justificatif
			// is_double
			$new->libelle = $item->libelle;
			$new->libelle_detail = $item->libelle_detail;
			$new->montant = $item->montant;
			$new->signe_id = $item->signe_id;
			$new->compte_id = $item->compte_id;
			$new->statut_id = 1;


			var_dump($item->toArray());
			var_dump($new->toArray());
			$new->save();
		});

		// return dd($ecritures);
	}



}