<?php 
namespace lib\tresorerie\traits;
/**
* 
*/
trait ModelTrait
{
	
	public static function listForInputSelect($attribut, $scope = null, $defaut = true)
	{

		if ($scope !== null) {
			$scope = 'scope'.$scope;
			$list = static::$scope();

		}else{

			foreach(static::get(['id', $attribut]) as $item)
			{
				$list[$item->id] = $item->{$attribut};
			}
		}
		if ($defaut === true) {
		$list[0] = CREATE_FORM_DEFAUT_LIST;
		}

		return $list;
	}

	public static function fillFormForCreate(){
		return static::$default_values_for_create;
	}

}
