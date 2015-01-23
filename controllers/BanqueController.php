<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use tresorerie\Validations\BanqueValidation;


class BanqueController extends BaseController {

	protected $validateur;


	public function __construct(BanqueValidation $validateur)
	{
		$this->validateur = $validateur;
		// dd($this->validateur);  // CTRL
	}



	public function index()
	{
		$banques = Banque::all();

		return View::Make('tresorerie.views.banques.index')
		->with(compact('banques'))
		->with('titre_page', 'Les banques')
		;
	}



	public function create()
	{
		$banque = new Banque(Banque::fillFormForCreate());
		$banque->fillFormForCreate();

		return View::Make('tresorerie.views.banques.create')
		->with(compact('banque'))
		->with('titre_page', 'Création d’une nouvelle banque')
		;
	}



	public function store()
	{
		$validation = $this->validateur->validerStore(Input::all());

		if($validation === true) 
		{
			// return 'OK'; // CTRL
			$banque = new Banque;
			$banque->create(Input::except('_token'));
			Session::flash('success', 'La banque "'.Input::get('nom').'" a bien été crée');              
			return Redirect::action('BanqueController@index');
		} else {
			return Redirect::back()->withInput(Input::all())->withErrors($validation);
		}
	}



	public function edit($id)
	{
		$banque = Banque::FindOrFail($id);
		return View::Make('tresorerie.views.banques.edit')
		->with(compact('banque'))
		->with('titre_page', 'Édition de la banque “'.$banque->nom.'”')
		;
	}



	public function update($id)
	{
		$item = Banque::FindOrFail($id);

		$validation = $this->validateur->validerUpdate(Input::all(), $id);

		if($validation === true) 
		{
			// return 'OK'; // CTRL

			$item->fill(Input::except('_token', '_method'));
			$item->save();

			Session::flash('success', 'La banque "'.Input::get('nom').'" a bien été modifiée');

			return Redirect::action('BanqueController@index');
		} else {
			// return 'fails'; // CTRL
			return Redirect::back()->withInput(Input::all())->withErrors($validation);
		}

	}



	public function destroy($id)
	{
		// return 'effacement de la banque n° '.$id;  // CTRL

		$item = Banque::FindOrFail($id);
		if ($item->delete()) {
			Session::flash('success', "La banque $item->nom a bien été supprimée");
		};

		return Redirect::action('BanqueController@index');
	}

}
