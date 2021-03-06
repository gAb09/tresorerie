<?php

/*
|--------------------------------------------------------------------------
| Section prefix "tresorerie"
|--------------------------------------------------------------------------
|
|
*/
Route::group(array('prefix' => 'tresorerie', 'before' => 'auth'), function() 
{
	Route::group(array('before' => 'tresorerie'), function() 
	{

		Route::get('/', function(){
			return Redirect::to('tresorerie/journal');
		});

		Route::get('statuts', function(){
			return View::make('tresorerie/views/statuts/visu');
		});


		/*----------------------  Export  -----------------------------*/
		Route::get('export/{id?}', array('as' => 'export', 'uses' => 'ExportController@export'));
		Route::get('export2', array('as' => 'export2', 'uses' => 'ExportController@export2'));


		/*----------------------  Journal  -----------------------------*/
		Route::get('journal/{id?}/{annee?}', ['as' => 'journal', 'uses' => 'JournalController@index']);


	// /*----------------------  Pointage  ----------------------------------*/
		Route::get('pointage/{id?}/{annee?}', ['as' => 'pointage', 'uses' => 'PointageController@index']);


	// /*----------------------  Recherche  ----------------------------------*/
		Route::get('recherche', 'EcritureController@recherche');


	// /*----------------------  Analytique  ----------------------------------*/
		Route::get('analytique', 'EcritureController@analytique');


		/*----------------------  Écritures  ----------------------------------*/
		// Route::put('ecritures/{id}/ok', array('as' => 'confirmupdate', 'uses' => 'EcritureController@update'));
		Route::get('ecritures/banque/{banque}', array('as' => 'bank', 'uses' => 'EcritureController@index'));
		Route::get('banque/dupli/{banque}', array('as' => 'dupli', 'uses' => 'EcritureController@duplicate'));
		Route::resource('ecritures', 'EcritureController');


		/*----------------------  Types  ----------------------------------*/
		Route::resource('types', 'TypeController');


		/*----------------------  Comptes  ----------------------------------*/
		Route::get('comptes/freres', 'CompteController@freres');
		Route::get('comptes/{id?}/freres', 'CompteController@freres');
		Route::get('comptes/classe/{root?}', 'CompteController@index');
		Route::any('comptes/updateactif', array('as' => 'tresorerie.comptes.updateActif', 'uses' => 'CompteController@updateActif'));
		Route::resource('comptes', 'CompteController');


		/*----------------------  Banques  ----------------------------------*/
		Route::resource('banques', 'BanqueController');


		/*----------------------  Transfert (vers une nouvelle année)  ----------------------------------*/
		Route::get('transferts', array('as' => 'transferts_index', 'uses' => 'TransfertController@index'));
		Route::get('transferts/setTransferable/{ecriture}', array('as' => 'setTransferable', 'uses' => 'TransfertController@setTransferable'));
		Route::get('transferts/dotransfert', array('as' => 'dotransfert', 'uses' => 'TransfertController@doTransfert'));
		Route::get('transferts/resetTransfert', array('as' => 'resetTransfert', 'uses' => 'TransfertController@resetTransfert'));


		/*----------------------  Notes  ----------------------------------*/
		Route::resource('notes', 'NoteController');


		/*----------------------  Statuts  ----------------------------------*/
		Route::post('statut/incremente/{id?}-{statuts?}', array('as' => 'incrementeStatut', 'uses' => 'EcritureController@incrementeStatut'));
		Route::get('statutsvisu', 'StatutController@visu');
		Route::get('statuts', 'StatutController@index');
		Route::resource('statuts', 'StatutController');

	});

/*----------------------  Prévisionnel  ----------------------------------*/
Route::get('previsionnel/{annee?}', 'PrevController@index');
Route::get('previsionnel/priorite/{idee?}', 'BanqueController@setPriorite');


});  // Fin de groupe prefix “tresorerie”


