<?php
use Lib\traits\ModelTrait;

class Type extends Eloquent {
  use ModelTrait;

	protected $guarded = array('id'); // AFA
	protected $softDelete = true; // AFA

	protected static $unguarded = true; // AFA

	protected static $default_values_for_create = array(
		'nom' => CREATE_FORM_DEFAUT_TXT_NOM,
		'description' => CREATE_FORM_DEFAUT_TXT_DESCRIPTION,
		'req_justif' => 0,
		'sep_justif' => CREATE_FORM_DEFAUT_TXT_SEPARATEUR,
		);

	/* —————————  RELATIONS  —————————————————*/

	public function ecriture()
	{
		return $this->hasMany('Ecriture');
	}


	/* —————————  ACCESSORS  —————————————————*/


	/* —————————  MUTATORS  —————————————————*/

	public function setSepJustifAttribute($value)
	{

		$value = ' '.$value.' ';
		$this->attributes['sep_justif'] = $value;
	}

	/* —————————  SCOPES  —————————————————*/

	public static function scopeByRang()
	{
		$items = self::orderBy('rang')->get(array('id', 'nom'));

		foreach($items as $item)
		{
			$list[$item->id] = $item->nom;
		}
		return $list;
	}


}