<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lib\Validations\StatutValidation;

class StatutController extends BaseController {

	protected $validateur;


	public function __construct(StatutValidation $validateur)
	{
		$this->validateur = $validateur;
	}



	public function visu()
	{
		$statuts = Statut::all();

		return View::Make('tresorerie.views.statuts.visu')
		->with(compact('statuts'))
		->with('titre_page', 'Lexique des statuts')
		;
	}

	public function index()
	{
		$statuts = Statut::all();

		return View::Make('tresorerie.views.statuts.index')
		->with(compact('statuts'))
		->with('titre_page', 'Les statuts')
		;
	}


	public function create()
	{
		return 'Action inhibée pour l’instant';  // CTRL

		$statut = new Statut(Statut::fillFormForCreate());

		return View::Make('tresorerie.views.statuts.create')
		->with('titre_page', 'Création d’un statut')
		->with(compact('statut'))
		;
	}

	public function store()
	{
		return 'Faire les validations !';
		
		$validation = $this->validateur->validerStore(Input::all());

		if($validation === true) 
		{
			// return 'OK'; // CTRL
			if(Statut::create(array(Input::except('_token'))))
			{
				Session::flash('success', 'Le statut "'.Input::get('nom').'" a bien été créé');
			}
			return Redirect::route('tresorerie.statuts.index');
		} else {
			return Redirect::back()->withInput(Input::all())->withErrors($validation);
		}
	}

	public function edit($id)
	{
		// return 'edition du statut n° '.$id;  // CTRL

		$statut = Statut::FindOrFail($id);

		return View::Make('tresorerie.views.statuts.edit')->with(compact('statut'));
	}

	public function update($id)
	{
		// return 'update du statut n° '.$id;  // CTRL
		return 'Faire les validations !';

		$validation = $this->validateur->validerUpdate(Input::all());

		if($validation === true) 
		{
			// return 'OK'; // CTRL
			$item = Statut::find($id);

			$item->nom = Input::get('nom');
			$item->classe = Input::get('classe');
			$item->description = Input::get('description');

			$item->save();

			return Redirect::route('tresorerie.statuts.index');
		} else {
			return Redirect::back()->withInput(Input::all())->withErrors($validation);
		}
	}

	public function destroy($id)
	{
		// return 'effacement du statut n° '.$id;  // CTRL

		$item = Statut::find($id);
		if ($item->delete()) {
			Session::flash('success', 'Le statut "'.$item->nom.'" a bien été supprimé');
		};

		return Redirect::to('backend/statuts');
	}

}
