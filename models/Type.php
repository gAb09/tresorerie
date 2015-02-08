<?php

class Type extends Eloquent {

	protected $guarded = array('id'); // AFA
	protected $softDelete = true; // AFA

	protected static $unguarded = true; // AFA


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