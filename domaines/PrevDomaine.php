<?php
use \Tresorerie\Domaines\ModesTraitDomaine as ModesTraitDomaine;
use \Illuminate\Database\Eloquent\Collection as Collection;

class PrevDomaine {
	use ModesTraitDomaine;

	private $skip = array();

	private $orphelin = array();

	private $rang = 0;

	private $cumul = array();


	/* Les statuts autorisés (séparés par un "-") */
	private $statuts_autorised = '1-2';

	/* Les statuts autorisés (séparés par un "-") */
	private $order = 'date_valeur';


	public function collectionPrev($banques, $annee, $banque_ref = 1)
	{
		/* Déterminer l'opérateur selon que la sélection doit être faite sur une ou plusieurs années */
		if ($annee == \Session::get('tresorerie.annee_reelle')) {
			$operateur = '>=';
		}else{
			$operateur = 'like';
		}

		$ecritures = Ecriture::with('signe', 'type', 'banque', 'statut', 'compte', 'ecriture2')
		->leftJoin("ecritures as soeur", function($join)
		{
			$join->on('soeur.id', '=', "ecritures.soeur_id")
			;

		})
		->where("ecritures.$this->order", $operateur, $annee.'%')
		->orderBy("ecritures.$this->order")
		->orderBy("ecritures.banque_id")
		->select(["ecritures.*", 'soeur.banque_id as banque_soeur_id'])
		->get()
		;

		if ($ecritures->isEmpty())
		{

			return false;
		}

		/* La collection $ecritures n'est pas vide, on peut lancer le traitement */

		/* Ne garder qu'une seule des 2 écriture liées, en tenant compte des priorités des banques */
		$ecritures = $ecritures->filter(function($ecriture){
			return $this->filtrerEcrituresLiees($ecriture);
		});


		/* Réindexer la collection pour éviter les “trous” et les n-1 qui déclencheront une erreur*/
		$ecritures = $ecritures->flatten();


		/* Initialiser les tableaux pour les cumuls */
		$this->cumul['global'] = 0;
		foreach ($banques as $bank) {
			$i = $bank->id;
			$this->cumul[$i] = 0;
			$this->cumul['prev'][$i] = 0;
		}


		/* Rangs, calculs et affichage */
		$ecritures = $ecritures->each(function($ecriture) use($ecritures, $banques){

			/* Affecter les rangs */
			$this->affecterRangs($ecriture, $ecritures);

			/* Préparer l'affichage par mois dans la vue */
			$order = $this->order;
			$this->affichageParMois($ecriture, $ecritures, $order);

			/* Signer les montants */
			$this->signerMontants($ecriture);

			/* Gérer les cumuls */
			$this->gererCumulsBanques($ecriture, $banques);

		});
		return $ecritures;
	}

	/**
	 * Conserver toutes les écritures simples
	 * et retirer les écritures liées doublonnant
	 * en tenant compte de la priorité des banques.
	 * 
	 */
	public function filtrerEcrituresLiees($ecriture){
		if (!$ecriture->is_double)
		{
			return true;
		}

		if ($ecriture->banque->priorite < $ecriture->ecriture2->banque->priorite)
		{
			return true;
		}

		return false;
	}

	/**
	 * Affecter à chaque ligne un rang,
	 * qui sera répercuté en id dansla vue.
	 * 
	 */
	public function affecterRangs($ecriture, $collection){

		/* Déterminer le rang de la dernière écriture de la page. */
		$this->last = $collection->count() -1;

		/* Affecter son rang à l'écriture. */
		$ecriture->rang = $this->rang;

		/* Incrémenter pour la ligne suivante */
		$this->rang++;
	}

	/**
	 * Signer les montants.
	 * 
	 */
	public function signerMontants($ecriture){

		$ecriture->montant_signed = $ecriture->montant * $ecriture->signe->signe;

		if($ecriture->is_double)
		{
			$ecriture->montant2_signed = 
			$ecriture->ecriture2->montant * $ecriture->ecriture2->signe->signe;
		}
	}


	/**
	 * Gérer les cumuls de chaque banque.
	 * 
	 */
	public function gererCumulsBanques($ecriture, $banques){

		foreach ($banques as $bank) {
			/* Si cette banque concerne l'écriture */

			if($ecriture->banque_id == $i){
				$bank = $bank->id;
				$this->gererCumulsBank($bank, $ecriture->montant_signed)
			}

			/* Si il s'agit d'une écriture liée */
			if($ecriture->is_double == 1)
			{
				$bank2 = $ecriture->ecriture2->banque_id;
				$this->gererCumulsBank($bank2, $ecriture->montant2_signed)
			}

		}

		/*  Affecter à la ligne */
		$ecriture->global = $this->cumul['global'];
		$ecriture->$i = $this->cumul[$i];

	}


	/**
	 * Gérer les cumuls d'une banque.
	 * 
	 */
	public function gererCumulsBank($bank, $montant){

		/* calculer le cumul de cette banque pour cette ligne */
		$this->cumul[$bank] += $montant;

		/* Conserver sa valeur pour attester si changement en ligne suivante */
		$this->cumul['prev'][$bank] = $this->cumul[$bank];

		/* Calculer le cumul global */
		$this->cumul['global'] += $montant;

		/*  Afficher ou non chaque cumul selon qu'il a changé ou non */
		if ($this->cumul[$bank] == $this->cumul['prev'][$bank]) {
			$ecriture->{'show_'.$bank} = true;
		}

	}
}

