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

		/* Déterminer le rang de la dernière écriture de la page. */
		$this->last = $collection->count() -1;

		/* Relever le mois et l’année du critère de classement */
		$ligne->mois_classement = \DatesFr::classAnMois($ligne->{$order});

		/* Il s'agit du premier mois de la page ?
		Assigner $index_ligne de cette ligne à "premier_mois"*/
		if ($this->prev_mois == '')
		{
			$ligne->premier_mois = true;

			/* Il y a changement de mois ? */
		}elseif ($ligne->mois_classement != $this->prev_mois)
		{
			/* Assigner $index_ligne de cette ligne à "nouveau_mois" */
			$ligne->nouveau_mois = true;

			/* Assigner $index_ligne de la ligne précédente à "der_du_mois" */
			$prev_rang = $ligne->rang -1;
			$collection[$prev_rang]->der_du_mois = true;
			
		}

		/* Conserver le mois classement pour comparaison avec la prochaine ligne.*/
		$this->prev_mois = $ligne->mois_classement;

		/* On n'oublie pas la toute dernière ligne de la page.*/
		if ($ligne->rang == $this->last) {
			$ligne->fin_page = true;
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
	 * Affecter à chaque ligne un rang,
	 * qui sera répercuté en id dansla vue.
	 * 
	 */
	public function affecterRangs($ecriture, $collection){

		/* Affecter son rang à l'écriture. */
		$ecriture->rang = $this->rang;

		/* Incrémenter pour la ligne suivante */
		$this->rang++;
	}

}