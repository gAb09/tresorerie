<?php
use \Tresorerie\Domaines\ModesTraitDomaine as ModesTraitDomaine;

class PrevDomaine {
	use ModesTraitDomaine;

	private $skip = array();

	private $orphelin = array();

	private $rang = 0;

	/* Les statuts autorisés (séparés par un "-") */
	private $statuts_autorised = '1-2';

	/* Les statuts autorisés (séparés par un "-") */
	private $order = 'date_valeur';


	public function collectionPrev($banques, $annee, $banque_ref = 1)
	{
		$ecritures = Ecriture::with('signe', 'type', 'banque', 'statut', 'compte', 'ecriture2')
		->leftJoin("ecritures as soeur", function($join)
		{
			$join->on('soeur.id', '=', "ecritures.soeur_id")
			;

		})
		->where("ecritures.$this->order", 'like', $annee.'%')
		->where(function($query) use ($banque_ref){
			$query->whereNull("ecritures.is_double") // Toutes les écritures simples

			/* Pour les écritures doubles :

			Si on faisait  banque_id = ref  ou  soeur.banque_id = ref
			on n'aurait pas les écritures doubles n'impliquant pas la banque de référence
			(entre 2 autres banques donc).

			Si on faisait banque_id != ref, 
			on n'aurait pas les écritures doubles impliquant la banque de référence.

			En faisant soeur.banque_id != ref, on a toutes les écritures doubles.
			Mais attention pour la banque de référence nous n'auront qu'une seule écriture,
			(ce qui nous convient)
			alors que pour les autres banques les 2 écritures soeurs seront présentes.
			Il va donc falloir faire un tri plus loin dans le script

			*/
			->orWhere('soeur.banque_id', '!=', $banque_ref); 
		})
		->orderBy("ecritures.$this->order")
		->orderBy("ecritures.banque_id")
		->select(["ecritures.*", 'soeur.banque_id as banque_soeur_id'])
		->get()
		;

		if ($ecritures->isEmpty())
		{

			return false;
		}
// dd($ecritures);

		/* La collection $ecritures n'est pas vide, on peut lancer le traitement */

		/* Initialiser les soldes (faire le report de l'année précédente) */

		$this->solde = array();
		$this->solde['total'] = 0;

		foreach ($banques as $bank) {
			$this->solde[$bank->id] = 0;
		}

		/* Déterminer le rang de la dernière écriture de la page. */
		$last = $ecritures->count() -1;

		$order = $this->order;
		$ecritures->each(function($ecriture) use ($ecritures, $order, $banques, $last) {

			/* Affecter la valeur de la propriété $this-rang initialisée à 0. */
			$ecriture->rang = $this->rang;

			/* Incrémenter pour la ligne suivante */
			$this->rang++;


			/* ----  Traitement du regroupement par mois ----- */
			$this->classementParMois($ecriture, $ecritures, $order, $last);

		});

		/* ----- Traitement des soldes par banques ----- */
		$ecritures->each(function($ecriture) use ($ecritures, $order, $banques) 
		{

			/* On intègre signe et montant, et réassigne $ecriture->montant */
			$ecriture->montant = $ecriture->montant * $ecriture->signe->signe; // aFa factoriser dans helper

			foreach ($banques as $bank) {

				/* On calcule les soldes de chaque banque à chaque ligne
				Attention le calcul est différent s'il s'agit d'une écriture double ou simple */

				/* On conserve les soldes de l'écriture précédente 
				pour déterminer s'ils seront affichés ou non. */
				$nbre_banques = $banques->count();
				$i = 1;

				while ($i <= $nbre_banques) {
					$prev_solde_ = 'prev_solde_'.$i;
					$$prev_solde_ = $this->solde[$i];
					$i++;
				}

				/* Si l'écriture concerne cette banque */
				if($ecriture->banque_id == $bank->id){

					/* Si l'écriture est simple */
					if (!$ecriture->is_double){
						$this->solde[$bank->id] +=  $ecriture->montant;
						$this->solde['total'] += $ecriture->montant;
					}

					/* Si l'écriture est double et concerne la banque principale (1) */
					if (!is_null($ecriture->is_double) and $ecriture->banque_id == 1) {
						$this->solde[1] +=  $ecriture->montant;
						$this->solde[$ecriture->banque_soeur_id] -=  $ecriture->montant;
					}

					/* Si l'écriture est double, 
					ne concerne pas la banque principale (1)
					et dont la soeur n'a pas été encore traitée */
					if (!is_null($ecriture->is_double) 
						and $ecriture->banque_id != 1) 
					{
						if (!in_array($ecriture->id, $this->skip)) 
						{

							/* Considérant un ordre de priorité en faveur des banques 
							ayant l'id le plus faible, on compare les 2 id.
							S'il est plus faible on traite cette écriture et on "skip" sa soeur.
							Sinon on fait l'inverse */
							if($ecriture->banque_id < $ecriture->banque_soeur_id)
							{
								$this->solde[$ecriture->banque_id] +=  $ecriture->montant;
								$this->solde[$ecriture->banque_soeur_id] -=  $ecriture->montant;

								// On ajoute l'écriture soeur à la liste des skip
								$this->skip[] = $ecriture->soeur_id;
							}else{

								// On ne tient pas compte de cette écriture
								unset($ecritures[$ecriture->rang]);
							}
						}else{
							// Si elle est dans ce tableau elle saute !
							unset($ecritures[$ecriture->rang]);
						}

					}

					/*  On affecte les soldes à l'écriture */

					$i = 1;

					while ($i <= $nbre_banques) {
						$solde_ = 'solde_'.$i;

						$ecriture->$solde_ = $this->solde[$i];
						$i++;
					}
					$ecriture->solde_total = $this->solde['total'];

					/*  On affiche ou non chaque solde selon qu'il a changé ou non */
					$i = 1;

					while ($i <= $nbre_banques) {
						$show_ = 'show_'.$i;
						$prev_solde_ = 'prev_solde_'.$i;

						$ecriture->$show_ = ($this->solde[$i] == $$prev_solde_)? false : true;
						$i++;
					}
				}
			}
		});
	return $ecritures;
	}

}

