<?php

class Signe extends Eloquent {


	/* —————————  RELATIONS  —————————————————*/

public function ecriture()
{
	return $this->hasMany('Ecriture');
}


	/* —————————  ACCESSORS  —————————————————*/



	/* —————————  MUTATORS  —————————————————*/

}