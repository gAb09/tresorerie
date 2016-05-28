<?php

class ExportDomaine extends BaseController {

	private $espace = ".";

	private $pipe = "|";

	private $txt = "";


	public function collectionExportGlobal()
	{

		$ecritures = Ecriture::with('signe', 'type', 'banque', 'statut', 'compte', 'ecriture2')
		->whereIn('banque_id', [1, 2])
		->orderBy('date_valeur')
		->get();
dd($ecritures[0]->toArray());
		if ($ecritures->isEmpty())
		{
			return false;
		}


		/* Déterminer le rang de la dernière écriture de la page. */
		$last = $ecritures->count() -1;

		/* Lancer la boucle sur la collection */
		$ecritures->each(function($ecriture) use ($ecritures, $order, $last) {

			$ecriture = $this->ComposeLigneGlobal($ecriture);
			$this->txt .= $ecriture;
// var_dump($ecriture);

		});

		return $this->txt;

	}



	private function ComposeLigneGlobal($ecriture)
	{
		$ligne = '';

		$ligne .= $this->FormatDateOperation($ecriture);
		$ligne .= $this->FormatNumeroFolio($ecriture);
		$ligne .= $this->FormatNumeroEcriture($ecriture);
		$ligne .= $this->FormatJourEcriture($ecriture);
		$ligne .= $this->FormatCompte($ecriture);
		$ligne .= $this->FormatDebit($ecriture);
		$ligne .= $this->FormatCredit($ecriture);
		$ligne .= $this->FormatLibelle($ecriture);
		$ligne .= $this->FormatLettrage($ecriture);
		$ligne .= $this->FormatCodePiece($ecriture);
		$ligne .= $this->FormatCodeStatist($ecriture);
		$ligne .= $this->FormatDateEcheance($ecriture);		
		$ligne .= $this->FormatMonnaie($ecriture);
		$ligne .= $this->FormatFiller1($ecriture);		
		$ligne .= $this->FormatIndCompteur($ecriture);
		$ligne .= $this->FormatQuantite($ecriture);
		$ligne .= $this->FormatFiller2($ecriture);			
		$ligne .= "<br />";

		return $ligne;
	}



	public function collectionExport($id, $order)
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

			$ecriture = $this->ComposeLigne($ecriture);
			$this->txt .= $ecriture;
// var_dump($ecriture);

		});

		return $this->txt;


	}



	private function ComposeLigne($ecriture)
	{
		$ligne = '';

		$ligne .= $this->FormatCodeJournal($ecriture);
		$ligne .= $this->FormatDateOperation($ecriture);
		$ligne .= $this->FormatNumeroFolio($ecriture);
		$ligne .= $this->FormatNumeroEcriture($ecriture);
		$ligne .= $this->FormatJourEcriture($ecriture);
		$ligne .= $this->FormatCompte($ecriture);
		$ligne .= $this->FormatDebit($ecriture);
		$ligne .= $this->FormatCredit($ecriture);
		$ligne .= $this->FormatLibelle($ecriture);
		$ligne .= $this->FormatLettrage($ecriture);
		$ligne .= $this->FormatCodePiece($ecriture);
		$ligne .= $this->FormatCodeStatist($ecriture);
		$ligne .= $this->FormatDateEcheance($ecriture);		
		$ligne .= $this->FormatMonnaie($ecriture);
		$ligne .= $this->FormatFiller1($ecriture);		
		$ligne .= $this->FormatIndCompteur($ecriture);
		$ligne .= $this->FormatQuantite($ecriture);
		$ligne .= $this->FormatFiller2($ecriture);			
		$ligne .= "<br />";

		return $ligne;
	}


	private function FormatDateOperation($ecriture)
	{
			// Date opération		4-11	8 A		0
			// Date de la pièce. Format JJMMAAAA

		$champs = $this->formate(null, 8);

		$champs .= $this->pipe;

		return $champs;
	}


	private function formate($data, $nbre_car_requis, $alignement = "gauche") // utiliser str_pad ?
	{
		$nbre_car_data = strlen($data);
		$split_data = str_split($data, $nbre_car_requis);
		if (($excedent = $nbre_car_data - $nbre_car_requis) > 0) {
			return $split_data[0]."<span style=\"color:red\">$split_data[1]</span> <span style=\"color:grey\">($excedent caractères en trop)</span>";
		}else{
			$nbre_espaces = $nbre_car_requis - $nbre_car_data;
			$espaces = $this->insertEspace($nbre_espaces);
		}
		

		if ($alignement == "droite") {
			$champs = $espaces.$data;
		}else{
			$champs = $data.$espaces;
		}

		return $champs;
	}

	private function insertEspace($nbre)
	{
		$espaces = '';

		for ($i=0; $i < $nbre; $i++) { 
			$espaces .= $this->espace;
		}
		return $espaces;
	}

	private function FormatCodeJournal($ecriture)
	{
			// Code journal          1-2                       2 A                       0               Code du journal comptable 
			// Le code journal « 0Y » correspond à une balance 
			// Si un code journal est présent dans les écritures mouvementées mais n'existe pas dans les journaux du dossier WINFIC,
			// il y a création automatique avec pour libellé «Créé en importation ». */

		$champs = $this->formate(null, 4);

		$champs .= $this->pipe;

		return $champs;
	}

	private function FormatDateFiducial($ecriture)
	{
			// Date opération		4-11	8 A		0
			// Date de la pièce. Format JJMMAAAA

		$champs = $this->formate(null, 8);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatNumeroFolio($ecriture)
	{
			// N° du folio	13-18	6 N		0
			// Cadré à droite. Ce numéro est égal à 1 s'il n'existe pas de numérotation de folio dans le logiciel émetteur.


		$champs = $this->formate(null, 6);

		$champs .= $this->pipe;

		return $champs;
	}

	private function FormatNumeroEcriture($ecriture)
	{
			// N° écriture 	20-25  	6 N  	0
			// N° de l écriture dans  le folio. Cadré à droite. Numérotation séquentielle. Deux écritures ne peuvent pas avoir le même numéro.



		$champs = $this->formate(null, 6);

		$champs .= $this->pipe;

		return $champs;
	}

	private function FormatJourEcriture($ecriture)
	{
			// Jour écriture	27-32	6 N		0
			// Jour de date  opération ( jj/mm/aa ). Cadré à droite.

		$champs = $this->formate($ecriture->date_emission->format('dmy'), 6, "droite");

		$champs .= $this->pipe;

		return $champs;
	}

	private function FormatCompte($ecriture)
	{
			// Compte	34-39	6 A	0
			// Numéro du compte général ou auxiliaire.
			/* Les comptes auxiliaires clients doivent commencer  par un "9", les comptes auxiliaires fournisseurs par un"0" (zéro).
			Ces deux types de comptes peuvent comporter des caractères alphanumériques. Les comptes généraux sont exclusivement numériques.
			
			Les comptes doivent obligatoirement être sur 6 caractères (compléter par des zéros si nécessaire).
			
			Les libellés des comptes doivent figurer en liste à la fin de ce fichier sous un code journal le.
			Si un compte mouvementé n'est pas présent dans les enregistrements « 1e », la création dans le plan comptable est effectuée avec le libellé « Libellé à créer ».  

			Pour chaque compte, création d'un enregistrement respectant les règles générales de ce fichier :
			Date = date de la première écriture transférée,
			N° de folio = 1,
			N°écriture : de 1 à n avec un incrément de 1 à chaque enregistrement,
			Montants = 0,00, ...*/

		$champs = $this->formate(null, 6);

		$champs .= $this->pipe;

		return $champs;
	}

	private function FormatDebit($ecriture)
	{
			// Montant Débit	41-53	13 N	0
			// 9(10),99. = 0,00 si non valorisé
			// Séparateur décimal =  ,   obligatoire.  
			// = 0,00 si non valorisé. Valeurs négatives interdites.

		$champs = $this->formate($ecriture->debit, 13);

		$champs .= $this->pipe;

		return $champs;
	}

	private function FormatCredit($ecriture)
	{
			// Montant Crédit	41-53	13 N	0
			// 9(10),99. = 0,00 si non valorisé
			// Séparateur décimal =  ,   obligatoire.  
			// = 0,00 si non valorisé. Valeurs négatives interdites.

		$champs = $this->formate($ecriture->credit, 13);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatLibelle($ecriture)
	{
			// libellés	69-98	30 A	0
			// Libellé de l écriture (ou libellé du compte si journal 1e)
			$ecriture->libelle_complet = $ecriture->libelle;
			if ($ecriture->libelle_detail) {
				$ecriture->libelle_complet .= " – ".$ecriture->libelle_detail;
			}


		$champs = $this->formate($ecriture->libelle_complet, 30);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatLettrage($ecriture)
	{
			// lettrage	100-101		2 A		F
			// Caractères de lettrage.

		$champs = $this->formate(null, 2);

		$champs .= $this->pipe;

		return $champs;
	}
	
	
	private function FormatCodePiece($ecriture)
	{
			// Code pièce		103-107		5 A		F
			// Numéro  de la pièce


		$champs = $this->formate(null, 5);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatCodeStatist($ecriture)
	{
			// Code statist.	109-112		4 A		F
			// = blancs


		$champs = $this->formate(null, 4);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatDateEcheance($ecriture)
	{
			// Date échéance	114-121		8 A		F
			// Date d échéance. Format  JJMMAAAA


		$champs = $this->formate($ecriture->date_valeur->format('dmY'), 8);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatMonnaie($ecriture)
	{
			// Monnaie	123-123		1 A		0
			// = 0 si montant exprimé en Francs
			// = 1 si montant exprimé en Euros


		$champs = $this->formate(1, 1);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatFiller1($ecriture)
	{
			// Filler		125-125		1 A		F
			// = blanc

		$champs = $this->formate(null, 1);

		$champs .= $this->pipe;

		return $champs;
	}



	private function FormatIndCompteur($ecriture)
	{
			// Ind. Compteur	127-127		1 A		F
			// = 'I' ,'C','T','F'  (codification interne Fiducial)

		$champs = $this->formate(null, 1);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatQuantite($ecriture)
	{
			// Quantité	129-139		11 N	F
			// = 0,000  (Pas utilisé en Winfic)


		$champs = $this->formate(null, 11);

		$champs .= $this->pipe;

		return $champs;
	}


	private function FormatFiller2($ecriture)
	{
			// Filler	141-142		2 A		F
			// = Code pointage


		$champs = $this->formate(null, 2);

		$champs .= $this->pipe;

		return $champs;
	}
// WINFIC : Dans les journaux de trésorerie les dates d'échéances correspondent aux dates de l'écriture (Pas de saisie de dates d'échéances en saisie de règlement).
}