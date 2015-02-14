<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use tresorerie\Validations\TypeValidation;

class TypeController extends BaseController {

	protected $validateur;


	public function __construct(TypeValidation $validateur)
	{
		$this->validateur = $validateur;
		$this->typeDom = new TypeDomaine;
	}



	public function index()
	{
		$types = Type::orderBy('rang')->get();

		return View::Make('tresorerie.views.types.index')
		->with('types', $types)
		->with('titre_page','Les types d’écriture')
		;
	}



	public function create()
	{
		$type = $this->typeDom->create();

		return View::Make('tresorerie.views.types.create')
		->with('type', $type)
		->with('titre_page', 'Création d’un “type d’écriture”')
		;
	}



	public function store()
	{
		// dd(Input::all()); // CTRL
		$validation = $this->validateur->validerStore(Input::all());

		if($validation === true) 
		{

			Type::create(Input::except('_token'));

			Session::flash('success', 'Le type "'.Input::get('nom').'" a bien été créé');

			return Redirect::action('TypeController@index');
		}else{
			return Redirect::back()->withInput(Input::all())->withErrors($validation);
		}
	}


	public function edit($id)
	{
		$type = Type::findOrFail($id);

		return View::Make('tresorerie/views/types/edit')
		->with('type', $type)
		->with('titre_page', 'Édition du type “'.$type->nom)
		;
	}

	public function update($id)
	{
		// return dd(Input::all());

		$item = Type::findOrFail($id);

		$item->fill(Input::except('_token', '_method'));
		$item->req_justif = (Input::get('req_justif')? 1 : 0);

		$validation = $this->validateur->validerUpdate(Input::all(), $id);

		if($validation === true) 
		{

			$item->save();

			Session::flash('success', 'Le type "'.Input::get('nom').'" a bien été modifié');

			return Redirect::action('TypeController@index');

		}else{
			return Redirect::back()->withInput(Input::all())->withErrors($validation);
		}

	}

	public function destroy($id)
	{
		$reponse = $this->typeDom->destroy($id);

		if (is_object($reponse)) { // la reponse est une collection
			$message = 'Ce type ne peut être supprimé car il référence les écritures suivantes :<br />';
			foreach ($reponse as $ecriture) {
				$message .= "• ".$ecriture->libelle." – ".$ecriture->libelle_detail."<br />";
			}

			return Redirect::back()->withInput(Input::all())->withErrors($message);

		}else{ // la réponse est une chaîne

			Session::flash('success', "Le type \"$reponse\" a bien été supprimé");

			return Redirect::action('TypeController@index');
		}
	}
}