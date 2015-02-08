<?php
use \Tresorerie\Domaines\TraitDomaine as TraitDomaine;

class JournalDomaine {
	use TraitDomaine;

	private $cumul_dep_mois = 0.0;

	private $cumul_rec_mois = 0.0;

	private $cumul_absolu = 0.0;

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

		/* Lancer la boucle sur la colection */
		$ecritures->each(function($ecriture) use ($ecritures, $order, $last) {

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
					$this->cumul_dep_mois = 0;
					$this->cumul_rec_mois = 0;
				}

			/* Calculer les cumuls */
			if($ecriture->signe_id == 1){
				$this->cumul_dep_mois += $ecriture->montant;
				$this->cumul_absolu -= $ecriture->montant;
			}
			if($ecriture->signe_id == 2){
				$this->cumul_rec_mois += $ecriture->montant;
				$this->cumul_absolu += $ecriture->montant;
			}

			/* Affecter les cumuls à l'écriture */
			$ecriture->cumul_dep_mois = $this->cumul_dep_mois;
			$ecriture->cumul_rec_mois = $this->cumul_rec_mois;
			$ecriture->solde = $this->cumul_rec_mois - $this->cumul_dep_mois;
			$ecriture->cumul_absolu = $this->cumul_absolu;

		});

		return $ecritures;

	}

}
