<?php

class StatutDomaine {

	protected $default_values_for_create = [
	'nom' => 'rehja,ea',
	'classe' => 'Saisir un libellé',
	'description' => 'Éventuellement le compléter',
	];


	public function create(){
		return new Statut($this->default_values_for_create);
	}

	public function getListeClasseStatut(){
		$statuts = Statut::all(['id', 'classe']);
		foreach ($statuts as $statut) {
			$classe_statut[$statut->id] = $statut->classe;
		}
		return json_encode($classe_statut);
	}


	public function incremente($statuts_autorised, $ecriture)
	{
		$last_statut_accessible = substr($statuts_autorised, -1);
		$statut_actuel = ($ecriture->statut_id);

		$new_statut = ($statut_actuel < $last_statut_accessible) ? ++$statut_actuel : $statuts_autorised[0] ;

		return $new_statut;
	}

}