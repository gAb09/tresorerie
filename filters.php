<?php

Route::filter('tresorerie', function()
{
	if (Auth::user()->role_id == 2 or Auth::user()->role_id == 1)
	{

	}else{
		return Redirect::back()->with('erreur', 'Vous n’avez pas les droits d’accès à la section Trésorerie');
	}
});


?>