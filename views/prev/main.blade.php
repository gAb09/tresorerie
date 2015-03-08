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
<h1>{{ $titre_page }}</h1>
@stop


@section('contenu')

@foreach($ecritures as $ecriture)

@if($ecriture->mois_nouveau)

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

		@if($ecriture->last)
		<tr class="soldes">
			<td colspan="5" style="text-align:right">
				Situation à fin {{mb_strtolower(DatesFr::MoisAnneeInsec($ecriture->date_valeur))}}
			</td>
			@foreach($banques as $banque)
			<?php $id = 'solde_'.$banque->id; ?>
				<td class="{{($ecriture->{$id} >=0) ? 'recette' : 'depense' }}">
					{{ NombresFr::francais_insec($ecriture->{$id}) }}
				</td>
			@endforeach
				<td class="{{($ecriture->solde_total >=0) ? 'recette' : 'depense' }}">
					{{ NombresFr::francais_insec($ecriture->solde_total) }}
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


@section('actions')
@if(Auth::user()->role_id == 1)
{{link_to_action('EcritureController@create', 'Ajouter une écriture', null, ["class" => "btn btn-success btn-actions iconemedium add"])}}
@else
<table style="background-color:#EDDCC1">
<caption  style="color:#FFF">
Légende
</caption>

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
</td>
</tr>

</table>
@endif
@stop


@section('actions')

{{link_to_action('EcritureController@create', 'Ajouter une écriture', null, ["class" => "btn btn-success btn-actions iconemedium add"])}}

@stop


@section('affichage')
<div class="span6">
	<a href ="{{ URL::to("tresorerie/previsionnel/2013") }}" 
		class="badge badge-locale badge-big 
		{{ (Session::get('ParamEnv.tresorerie.annee_courante') == '2013') ? 'badge-success' : ''}} " >
		2013
	</a>
	<a href ="{{ URL::to("tresorerie/previsionnel/2014") }}" 
		class="badge badge-locale badge-big 
		{{ (Session::get('ParamEnv.tresorerie.annee_courante') == '2014') ? 'badge-success' : ''}} " >
		2014
	</a>
	<a href ="{{ URL::to("tresorerie/previsionnel/2015") }}" 
		class="badge badge-locale badge-big 
		{{ (Session::get('ParamEnv.tresorerie.annee_courante') == '2015') ? 'badge-success' : ''}} " >
		2015
	</a>
	<a href ="{{ URL::to("tresorerie/previsionnel/2016") }}" 
		class="badge badge-locale badge-big 
		{{ (Session::get('ParamEnv.tresorerie.annee_courante') == '2016') ? 'badge-success' : ''}} " >
		2016
	</a>
</div>

<div class="span6">
	<span>Banque de référence</span>
	@foreach(Banque::all() as $bank)
	<p class="label label-locale label-medium {{ ($bank->rang == 1) ? 'btn-success' : ''}}"
		onClick="javascript:alert('Le changement de banque de référence sera disponible dans la prochaine version');" >
		{{ $bank->nom }}
	</p>
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
echo 'var mois = "'.Session::get('ParamEnv.tresorerie.mois_courant').'";';

?>
</script>

<script src="/assets/tresorerie/js/volets.js">
</script>

<script src="/assets/tresorerie/js/incrementeStatuts.js">
</script>

@stop