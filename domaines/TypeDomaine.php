<?php

class TypeDomaine {

	protected $default_values_for_create = array(
		'nom' => CREATE_FORM_DEFAUT_TXT_NOM,
		'description' => CREATE_FORM_DEFAUT_TXT_DESCRIPTION,
		'statut_justif' => 0,
		'sep_justif' => CREATE_FORM_DEFAUT_TXT_SEPARATEUR,
		);


	public function create()
	{
		return new Type($this->default_values_for_create);
	}

	public function getListNom()
	{
		$model = new Type;

		return DomHelper::listForSelect($model, null, 'ByRang', false);
	}

	public function destroy($id)
	{
		/* S’il y a des écritures utilisant ce type, on renvoie la collection des écritures.
		Recevant une collection le controleur ne validera pas et préparera la liste pour la vue.
		Dans le cas contraire on renvoie la chaine contenant le nom de l'item 
		le controleur validera et passera le nom pour le message de confirmation. */

		$item = Type::with('ecriture')->findOrFail($id);
		if ($item->ecriture->count() != 0) {
			return $item->ecriture;
		}
		$item->delete();
		return $item->nom;
	}
}