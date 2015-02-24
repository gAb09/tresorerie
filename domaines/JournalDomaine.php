<?php
use \Tresorerie\Domaines\TraitDomaine as TraitDomaine;

class JournalDomaine {
	use TraitDomaine;

	private $somme_dep_mois = 0.0;

	private $somme_rec_mois = 0.0;

	private $cumul = 0.0;

	private $rang = 0;

	public function collectionJournal($id, $order)
	{

		$ecritures = Ecriture::with('signe', 'type', 'banque', 'statut', 'compte', 'ecriture2')
		->where('banque_id', $id)
		->orderBy($order)
		->get();

		if ($ecritures->isEmpty())
		{
			return false;
		}

		/* Déterminer le rang de la dernière écriture de la page. */
		$last = $ecritures->count() -1;

		/* Lancer la boucle sur la collection */
		$ecritures->each(function($ecriture) use ($ecritures, $order, $last) {

			/* Gérer l'existence d'une note */
			$ecriture = $this->setPresenceNote($ecriture);

			/* Gérer la classe CSS du compte */
			$ecriture = $this->setClassCompte($ecriture);

			/* Affecter la valeur de la propriété $this-rang initialisée à 0. */
			$ecriture->rang = $this->rang;

			/* Incrémenter pour la ligne suivante */
			$this->rang++;

			/* ----- Traitement du classement par mois ----- */
			$this->classementParMois($ecriture, $ecritures, $order, $last);


			/* ----- Traitement des soldes ----- */

			/* Réinitialiser les cumuls pour la première ecriture de chaque mois */
				if($ecriture->mois_nouveau == 'nouveau')
				{
					$this->somme_dep_mois = 0;
					$this->somme_rec_mois = 0;
				}

			/* Calculer les cumuls */
			if($ecriture->signe_id == 1){
				$this->somme_dep_mois += $ecriture->montant;
				$this->cumul -= $ecriture->montant;
			}
			if($ecriture->signe_id == 2){
				$this->somme_rec_mois += $ecriture->montant;
				$this->cumul += $ecriture->montant;
			}

			/* Affecter les cumuls à l'écriture */
			$ecriture->somme_dep_mois = $this->somme_dep_mois;
			$ecriture->somme_rec_mois = $this->somme_rec_mois;
			$ecriture->solde_mois = $this->somme_rec_mois - $this->somme_dep_mois;
			$ecriture->cumul = $this->cumul;

		});

		return $ecritures;

	}

}