<?php
use Baum\Node;

class Compte extends Node {

	/* Accès au listes pour input select */

	protected $guarded = array('id');


	/* —————————  RELATIONS  —————————————————*/

	public function ecriture()
	{
		return $this->hasMany('Ecriture');
	}

	/* —————————  SCOPES  —————————————————*/

	public static function scopeParentable()
	{
		$result = self::all(array('id', 'libelle', 'numero'));
		$items = $result->filter(function($item)
		{
			if (strlen($item->numero) < 6) {
				return $item;
			}
		});

		foreach($items as $item)
		{
			$list[$item->id] = '('.$item->numero.') '.$item->libelle;
		}
		return $list;
	}

	public static function scopeActivable()
	{
		$items = self::where('actif', '0')->orderBy('lft')->get(array('id', 'libelle', 'numero', 'actif'));

		foreach($items as $item)
		{
			$list[$item->id] = '('.$item->numero.') '.$item->libelle;
		}
		return $list;
	}

	// public static function scopeFreres()
	// {
	// 	foreach(self::all(array('id', 'libelle', 'numero')) as $item)
	// 	{
	// 		$parent = Compte::where('id', Input::get('parent'))->first();
	// 		$freres = $parent->getImmediateDescendants();
	// 		$list[$item->id] = $item->numero.' : '.$item->libelle;
	// 	}
	// 	return $list;
	// }

	public static function scopeActif()
	{
		foreach(self::where('actif', 1)->get(array('id', 'libelle', 'numero')) as $item)
		{
			$list[$item->id] = '('.$item->numero.') '.$item->libelle;
		}
		return $list;
	}


	/* —————————  ACCESSORS  ————————————————— */

	public function getNumeroAttribute($value)
	{
		settype($value, 'string');
		return $value;
	}
}
