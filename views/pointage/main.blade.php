@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop

@section('assets')
@parent
	<link href="/assets/tresorerie/css/pointage.css" rel="stylesheet" type="text/css">
@stop

@section('body')
onLoad="initVolets();"
@stop


@section('titrepage')
<h1>{{ $titre_page }}</h1>
@stop




@section('contenu')

@foreach($ecritures as $ecriture)

@if($ecriture->mois_nouveau)

<table class="modes">
	<caption class="ligne_mois" id="{{$ecriture->mois_classement}}" onclick="javascript:volet(this);">
		{{ ucfirst(DatesFr::MoisAnneeInsec($ecriture->date_valeur)) }}
	</caption>

	<thead class="replie" id="tetiere{{$ecriture->mois_classement}}" >
		<th class="statut">
			Statut
		</th>
		<th class="date">
			Date de valeur
		</th>
		<th class="libelle">
			Libellé
		</th>
		<th class="montant">
			Dépenses
		</th>
		<th class="montant">
			Recettes
		</th>
		<th class="montant">
			Solde
		</th>
		<th class="type">
			Type
		</th>
		<th class="banque">
			Banque(s)
		</th>
		<th class="compte">
			Compte
		</th>
		<th class="icone iconemedium edit">
			
		</th>
	</thead>


	<tbody class="replie" id="corps{{$ecriture->mois_classement}}">

		@endif

		@include('tresorerie/views/pointage/row')

		@if($ecriture->last)
		<tr class="soldes">
			<td colspan="3">
			</td>
			<td class ='depense'>
				{{NombresFr::francais_insec($ecriture->somme_dep_mois)}}
			</td>
			<td class='recette'>
				{{NombresFr::francais_insec($ecriture->somme_rec_mois)}}
			</td>
			<td colspan="2">
				Bilan du mois : 
				@if($ecriture->solde_mois < 0)
				<span class="depense">{{NombresFr::francais_insec($ecriture->solde_mois)}}</span>
				@else
				<span class="recette">{{NombresFr::francais_insec($ecriture->solde_mois)}}</span>
				@endif
			</td>
			<td colspan="2">
				Solde à fin {{ ucfirst(DatesFr::MoisAnneeInsec($ecriture->date_valeur))}} : 
				@if($ecriture->cumul < 0)
				<span class="depense">{{NombresFr::francais_insec($ecriture->cumul)}}</span>
				@else
				<span class="recette">{{NombresFr::francais_insec($ecriture->cumul)}}</span>
				@endif
			</td>		</tr>
		@endif

		@endforeach

	</tbody>

</table>

@stop


@section('actions')

{{link_to_action('EcritureController@create', 'Ajouter une écriture', null, ["class" => "btn btn-success btn-actions iconemedium add"])}}

@stop


@section('affichage')
<div class="banques">
	@foreach(Banque::all() as $bank)
<a href ="{{ URL::route('pointage', $bank->id) }}" class="badge badge-locale badge-big {{ ($bank->nom == Session::get('ParamEnv.tresorerie.banque_nom')) ? 'badge-success' : ''}}">{{ $bank->nom }}</a>
	@endforeach
</div>

@stop


@section('footer')
@parent
<h3>  Le footer de recettes_depenses</h3>
@stop


@section('script')

<script type="text/javascript">	
<?php

/* Transmettre au javascript "incrementeStatuts()" 
le tableau de correspondance classe/id pour les statuts */
echo "var classe_statut = ".$classe_statut.";";
echo "var statuts_accessibles = '".$statuts_accessibles."';";

/* Transmettre au javascript "initVolets()"
la variable du mois courant */
echo 'var mois = "'.Session::get('ParamEnv.tresorerie.mois_courant').'";';

?>
</script>

<script src="/assets/tresorerie/js/volets.js">
</script>

<script src="/assets/tresorerie/js/incrementeStatuts.js">
</script>

@stop