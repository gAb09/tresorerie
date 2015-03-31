<?php
use \Tresorerie\Domaines\ModesTraitDomaine as ModesTraitDomaine;

class PointageDomaine {
	use ModesTraitDomaine;

	private $somme_dep_mois = 0.0;

	private $somme_rec_mois = 0.0;

	private $cumul = 0.0;

	private $rang = 0;

	// Les statuts autorisés (séparés par un "-")
	private $statuts_autorised = '1-2-3-4';

	/* Le critère de classement */
	private $order = 'date_valeur';



	public function getStatutsAutorised()
	{
		return $this->statuts_autorised;
	}

	public function collectionPointage($id, $annee, $order)
	{

		$ecritures = Ecriture::with('signe', 'type', 'banque', 'statut', 'compte', 'ecriture2')
		->where('banque_id', $id)
		->where($order, 'like', $annee.'%')
		->orderBy($order)
		->get();

		if ($ecritures->isEmpty())
		{
			return false;
		}

		/* Lancer la boucle sur la collection */
		$ecritures->each(function($ecriture) use ($ecritures, $order) {

			/* Gérer l'existence d'une note */
			$ecriture = $this->setPresenceNote($ecriture);

			/* Gérer la classe CSS du compte */
			$ecriture = $this->setStatutCompte($ecriture);

			/* Affecter les rangs */
			$this->affecterRangs($ecriture, $ecritures);


			/* Préparer l'affichage par mois dans la vue */
			$order = $this->order;
			$this->affichageParMois($ecriture, $ecritures, $order);


			/* ----- Traitement des cumuls ----- */

			/* Réinitialiser les cumuls pour la première ecriture de chaque mois */
				if($ecriture->nouveau_mois)
				{
						$this->somme_dep_mois = 0;
						$this->somme_rec_mois = 0;
				}
				
			/* Calculer les cumuls */
			if($ecriture->signe_id == 1){
				$this->somme_dep_mois = $this->somme_dep_mois + $ecriture->montant;
				$this->cumul = $this->cumul - $ecriture->montant;
			}
			if($ecriture->signe_id == 2){
				$this->somme_rec_mois = $this->somme_rec_mois + $ecriture->montant;
				$this->cumul = $this->cumul + $ecriture->montant;
			}

			/* C   On affecte les cumuls à l'écriture */
			$ecriture->somme_dep_mois = $this->somme_dep_mois;
			$ecriture->somme_rec_mois = $this->somme_rec_mois;
			$ecriture->solde_mois = $this->somme_rec_mois - $this->somme_dep_mois;
			$ecriture->cumul = $this->cumul;


	});

	return $ecritures;

	}

	/**
	 * Affecter à chaque ligne un rang,
	 * qui sera répercuté en id dansla vue.
	 * 
	 */
	/* Fonction affecterRangs() dans ModesTraitDomaine */



}
