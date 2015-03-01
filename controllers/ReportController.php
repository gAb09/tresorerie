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

	private $listes = array();

	private function getListes()
	{
		$this->listes['banque'] = $this->banqueDom->getListNom();
		$this->listes['compte'] = $this->compteDom->getListActifs();
		$this->listes['compte_activation'] = $this->compteDom->getListActivables();
		$this->listes['type'] = $this->typeDom->getListNom();
		return $this->listes;
	}
// aFa Séparer la génération des listes ? OUI


	public function index()
	{	
		Session::put('page_depart', Request::getUri());

		$reports = $this->reportDom->all();
		$titre_page = 'Tous les reports';

		// Créer un tableau pour la construction de la tétière
		$head = array(
			'ids' => 'Id',
			'date_valeur' => 'Date valeur',
			'type_id' => 'Type',
			'banque_id' => 'Banque',
			'libelle' => 'Libellé',
			'montant' => 'Montant',
			'compte_id'=> 'Compte',
			'created_at'=> 'Créé le',
			'updated_at'=> 'Modifié le',
			);



		return View::Make('tresorerie.views.reports.index')
		->with(compact('reports'))
		->with(compact('titre_page'))
		->with(compact('head'))
		;
	}



	public function create()
	{
		$report = $this->reportDom->create();

		return View::Make('tresorerie.views.reports.create')
		->with(compact('reports'))
		->with('list', self::getListes())
		->with('titre_page', "Création d’un report")
		;
	}

	public function duplicate($id)
	{
		$report = Report::where('id', $id)->first();

		return View::Make('tresorerie.views.reports.create')
		->with(compact('report'))
		->with('list', self::getListes())
		->with('titre_page', "Duplication d’un report")
		;
	}


	public function store()
	{
		$report = new Report;

		$report = static::hydrateSimple($report);
		$report->created_by = Auth::user()->id;

		$report->save();

		Session::flash('success',"L’écriture a été créée");

		return Redirect::to(Session::get('page_depart')."#".Session::get('ParamEnv.tresorerie.annee_courante'));

	}

	private static function hydrateSimple(Report $ec1)
	{		
		$ec1->banque_id = Input::get('banque_id');
		$ec1->date_emission = DatesFr::Sauv(Input::get('date_emission'));
		$ec1->date_valeur = DatesFr::Sauv(Input::get('date_valeur'));
		$ec1->montant = Input::get('montant');
		$ec1->signe_id = Input::get('signe_id');
		$ec1->libelle = Input::get('libelle');
		$ec1->libelle_detail = Input::get('libelle_detail');
		$ec1->type_id = Input::get('type_id1');
		$ec1->justificatif = Input::get('justificatif1');
		$ec1->compte_id = Input::get('compte_id');
		$ec1->is_double = Input::get('is_double');
		$ec1->note = Input::get('note');

		return $ec1;
	}


	public function edit($id)
	{
		$report = Report::where('id', $id)->first();
		$libelleDetail = ($report->libelle_detail)? ' - '.$report->libelle_detail : "";

		return View::Make('tresorerie.views.reports.edit')
		->with('report', $report)
		->with('list', self::getListes())
		->with('titre_page', "Édition de l’écriture \"$report->libelle$libelleDetail\" (n°$report->id)")
		;
	}



	public function update($id)
	{
		$report = Report::where('id', $id)->first();

		/* Initialiser la variable destinée à contenir le message de succès */
		$success = '';

		/* Conserver les inputs */
		Input::flash();

		$report = static::hydrateSimple($report);
		$report->updated_by = Input::get('updated_by');

		$report->save();

		/* Rediriger */
		Session::flash('success', $success);
		return Redirect::to(Session::get('page_depart')."#".Session::get('ParamEnv.tresorerie.mois_courant'));
	}



	public function destroy($id)
	{
		$report = Report::where('id', $id)->get();
		$report = $report[0];

		$success = '';
		/* Le cas échéant traiter l'écriture liée */

		$report->deleted_by = Auth::user()->id;
		$report->save();

		$report->delete();
		$success = "• L’écriture à été supprimée.<br />$success";

		Session::flash('success', $success);

		self::setMoisCourant($report);
		return Redirect::to(Session::get('page_depart')."#".Session::get('ParamEnv.tresorerie.mois_courant'));

	}


	public static function setMoisCourant($ec){
		if (strpos(Session::get('page_depart'), 'journal') !== false) {
			$mois = DatesFr::classAnMois($ec->date_emission);
		}else{
			$mois = DatesFr::classAnMois($ec->date_valeur);
		}

		return Session::put('ParamEnv.tresorerie.mois_courant', $mois);
	}


}