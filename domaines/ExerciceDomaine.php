<?php

class ExerciceDomaine {


	/**
	 * Détermine et renvoie les années clôturées.
	 *
	 * @return array 
	 *
	 */
	public function getExercicesClotured(){
		$first_annee = \Ecriture::orderBy('date_valeur')->first(['date_valeur'])->date_valeur->formatlocalized('%Y');
		$first_annee_non_clotured = $this->getExerciceCourant();

		for ($i=$first_annee; $i < $first_annee_non_clotured ; $i++) { 
			$exercices[] = (string)$i;
		}
		return $exercices;
	}



	/**
	 * retourne la première année non clôturée,
	 * donc l'exercice courant.
	 * En principe il s'agit de l'année réelle
	 *
	 */
	public function getExerciceCourant(){
		$exercice = \Session::get('tresorerie.annee_reelle');
		return $exercice;
	}


}
