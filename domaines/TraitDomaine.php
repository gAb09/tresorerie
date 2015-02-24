<?php
namespace Tresorerie\Domaines;

trait TraitDomaine {

	private $prev_mois = 'premier';


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
	private function classementParMois($ligne, $collection, $order, $last){

			/* Pour pouvoir accéder depuis une ligne à la ligne précédente :
			Ajouter un attribut rang à chaque ligne.


			/* Extraire le mois et l’année du critère de classement */
			$ligne->mois_classement = \DatesFr::classAnMois($ligne->{$order});

			/* Il s'agit du premier mois de la page ?
			Assigner $mois_nouveau de cette ligne à "premier"*/
			if ($this->prev_mois == 'premier')
			{
				$ligne->mois_nouveau = 'premier';

				/* Il y a changement de mois ? */
			}elseif($ligne->mois_classement != $this->prev_mois)
			{
				/* Assigner $mois_nouveau de cette ligne à "nouveau" */
				$ligne->mois_nouveau = 'nouveau';
				$prev_rang = $ligne->rang -1;
				/* Assigner $last de la ligne précédente à "true" */
				$collection[$prev_rang]->last = true;
			}

			/* On n'oublie pas la toute dernière ligne de la page.*/
			if ($ligne->rang == $last) {
				$ligne->last = true;
			}

			/* E  Enfin, on passe le mois de classement de cette ligne 
			dans $prev_mois pour comparaison avec la ligne suivante */
			$this->prev_mois = $ligne->mois_classement;


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
	private function setStatutCompte($ligne){

			if ($ligne->compte->id == 1) {
				$ligne->statut_compte = "indefini";
			}
			return $ligne;
		}

}
