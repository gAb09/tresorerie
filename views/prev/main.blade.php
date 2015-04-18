@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop

@section('assets')
@parent
<link href="/assets/tresorerie/css/prev.css" rel="stylesheet" type="text/css">
@stop

@section('body')
onLoad="initVolets();"
@stop


@section('titrepage')
<h1>{{ $titre_page }} {{ Session::get('tresorerie.exercice_travail') }}</h1>
@stop


@section('contenu')

@foreach($ecritures as $ecriture)

@if($ecriture->nouveau_mois or $ecriture->premier_mois)

<table class="modes">
	<caption class="ligne_mois" id="{{$ecriture->mois_classement}}" onclick="javascript:volet(this);">
		{{ DatesFr::MoisAnneeInsec($ecriture->date_valeur) }}
	</caption>

	<thead class="replie" id="tetiere{{$ecriture->mois_classement}}">
		<th class="statut">
			Statut
		</th>
		<th class="date">
			Date de valeur
		</th>
		<th class="type">
			Type
		</th>
		<th class="libelle">
			Libellé
		</th>
		<th class="montant">
			Montant
		</th>
		@foreach($banques as $banque)		
		<th  class="montant">
			{{$banque->nom}}
		</th>
		@endforeach
		<th class="montant">
			Global
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

		@include('tresorerie/views/prev/row')

		@if($ecriture->fin_page or $ecriture->der_du_mois)
		<tr class="cumuls">
			<td colspan="5" style="text-align:right">
				Situation à fin {{mb_strtolower(DatesFr::MoisAnneeInsec($ecriture->date_valeur))}}
			</td>
			@foreach($banques as $banque)
			<?php $banque_cumul =  $banque->id; ?>
			<td class="{{($ecriture->{$banque_cumul} >=0) ? 'recette' : 'depense' }}">
				{{ NombresFr::francais_insec($ecriture->{$banque_cumul}) }}
			</td>
			@endforeach
			<td class="{{($ecriture->global >=0) ? 'recette' : 'depense' }}">
				{{ NombresFr::francais_insec($ecriture->global) }}
			</td>
		</tr>
		@endif
		@endforeach

	</tbody>

</table>
<br />
@stop




@section('footer')

@parent

<h3>  Le footer de recettes_depenses</h3>

@stop


@if(Auth::user()->role_id == 1)

@section('topfoot1')
<span>Actions</span>
{{link_to_action('EcritureController@create', 'Ajouter une écriture', null, ["class" => "btn btn-success btn-actions iconemedium add"])}}
@stop

@else

@section('topfoot1')

<span>Légende</span>
<table style="background-color:#EDDCC1">
	<tr class="st_prev">
		<td class="st_prev" style="width:30px">
		</td>
		<td>
			Prévisionnelle : écriture encore "virtuelle" - Dates d’émission et de valeur inconnues.
		</td>
	</tr>

	<tr class="st_emise">
		<td class="st_emise style="width:30px"">
		</td>
		<td>
			Émise : écriture réellement émise - Date d'émision connue, date de valeur imprécise.</td>
		</td>
	</tr>

	<tr class="st_www">
		<td class="st_www" style="width:30px">
		</td>
		<td>
			Pointée www : écriture pointée par rapport au site Caisse d’Épargne - Date de valeur connue.
		</td>
	</tr>

	<tr class="">
		<td class="" style="width:30px">
		</td>
		<td>
			Pointée : écriture pointée par rapport aux relevés bancaires.
			<p></p>
		</td>
	</tr>

</table>
@endif
@stop

@section('topfoot3')
<span>Exercice affiché : </span>
@foreach($exercices_clotured as $exercices)
<a href ="{{ URL::to("tresorerie/previsionnel/".$exercices) }}" 
class="badge badge-locale
{{ (Session::get('tresorerie.exercice_travail') == $exercices) ? 'badge-success' : ''}} " >
{{$exercices}}
</a>
@endforeach
<a href ="{{ URL::to("tresorerie/previsionnel/".$exercice) }}" 
class="badge badge-locale 
{{ ($exercice == Session::get('tresorerie.exercice_travail')) ? 'badge-success' : ''}} " >
{{ $exercice }} et suivantes
</a>


<span>Priorités des banques</span>
@foreach(Banque::all() as $bank)
<a class="label label-locale label-medium {{ ($bank->priorite == 1) ? 'btn-success' : ''}}" 
	href ="{{ URL::to("tresorerie/previsionnel/priorite/".$bank->id) }}" >
	{{ $bank->nom }}
</a>
@endforeach


@stop


@section('script')

<script type="text/javascript">	
<?php

/* Transmettre au javascript "incrementeStatuts()" 
le tableau de correspondance classe/id pour les statuts */
echo "var classe_statut = ".$classe_statut.";";
echo "var statuts_autorised = '".$statuts_autorised."';";

/* Transmettre au javascript "initVolets()"
la variable du mois de travail */
echo 'var mois = "'.Session::get('tresorerie.mois_travail').'";';

?>
</script>

<script src="/assets/tresorerie/js/volets.js">
</script>

<script src="/assets/tresorerie/js/incrementeStatuts.js">
</script>

@stop