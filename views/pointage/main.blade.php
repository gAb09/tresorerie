@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop

@section('body')
onLoad="initVolets();"
@stop


@section('topcontent1')
<h1 class="titrepage">{{ $titre_page }}</h1>
@stop


@section('topcontent2')
<div class="banques">
	@foreach(Banque::all() as $bank)
<a href ="{{ URL::route('pointage', $bank->id) }}" class="badge badge-locale badge-big {{ ($bank->nom == Session::get('Courant.banque')) ? 'badge-success' : ''}}">{{ $bank->nom }}</a>
	@endforeach
</div>

@stop


@section('contenu')

@foreach($ecritures as $ecriture)

@if($ecriture->mois_nouveau)

<table>
	<caption class="ligne_mois" id="{{$ecriture->mois_classement}}" onclick="javascript:volet(this);">
		{{ ucfirst(DatesFr::MoisAnneeInsec($ecriture->date_valeur)) }}
	</caption>

	<thead class="replie" id="tetiere{{$ecriture->mois_classement}}" >
		<th style="width:10px">
			Statut
		</th>
		<th>
			Date de valeur
		</th>
		<th>
			Libellé
		</th>
		<th>
			Dépenses
		</th>
		<th>
			Recettes
		</th>
		<th>
			Solde
		</th>
		<th>
			Type
		</th>
		<th>
			Banque(s)
		</th>
		<th>
			Compte
		</th>
		<th>
			
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
				{{NombresFr::francais_insec($ecriture->cumul_dep_mois)}}
			</td>
			<td class='recette'>
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
echo 'var mois = "'.Session::get('Courant.mois').'";';

?>
</script>

<script src="/assets/js/volets.js">
</script>

<script src="/assets/js/incrementeStatuts.js">
</script>

@stop