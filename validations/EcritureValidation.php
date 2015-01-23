<?php namespace tresorerie\Validations;

use Lib\Validations\ValidationBase;

class EcritureValidation extends ValidationBase
{
	protected $rules = array(
		'banque_id' => 'not_in:0',
		'date_emission' => 'required|alpha_dash|date',
		'date_valeur' => 'required|alpha_dash|date|afteremission',
		'montant' => 'required|fnumeric|notnull|positif',
		'signe_id' => 'required',
		'libelle' => 'required|not_in:CREATE_FORM_DEFAUT_TXT_LIBELLE',
		'libelle_detail' => 'not_in:CREATE_FORM_DEFAUT_TXT_LIBELLE_COMPL',
		'type_id1' => 'not_in:0',
		'justificatif' => 'required_if:req_justif,1',
		'compte_id' => 'not_in:0',
		);

	protected $messages = array(
		'banque_id.not_in' => 'Vous n’avez pas selectionné de “Banque”.',
		'date_emission.alpha_dash' => 'Le séparateur doit être un tiret (Date d’émission).',
		'date_emission.date' => 'Cette date n’existe pas et/ou n’est pas au format : jj-mm-aaaa (Date d’émission).',
		'date_valeur.alpha_dash' => 'Le séparateur doit être un tiret (Date de valeur).',
		'date_valeur.date' => 'Cette date n’existe pas et/ou n’est pas au format : jj-mm-aaaa (Date de valeur).',
		'date_valeur.afteremission' => 'La date de valeur doit être postérieure ou égale à la date d’émission',
		'montant.notnull' => 'Le montant ne peut être égal à 0',
		'montant.fnumeric' => 'Le montant ne doit contenir que des chiffres, et éventuellement une virgule et des espaces',
		'montant.positif' => 'Le montant ne peut être un nombre négatif. Vous devez utiliser un nombre positif classé en “Dépense”.',
		'signe_id.required' => 'Vous n’avez pas précisé s’il s’agit d’une dépense ou d’une recette.',
		'libelle.required' => 'Vous n’avez pas indiqué de Libellé.',
		'libelle.not_in' => 'Vous n’avez pas indiqué de Libellé.',
		'libelle_detail.not_in' => 'Si vous ne souhaitez pas préciser le libellé, il faut laisser le champs “Libellé détail” vide.',
		'type_id1.not_in' => 'Vous n’avez pas selectionné de “Type”.',
		'justificatif.not_in' => 'Si vous ne précisez pas de justificatif, il vaut mieux laisser le champs “Justificatif” vide.',
		'justificatif.required_if' => 'Ce type d’écriture impose un justificatif, veuillez refaire la sélection du type et préciser le justificatif.',
		'compte_id.not_in' => 'Vous n’avez pas selectionné de “Compte”.',
		);
}