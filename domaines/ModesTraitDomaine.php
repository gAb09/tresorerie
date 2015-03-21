<?php
namespace Tresorerie\Domaines;

trait ModesTraitDomaine {

	private $prev_mois = '';



	public function getStatutsAutorised()
	{
		return $this->statuts_autorised;
	}



	/**
	 * Prépare l'affichage des lignes en tableaux d'un même mois/année.
	 *
	 * @param $ligne. La ligne du tableau en cours de traitement.
	 * @param $collection.
	 * @param $order. Le critère pour l'ordre de classement.
	 * @param $last. Le rang de la dernière écriture.
	 *
	 * @return Rien puisque appellée depuis une boucle each sur une collection
	 *
	 */
	private function affichageParMois($ligne, $collection, $order){

		/* Relever le mois et l’année du critère de classement */
		$ligne->mois_classement = \DatesFr::classAnMois($ligne->{$order});

			/* Il s'agit du premier mois de la page ?
			Assigner $index_ligne de cette ligne à "premier_mois"*/
			if ($this->prev_mois == '')
			{
				$ligne->index_ligne = 'premier_mois';

				/* Il y a changement de mois ? */
			}elseif ($ligne->mois_classement != $this->prev_mois)
			{
				/* Assigner $index_ligne de cette ligne à "nouveau_mois" */
				$ligne->index_ligne = 'nouveau_mois';

				/* Assigner $index_ligne de la ligne précédente à "der_du_mois" */
				$prev_rang = $ligne->rang -1;
				$collection[$prev_rang]->index_ligne = "der_du_mois";
				
			}

			/* Conserver le mois classement pour comparaison avec la prochaine ligne.*/
			$this->prev_mois = $ligne->mois_classement;

			/* On n'oublie pas la toute dernière ligne de la page.*/
			if ($ligne->rang == $this->last) {
				$ligne->index_ligne = "fin_page";
			}

		}



	/**
	 * Assigne la classe de présence d'une note.
	 *
	 * @param object L'ecriture'.
	 *
	 * @return object L'ecriture'.
	 *
	 */
	private function setPresenceNote($ecriture){

		if ($ecriture->note) {
			$ecriture->presence_note = "info note";
		}
		return $ecriture;
	}



	/**
	 * Assigne la classe du compte. Affichera celui-ci en css "indefini" si indéfini
	 *
	 * @param object L'ecriture'.
	 *
	 * @return object L'ecriture'.
	 *
	 */
	private function setStatutCompte($ecriture){

		if ($ecriture->compte->id == 1) {
			$ecriture->statut_compte = "indefini";
		}
		return $ecriture;
	}



	/**
	 * Passe les années clôturées en session
	 *
	 * @return array 
	 *
	 */
	public function getAnneesClotured(){
		$first_annee = \Ecriture::orderBy('date_valeur')->first(['date_valeur'])->date_valeur->formatlocalized('%Y');
		$first_annee_non_clotured = $this->getAnneesNonClotured();
		\Session::forget('tresorerie.exercice.clotured');

		for ($i=$first_annee; $i < $first_annee_non_clotured ; $i++) { 
			\Session::push('tresorerie.exercice.clotured', (string)$i);
		}
		return \Session::get('tresorerie.exercice.clotured');
	}



	/**
	 * retourne la première année non clôturée
	 * Actuellement il s'agit de l'année réelle
	 *
	 */
	public function getAnneesNonClotured(){
		return \Session::get('tresorerie.annee_reelle');
	}

}