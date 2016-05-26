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


	public function getTransferable()
	{
		// dd($id);
		$ecritures = Ecriture::where('transfert', 1)->get(['id'])->toArray();
		dd($ecritures);
		if($ecriture->transfert == 1)
		{
			$ecriture->transfert = null;
		}else{
			$ecriture->transfert = 1;
		}
		$ecriture->save();
		return \Response::make('', 204);
	}


	public function update($id)
	{
	// 	$transfert = Transfert::where('id', $id)->first();

	// 	/* Initialiser la variable destinée à contenir le message de succès */
	// 	$success = '';

	// 	/* Conserver les inputs */
	// 	Input::flash();

	// 	$transfert = static::hydrateSimple($transfert);
	// 	$transfert->updated_by = Input::get('updated_by');

	// 	$transfert->save();

	// 	/* Rediriger */
	// 	Session::flash('success', $success);
	// 	return Redirect::to(Session::get('page_depart')."#".Session::get('tresorerie.mois_travail'));
	}



}