<?php namespace Lib\Validations;

class EcritureDoubleValidation extends ValidationBase
{
	public $rules = array(
		'banque2_id' => 'not_in:0|different:banque_id',
		'justif2' => 'required_if:req_justif2,1|not_in:CREATE_FORM_DEFAUT_TXT_JUSTIF',
		'type_id2' => 'not_in:0',
		);

	public $messages = array(
		'banque2_id.not_in' => 'Vous n’avez pas selectionné de banque (écriture liée).',
		'banque2_id.different' => 'Vous avez sélectionné 2 fois la même banque.',
		'justif2.not_in' => 'Si vous ne précisez pas de justificatif, il vaut mieux laisser le champs “Justificatif” vide (écriture liée).',
		'justif2.required_if' => 'Ce type d’écriture impose un justificatif, veuillez refaire la sélection et préciser le justificatif (écriture liée).',
		'type_id2.not_in' => 'Vous n’avez pas selectionné de “Type” (écriture liée).',
		);
}