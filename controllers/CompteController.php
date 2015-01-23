<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use tresorerie\Validations\CompteValidation;
use Baum\Node;

class CompteController extends BaseController {

	protected $validateur;

	private function listerParentable()
	{
		return Compte::listForInputSelect('libelle', 'Parentable');
	}

	public function __construct(CompteValidation $validateur)
	{
		$this->validateur = $validateur;
	}


	public function index($choix = null)
	{
		$numero = (is_null($choix)) ? Session::get('Courant.classe') : $choix ;

		/* Passer en session le numero,
		pour mémoriser la classe sur laquelle
		l’utilisateur est en cours de travail
		et la mémoriser au fil de la navigation. */
		Session::set('Courant.classe', $numero);

		/* Assigner la liste des racines de comptes (classes) 
		pour le tableau de sélection des classes */
		$classes = Compte::roots()->get();


		/* Assigner $comptes qui contient les comptes à afficher
		   selon la classe demandée par l'utilisateur.
		   + Assigner le titre de haut de page (lui aussi fonction de la demande) */
		  $classe = Compte::where('numero', $numero)->first();
		  $comptes = $classe->getDescendantsAndSelf();
		  $titre_page = "Classe $numero : $classe->libelle";

		  /* Adapter les class css selon les valeurs de certains attributs */
		  $comptes->map(function($compte){
		  	$compte->classe_actif = ($compte->actif)? 'actif' : '';
		  	$compte->class_pco = ($compte->pco)? 'pco' : '';
		  });


		  return View::Make('tresorerie.views.comptes.index')
		  ->with('titre_page', $titre_page)
		  ->with('comptes', $comptes)
		  ->with('classes', $classes)
		  ;
		}


		public function create()
		{
			$compte = new Compte(Compte::fillFormForCreate());
			// $compte->fillFormForCreate();

			return View::Make('tresorerie.views.comptes.create')
			->with('compte', $compte)
			->with('position_class', 'invisible')
			->with('parents', self::listerParentable())
			->with('titre_page', 'Création d’un nouveau compte')
			;
		}



		public function store()
		{
			// return dd(Input::all());

			$validation = $this->validateur->validerStore(Input::all());

			if($validation === true) 
			{
				$compte = new Compte;
				$compte = $compte->create(Input::except('_token', 'pere', 'thisid', 'position'));

				$pere=Compte::where('id', Input::get('pere'))->first();
				$frere=Compte::where('id', Input::get('position'))->first();

				$compte->makeChildOf($pere);

				if (Input::get('position')) {
					$compte->moveToLeftOf($frere);
				}

				Session::flash('success', 'Le compte "'.Input::get('libelle').'" a bien été créé');              
				return Redirect::action('CompteController@index');
			} else {
				return Redirect::back()->withInput(Input::all())->withErrors($validation);
			}
		}




		public function edit($id)
		{
			$compte = Compte::FindOrFail($id);

			/* Adapter les class css selon les valeurs de certains attributs */
			$compte->class_pco = ($compte->pco)? 'pco' : '';

			return View::Make('tresorerie.views.comptes.edit')
			->with('compte', $compte)
			->with('position_class', 'invisible')
			->with('parents', self::listerParentable())
			;
		}



		public function update($id)
		{

			$item = Compte::FindOrFail($id);

			$validation = $this->validateur->validerUpdate(Input::all(), $id);

			if($validation === true) 
			{

				$item->fill(Input::except('_token', '_method', 'pere', 'thisid', 'position'));
				$actif = (Input::Has('actif')) ? Input::get('actif') : null ;
				$item->setAttribute('actif', $actif);
				$item->save();

				$pere=Compte::where('id', Input::get('pere'))->first();
				$frere=Compte::where('id', Input::get('position'))->first();
				$item->makeChildOf($pere);
				if (!is_null($frere)) {
					$item->moveToLeftOf($frere);
				}


				Session::flash('success', 'Le compte "'.Input::get('libelle').'" a bien été modifié');              
				return Redirect::action('CompteController@index');
			} else {
				return Redirect::back()->withInput(Input::all())->withErrors($validation);
			}
		}


		public function updateActif()
		{
			$item = Compte::FindOrFail(Input::get('id'));
			$item->actif = Input::get('valeur');
			$item->save();
			return Redirect::back();

		}


		public function destroy($id)
		{
		// dd('destroy compte : '.$id);
			$item = Compte::FindOrFail($id);
			$item->delete();

			Session::flash('success', 'Le compte "'.Input::get('libelle').'" a bien été supprimé');              

			return Redirect::action('CompteController@index');
		}

		public function freres($id = null) // aFa décomposer en scope dans model + Lister() dans controleur .
		{
			/* Obtenir le compte pere qui a été choisi et rechercher ses descendants immédiats */
			$pere = Compte::where('id', Input::get('idpere'))->first();
			$freres = $pere->getImmediateDescendants();

			/* Si on est en mode édition retirer le compte concerné de la liste des descendants 
			Si on est en mode création ce n'est pas nécessaire (nota : alors $id est null) */
			$freres = $freres->filter(function($frere) use($id)
			{
				if ($frere->id != $id) {
					return true;
				}
			});

			/* Si on est en mode édition ($id != null)
			assigner le compte frère suivant dans la liste pour checked dans le snippet html */

			if (isset($id)) {
				$this_position = Compte::where('id', $id)->first()->lft;
				$next_position = $this_position+2;
				$suivant = Compte::where('lft', $next_position)->first();
				$next_id = (is_null($suivant)) ? null : $suivant->id ;
			}

			/* Construire (ou non) la réponse = snippet html des options de l'élément select */
			if ($freres->isEmpty()) {
				$reponse = null;
			}else{
				$select = "<select name='position'>";
				$select .= "<option value='0'>Placer en dernier</option>";
				foreach ($freres as $frere) {
					if (isset($next_id) and $frere->id == $next_id) {
						$selection = ' selected';
					}else{
						$selection = '';
					}
					$select .= "<option value='".$frere->id."'".$selection.">(".$frere->numero.") ".$frere->libelle."</option>";
				} 
				$select .= "</select>";


				$reponse[0] = $select;
				$reponse[1] = $pere->libelle;
				// $reponse[2] = $next;
				
			}
			return $reponse;

		}
	}
