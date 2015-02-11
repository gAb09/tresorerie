@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop

@section('assets')
@parent
	<link href="/assets/tresorerie/css/journal.css" rel="stylesheet" type="text/css">
@stop

@section('body')
onLoad="initVolets();"
@stop


@section('topcontent1')
<h1 class="titrepage">{{ $titre_page }}</h1>
@stop



@section('contenu')

@foreach($ecritures as $ecriture)

@if($ecriture->mois_nouveau)

<table class="modes">
	<caption class="ligne_mois" id="{{$ecriture->mois_classement}}" onclick="javascript:volet(this);">
		{{ ucfirst(DatesFr::MoisAnneeInsec($ecriture->date_emission)) }}
	</caption>

	<thead class="replie" id="tetiere{{$ecriture->mois_classement}}" >
		<th class="statut">
			Statut
		</th>
		<th class="date">
			Émission
		</th>
		<th class="libelle">
			Libellé
		</th>
		<th class="montant">
			Dépenses
		</th>
		<th class="montant padding">
			Recettes
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
		<th class="icone iconemedium dupli">
			
		</th>
		<th class="icone iconemedium double">
			
		</th>
	</thead>

	<tbody class="replie" id="corps{{$ecriture->mois_classement}}">

		@endif
		
		@include('tresorerie/views/journal/row')

		@if($ecriture->last)
		<tr class="soldes">
			<td colspan="3">
			</td>
			<td class ='depense'>
				{{NombresFr::francais_insec($ecriture->cumul_dep_mois)}}
			</td>
			<td class='recette padding'>
				{{NombresFr::francais_insec($ecriture->cumul_rec_mois)}}
			</td>
			<td colspan="4">
				Solde du mois : 
				@if($ecriture->solde < 0)
				<span class="depense">{{NombresFr::francais_insec($ecriture->solde)}}</span>
				@else
				<span class="recette">{{NombresFr::francais_insec($ecriture->solde)}}</span>
				@endif
			</td>
		</tr>
		@endif

		@endforeach

	</tbody>

</table>

@stop


@section('actions')

{{link_to_action('EcritureController@create', 'Ajouter une écriture', null, ["class" => "btn btn-success btn-actions iconemedium add"])}}

@stop

@section('topcontent2')
<div class="banques">
	@foreach(Banque::all() as $bank)
	
	<a href ="{{ URL::to("tresorerie/journal/$bank->id") }}" class="badge badge-locale badge-big {{ ($bank->nom == Session::get('Courant.banque')) ? 'badge-success' : ''}}">{{ $bank->nom }}</a>
	@endforeach
</div>
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
echo 'var mois = "'.Session::get('Courant.mois').'";';

?>
</script>

<script src="/assets/tresorerie/js/volets.js">
</script>

<script src="/assets/tresorerie/js/incrementeStatuts.js">
</script>

@stop