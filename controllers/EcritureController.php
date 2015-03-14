<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use tresorerie\Validations\EcritureValidation;
use tresorerie\Validations\EcritureDoubleValidation;

class EcritureController extends BaseController {

	/* Attribuer le qualificatif donné à l'écriture n°1 dans les messages (souci de clarté pour l’utilisateur)
	afin de pouvoir le changer globalement on le place dans une variable  */
	private $nommage = 'en cours d’édition';



	public function __construct(
		EcritureValidation $validateur, 
		EcritureDoubleValidation $validateur2, 
		EcritureDomaine $ecritureDom,
		BanqueDomaine $banqueDom,
		CompteDomaine $compteDom,
		StatutDomaine $statutDom,
		TypeDomaine $typeDom
		)
	{
		$this->validateur = $validateur;
		$this->validateur2 = $validateur2;
		$this->ecritureDom = $ecritureDom ;
		$this->banqueDom = $banqueDom;
		$this->compteDom = $compteDom ;
		$this->statutDom = $statutDom ;
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


	public function index($banque = null)
	{	
		Session::put('page_depart', Request::getUri());
		Session::put('ParamEnv.tresorerie.mode_courant', 'ecritures');

		$nbre_par_page = (Input::get('nbre_par_page')) ? Input::get('nbre_par_page') : NBRE_PAR_PAGE;

		// La date d'émission est le critère par défaut
		$critere_tri = (Input::get('critere_tri')) ? Input::get('critere_tri') : 'libelle';

		// "asc" est le sens du tri par défaut
		$sens_tri = Input::get('sens_tri') ? Input::get('sens_tri') : 'asc';

		// Comme il est délicat d'utiliser "id", j'ai utilisé "ids"…
		// Mais il faut en tenir compte maintenant et remplacer par "id" le cas échéant
		$critere_tri = ($critere_tri == 'ids')? 'id' : $critere_tri;

		if ($banque !== null) {
			Session::put('ParamEnv.tresorerie.banque_id', $banque);
			$bank_nom = $this->banqueDom->nomBanque($banque);
			$titre_page = 'Écritures de “'.$bank_nom.'”';
			$ecritures = $this->ecritureDom->tri($critere_tri, $sens_tri, $nbre_par_page, $banque);
		}else{
			$ecritures = $this->ecritureDom->tri($critere_tri, $sens_tri, $nbre_par_page, null);
			$titre_page = 'Toutes les écritures';
		}

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



		return View::Make('tresorerie.views.ecritures.index')
		->with(compact('ecritures'))
		->with(compact('titre_page'))
		->with(compact('critere_tri'))
		->with(compact('sens_tri'))
		->with(compact('head'))
		;
	}



	public function create()
	{
		$ecriture = $this->ecritureDom->create();

		return View::Make('tresorerie.views.ecritures.create')
		->with('ecriture', $ecriture)
		->with('list', self::getListes())
		->with('titre_page', "Création d’une écriture")
		;
	}

	public function duplicate($id)
	{
		$ecriture = Ecriture::where('id', $id)->with('ecriture2')->first();

		return View::Make('tresorerie.views.ecritures.create')
		->with('ecriture', $ecriture)
		->with('list', self::getListes())
		->with('titre_page', "Duplication d’une écriture")
		;
	}


	public function store()
	{
		/* Instancier écriture 1 */
		$ec1 = new Ecriture;

		/* Si écriture simple */
		if (!Input::get('is_double')) {

			$ec1 = static::hydrateSimple($ec1);
			$ec1->created_by = Auth::user()->id;
			$ec1->statut_id = self::saveStatutSelonModeCourant();

			$validation = $this->validateur->valider( Input::all() );
			if ($validation === true) {
				$ec1->save();
				Session::flash('success',"L’écriture a été créée");

			}else{
				return Redirect::back()
				->withInput(Input::all())
				->withErrors($validation)
				;
			}

		}else{
			/* Si écriture double */ 

			$double = static::hydrateDouble($ec1, $ec2 = null);

			$ec1 = $double[0];
			$ec2 = $double[1];

			$ec1->created_by = Auth::user()->id;
			$ec2->created_by = Auth::user()->id;

			$ec1->statut_id = self::saveStatutSelonModeCourant();
			$ec2->statut_id = self::saveStatutSelonModeCourant();

			$validation = $this->validateur->valider( Input::all() );
			$validation2 = $this->validateur2->valider( Input::all() );

			if ($validation === true) {
				if ($validation2 === true) {
					Session::flash('success',"Les deux écritures ont été créées et synchronisées");
				}else{
					return Redirect::back()->withInput(Input::all())->withErrors($validation2);
				}
			}else{
				return Redirect::back()->withInput(Input::all())->withErrors($validation);
			}

			$ec1->save();

		// /* Synchroniser */
			$ec2->soeur_id = $ec1->id;
			$ec2->save();

			$ec1->soeur_id = $ec2->id;
			$ec1->save();
		}
		self::setMoisCourant($ec1);
		return Redirect::to(Session::get('page_depart')."#".Session::get('ParamEnv.tresorerie.annee_courante'));

	}

	private static function hydrateSimple(Ecriture $ec1)
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



	private static function hydrateDouble(Ecriture $ec1, Ecriture $ec2 = null)
	{
		/* Hydrater écriture 1 */
		$ec1 = static::hydrateSimple($ec1);

		/* Instancier écriture 2 */
		if ($ec2 === null) { // Store
			$ec2 = new Ecriture;
		}

		/* Hydrater écriture 2 */
		$ec2->banque_id = Input::get('banque2_id');
		$ec2->date_emission = DatesFr::Sauv(Input::get('date_emission'));
		$ec2->date_valeur = DatesFr::Sauv(Input::get('date_valeur'));
		$ec2->montant = Input::get('montant');
		$ec2->signe_id = ($ec1->signe_id == 1)? 2 : 1;
		$ec2->libelle = Input::get('libelle');
		$ec2->libelle_detail = Input::get('libelle_detail');
		$ec2->type_id = Input::get('type_id2');
		$ec2->justificatif = Input::get('justificatif2');
		$ec2->compte_id = Input::get('compte_id');
		$ec2->is_double = Input::get('is_double');
		$ec2->note = Input::get('note');

		return array($ec1, $ec2);

	}



	public function edit($id)
	{
		$ec1 = Ecriture::where('id', $id)->with('ecriture2')->first();
		$libelleDetail = ($ec1->libelle_detail)? ' - '.$ec1->libelle_detail : "";

		return View::Make('tresorerie.views.ecritures.edit')
		->with('ecriture', $ec1)
		->with('list', self::getListes())
		->with('titre_page', "Édition de l’écriture \"$ec1->libelle$libelleDetail\" (n°$ec1->id)")
		;
	}



	public function update($id)
	{
		/* Instancier ecriture 1 */
		$ec1 = Ecriture::where('id', $id)->with('ecriture2')->first();

		/* Initialiser la variable destinée à contenir le message de succès */
		$success = '';

		/* Détecter si changement du flag double écriture */
		$doubleBefore = ($ec1->is_double == 1 ? true : false);
		$doubleNow = (is_null(Input::get('is_double'))) ? false : true;

		$changement = ($doubleBefore != $doubleNow) ? true : false ;

		/* Déterminer si le changement de type est confirmée ou non */
		$confirmOk = (Input::get('verrou') == 1 ) ? false : true ;

		/* Si changement de type, on doit alors vérifier s'il y a eu confirmation. */

		/* Si non confirmé */
		if($changement == true and $confirmOk == false){

			/* Conserver les inputs */
			Input::flash();

		/*	- stopper le processus,
		- présenter un nouveau formulaire identique du point de vue des inputs, et qui
 	  		• conserve les entrées faites par l'utilisateur,
 	    	• modifie l'attribut action du formulaire (ajout de "/ok" en fin d'url) afin de ne pas être filtré à nouveau,
 	    	• affiche un message alertant sur le changement de type et donnant la possibilté d'annuler. */

 	    	/* Le message sera composé différemment selon qu'il s'agit d'un passage d'une écriture double à une écriture simple  ou du passage inverse */
 	    	if ($doubleBefore){
 	    		$message = "• IMPORTANT ! Vous demandez à passer d’une écriture double à une écriture simple. Si vous validez vous allez supprimer l'écriture pour la banque “".$ec1->ecriture2->banque->nom."”.<br />Vous pouvez :<br />
 	    		– BASCULER <a href =".URL::action('EcritureController@edit', $ec1->ecriture2->id)."> sur l’écriture liée</a><br />
 	    		– CONFIRMER votre choix en validant “.VERROU.”, puis enregistrer<br />
 	    		– ANNULER en revenant à la ";
 	    	}else{
 	    		$message = "Attention ! Vous cherchez à passer d’une écriture simple à une écriture double.<br />Vous pouvez :<br /> – Vérifier votre saisie et VALIDER ce choix en déverouillant ".VERROU.",<br /> – ANNULER en revenant à la ";

 	    	}

 	    	Session::flash('erreur', $message .= link_to(Session::get('page_depart'), 'page précédente'));
 	    	Session::flash('class_verrou', 'visible');
 	    	/* Redirection */
 	    	return Redirect::back()->withInput(Input::all());
 	    }


			/* - - - - - - - - - - - - - - - - - - - - - -
			Traitement de l'update (Pas de changement de type OU BIEN celui-ci a été confirmé).  
			- - - - - - - - - - - - - - - - - - - - - - - - */

			/* - - - - - - - - - - - - - - - - - - - - - -
			Si l'écriture est de type simple
			- - - - - - - - - - - - - - - - - - - - - - - - */
			if (!$doubleNow == 1)
			{
				Session::flash('test.passage', '1 vers 1');
				/* - - - - - - - - - - - - - - - - - - - - - -
				… et était simple avant…
				- - - - - - - - - - - - - - - - - - - - - - - - */
				/* Hydrater ecriture 1 avec les nouvelles entrées*/
				$ec1 = static::hydrateSimple($ec1);
				$ec1->updated_by = Input::get('updated_by');
				
				/* - - - - - - - - - - - - - - - - - - - - - -
				… et était double avant…
				- - - - - - - - - - - - - - - - - - - - - - - - */
				if ($changement and $doubleBefore == 1) {
					Session::flash('test.passage', '2 vers 1');

					/* Supprimer E2 */
					$ec2 = Ecriture::where('id', Input::get('ecriture2_id'))->get();

					$ec2[0]->delete();

					/* Désynchroniser E1 */
					$ec1->soeur_id = null;

					/* Composer messages */
					$success = "• L’écriture $this->nommage a été désynchronisée…<br />• L’écriture liée a été supprimée<br />".$success;
				}


			/* - - - - - - - - - - - - - - - - - - - - - -
			Si l'écriture est de type double…	
			- - - - - - - - - - - - - - - - - - - - - - - - */
		}else{
				/* - - - - - - - - - - - - - - - - - - - - - -
				… et était simple avant…
				- - - - - - - - - - - - - - - - - - - - - - - - */
				if ($changement) {

					/* Instancier E2 */
					$ec2 = new Ecriture();
					$success .= '• L’écriture liée a été créée.<br />';

					/* Synchroniser E2 */
					$ec2->soeur_id = $id;
					$success .= '• L’écriture liée a été synchronisée.<br />';


				/* - - - - - - - - - - - - - - - - - - - - - -
				… et était déjà double avant.
				- - - - - - - - - - - - - - - - - - - - - - - - */
			}else{

				/* Instancier E2 */
				$ec2 = Ecriture::where('id', Input::get('ecriture2_id'))->get();

				/* Vérification qu'il n’existe qu'une seule écriture liée */
				if($ec2->count() > 1)
				{
					return Redirect::back()->withErrors('ATTENTION PROBLÈME GRAVE : il y a plus d’une écriture associée à celle qui vient d’être modifiée. Contactez l’administrateur<!-- aFa a href"">Contrôle des écritures doubles"</a-->');
				}
				$ec2 = $ec2[0];
			}

			/* Hydrater les 2 écritures */
			$double = static::hydrateDouble($ec1, $ec2);

			/* Save E2 */
			$validation2 = $this->validateur2->valider( Input::all() );

			if ($validation2 === true) {
				$ec2->updated_by = Auth::user()->id;
				$ec2->save();
				$success .= '• L’écriture liée a été sauvegardée<br />';
			}else{
				return Redirect::route('tresorerie.ecritures.edit', [$id])->withInput(Input::except('type_id2'))->withErrors($validation2);
			}

			/* Synchroniser E1 */
			if ($changement) {
				$ec1->soeur_id = $ec2->id;
				$success = "• L’écriture $this->nommage a été synchronisée.<br />".$success;
			}

		}

		/* - - - - - - - - - - - - - - - - - - - - - -
		Dans tous les cas
		- - - - - - - - - - - - - - - - - - - - - - - - */
		// dd(Input::all());
		$validation = $this->validateur->valider( Input::all() );

		if ($validation === true) {
			$ec1->updated_by = Auth::user()->id;
			$ec1->save();
			$success = "• L’écriture $this->nommage a été sauvegardée.<br />".$success;
		}else{
			return Redirect::route('tresorerie.ecritures.edit', [$id])->withInput(Input::except('type_id1'))->withErrors($validation);
		}

		/* Rediriger */
		Session::flash('success', $success);
		self::setMoisCourant($ec1);
		return Redirect::to(Session::get('page_depart')."#".Session::get('ParamEnv.tresorerie.mois_courant'));
	}



	public function destroy($id)
	{
		$ecriture = Ecriture::where('id', $id)->with('ecriture2')->get();
		$ecriture = $ecriture[0];

		$success = '';
		/* Le cas échéant traiter l'écriture liée */

		if ($ecriture->ecriture2){
			$deuze = Ecriture::whereSoeurId($ecriture->ecriture2->soeur_id)->get();
			$deuze = $deuze[0];
			$deuze->deleted_by = Auth::user()->id;
			$deuze->save();
			$deuze->delete();
			$success = "• L’écriture liée à été supprimée.<br />";
		}

		$ecriture->deleted_by = Auth::user()->id;
		$ecriture->save();

		$ecriture->delete();
		$success = "• L’écriture à été supprimée.<br />$success";

		Session::flash('success', $success);

		self::setMoisCourant($ecriture);
		return Redirect::to(Session::get('page_depart')."#".Session::get('ParamEnv.tresorerie.mois_courant'));

	}


	public static function setMoisCourant($ec){
		if (Session::has('ParamEnv.tresorerie.mois_courant.mode')) {
			if (Session::get('ParamEnv.tresorerie.mode_courant') == 'journal') {
				$mois = DatesFr::classAnMois($ec->date_emission);
			}else{
				$mois = DatesFr::classAnMois($ec->date_valeur);
			}
			Session::put('ParamEnv.tresorerie.mois_courant', $mois);
		}else{
			Session::put('ParamEnv.tresorerie.mois_courant', date('Y.m'));
		}

	}

	public static function saveStatutSelonModeCourant(){
		$mode_courant = Session::get('ParamEnv.tresorerie.mode_courant');
		switch ($mode_courant) {
			case 'journal':
			return 2;
			break;
			
			case 'pointage':
			return 3;
			break;
			
			case 'previsionnel':
			return 1;
			break;
			
			default:
			return 1;
			break;
		}
	}

	public function incrementeStatut($id, $statuts_accessibles)
	{
		$ecriture = $this->ecritureDom->find($id);

		$ecriture->statut_id = $this->statutDom->incremente($statuts_accessibles, $ecriture);

		$this->ecritureDom->save($ecriture);

		return Response::make('', 204);
	}

	public static function recherche(){

		return Redirect::back()
		->withErrors("La recherche sera fonctionnelle dans une prochaine version")
		;
	}

	public static function analytique(){

		return Redirect::back()
		->withErrors("Les fonctions analytiques seront fonctionnelles dans une prochaine version")
		;
	}

}