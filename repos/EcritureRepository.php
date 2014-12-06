<?php

class EcritureRepository {


		public function find($id)
		{
			return Ecriture::find($id);
		}


		public function save($ecriture)
		{
			$ecriture->save();
		}


	}
