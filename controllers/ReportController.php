<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use tresorerie\Validations\EcritureValidation;
use tresorerie\Validations\EcritureDoubleValidation;

class ReportController extends BaseController {

	/* Attribuer le qualificatif donné à l'écriture n°1 dans les messages (souci de clarté pour l’utilisateur)
	afin de pouvoir le changer globalement on le place dans une variable  */
	private $nommage = 'en cours d’édition';



	public function __construct(
		ReportDomaine $reportDom,
		BanqueDomaine $banqueDom,
		CompteDomaine $compteDom,
		TypeDomaine $typeDom
		)
	{
		$this->reportDom = $reportDom ;
		$this->banqueDom = $banqueDom;
		$this->compteDom = $compteDom ;
		$this->typeDom = $typeDom ;
	}

	public function index()
	{	
		Session::put('page_depart', Request::getUri());

		$ecritures = $this->reportDom->report();

		$ecritures->each(function($ecriture)
		{
			if($ecriture->report == 1)
			{
				$ecriture->classe = "reportable";
			}
		});
		$titre_page = 'Tous les reports';

		// Créer un tableau pour la construction de la tétière
		$head = array(
			'ids' => 'Id',
			'banque_id' => 'Banque',
			'date_valeur' => 'Date valeur',
			'libelle' => 'Libellé',
			'type_id' => 'Type',
			'montant' => 'Montant',
			);



		return View::Make('tresorerie.views.reports.index')
		->with(compact('ecritures'))
		->with(compact('titre_page'))
		->with(compact('head'))
		;
	}



	public function setReportable($id)
	{
		// dd($id);
		$ecriture = Ecriture::where('id', $id)->first();
		if($ecriture->report == 1)
		{
			$ecriture->report = null;
		}else{
			$ecriture->report = 1;
		}
		$ecriture->save();
		return \Response::make('', 204);
	}


	public function update($id)
	{
	// 	$report = Report::where('id', $id)->first();

	// 	/* Initialiser la variable destinée à contenir le message de succès */
	// 	$success = '';

	// 	/* Conserver les inputs */
	// 	Input::flash();

	// 	$report = static::hydrateSimple($report);
	// 	$report->updated_by = Input::get('updated_by');

	// 	$report->save();

	// 	/* Rediriger */
	// 	Session::flash('success', $success);
	// 	return Redirect::to(Session::get('page_depart')."#".Session::get('tresorerie.mois_travail'));
	}



}