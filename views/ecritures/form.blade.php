@section('body')
onLoad="bascule_signe();banque();"
@stop

<?php
$class_verrou = (Session::get('class_verrou')) ? Session::get('class_verrou') : "invisible";
?>
<!-- Tableau pour actualisation du séparateur -->

<fieldset>
	<!-- Écriture simple/double -->
	<div class="input nobr">
		{{ Form::checkbox('is_double', '1', $ecriture->is_double, array ('class' => 'nobr', 'id' => 'double', 'onChange' => 'javascript:banque();')) }}
		{{ Form::label('double', 'Écriture double', array ('class' => 'nobr', 'id' => 'label_flag')) }}
	</div>

	@if($ecriture->is_double)
	<div class="input nobr">
	<a class="iconemedium double" href ="{{ URL::action('EcritureController@edit', $ecriture->ecriture2->id) }}"></a>Aller à l’écriture liée
	</div>
	@endif

	<!-- Verrou simple/double -->
	<div class="{{$class_verrou}}">
		{{ Form::checkbox('verrou', '1', '1', array ('class' => 'nobr', 'id' => 'check_verrou', 'onChange' => 'javascript:bascule_verrou();')) }}
		{{ Form::label('verrou', VERROU.' NON VALIDÉ', array ('class' => 'nobr', 'id' => 'verrou', 'style' => 'color:red')) }}
	</div>
</fieldset>
<!-- Banque - Dates - Montant & Signe - Écriture simple/double - Verrou simple/double -->
<fieldset>
	<div class="input">
		<!-- Banque -->
		{{ Form::label('banque_id', 'Banque', array ('id' => 'banque', 'class' => '')) }}
		{{ Form::select('banque_id', $list['banque'], $ecriture->banque_id) }}
	</div>

	<!-- Date émission -->
	<div class="input">
		{{ Form::label('date_emission', 'Date émission', array ('class' => '')) }}
		{{ Form::text('date_emission', DatesFr::formEdit($ecriture->date_emission), array ('class' => 'calendrier')) }}

		<br /><div class="btn btn-date" OnClick="javascript:aujourdhuiEmission();">Aujourd'hui</div>
	</div>

	<!-- Date valeur -->
	<div class="input nobr">
		{{ Form::label('date_valeur', 'Date de valeur', array ('class' => '')) }}
		{{ Form::text('date_valeur', DatesFr::formEdit($ecriture->date_valeur), array ('class' => 'calendrier')) }}

		<br /><div class="btn btn-date" OnClick="javascript:aujourdhuiValeur();">Aujourd'hui</div>
	</div>

	<div class="input">
		<!-- Montant -->
		{{ Form::label('montant', 'Montant', array ('class' => '')) }}
		{{ Form::text('montant', NombresFr::francais($ecriture->montant), array ('class' => '')) }}

		<!-- Signe -->
		@foreach($list_radios as $signes => $signe)
		<br />
		{{ Form::radio('signe_id', $signe['value'], ($signe['id'] == $ecriture->signe_id) ? "checked" : "", array ('class' => '', 'style' => 'vertical-align:inherit;', 'id' => $signe["id_css"], 'onClick' => 'javascript:bascule_signe();'))}}
		{{ Form::label($signe["id_css"], $signe['etiquette'], array ('class' => 'nobr','style' => '', 'id' => '')) }}
		@endforeach
	</div>
</fieldset>

<!-- Libellés -->
<fieldset>
	<div class="input">
		<!-- Libellé -->
		{{ Form::label('Libelle', 'Libellé', array ('class' => '')) }}
		{{ Form::text('libelle', $ecriture->libelle, array ('class' => 'input-long')) }}
	</div>

	<div class="input">
		<!-- Libellé détail -->
		{{ Form::label('libelle_detail', 'Libellé détail', array ('class' => '')) }}
		{{ Form::text('libelle_detail', $ecriture->libelle_detail, array ('class' => 'input-long margright')) }}
	</div>
</fieldset>

<!-- Type - justificatif-->
<fieldset>
	<div class="input">
		<!-- Type -->
		{{ Form::label('type_id1', 'Type', array ('class' => '')) }}
		{{Form::select('type_id1', $list['type'], $ecriture->type_id, array ('class' => 'input-long', 'onChange' => 'javascript:separateur(this);') ) }}
	</div>

@if(isset($ecriture->type->req_justif) and $ecriture->type->req_justif)
		<div id="divjustificatif1" class="input">
	@else
		<div id="divjustificatif1" class="input hidden">
	@endif
		<!-- Type (justificatif) -->
		{{ Form::label('justificatif', 'Justificatif', array ('class' => '')) }}
		<span id="sep1">
			{{ isset($ecriture->type->sep_justif) ? $ecriture->type->sep_justif : '' }}
		</span>
		{{ Form::text('justificatif', isset($ecriture->justificatif) ? $ecriture->justificatif : '',  array ('class' => 'input-long margright')) }}   <!-- aPo probleme de selected -->
		<!-- Type (justificatif requis) -->
		{{ Form::hidden('req_justif',  isset($ecriture->type->req_justif) ? $ecriture->justificatif : '', array ('class' => 'input-long margright', 'id' => 'req_justif1')) }}   <!-- aPo probleme de selected -->
	</div>
</fieldset>






<!-- Compte -->
<fieldset>
	<div class="input">
		{{ Form::label('compte_id', 'Compte', array ('class' => '', 'id' => 'compte_id')) }}
		{{Form::select('compte_id', $list['compte'], $ecriture->type_id, array ('class' => 'input-long nobr', 'id' => 'compte_id_actif')) }}

		<input id="desactive_compte" value="Désactiver ce compte" type="button" class="invisible" 
		onclick = "modificationCompte('{{URL::action('CompteController@updateActif')}}', 0 )">
		<span id="span_compte_activation" class="invisible"> Attention : après désactivation bien penser à réattribuer un compte.</span>

	</div>
</fieldset>

<fieldset>
		{{ Form::label('compte_activation', 'Activer/Désactiver un compte. 
		', array ('class' => '', 'id' => 'compte', 'onClick' => 'javascript:bascule_compte(this)')) }}

	<div id="div_compte_activation" class="invisible">
		Activer un compte l’ajoutera à la liste de sélection ci-dessus.

		<br />{{Form::select('compte_activation', $list['compte_activation'], '0', array ('class' => 'input-long', 'id' => 'compte_activation')) }}

		<input value="Activer ce compte" type="button" class="btn btn-small btn-success"
		onclick = "modificationCompte('{{URL::action('CompteController@updateActif')}}', 1 )">
	</div>
</fieldset>

<!-- Banque 2 -->
<fieldset id="ecriture2" >
	<legend class="input">
		Écriture liée
	</legend>
	<div class="input">
		<!-- Banque 2 -->
		{{ Form::hidden('ecriture2_id', isset($ecriture->ecriture2->id) ? $ecriture->ecriture2->id : '') }}
		{{ Form::label('banque2_id', 'Banque liée', array ('class' => '', 'id' => 'banque2_label')) }}
		{{ Form::select('banque2_id', $list['banque'], isset($ecriture->ecriture2->banque_id) ? $ecriture->ecriture2->banque_id : 0, array ('target' => 'blank'))}}
	</div>
	<div class="input">
		<!-- Type 2 -->
		{{ Form::label('type_id2', 'Type', array ('class' => '')) }}

		{{Form::select('type_id2', $list['type'], isset($ecriture->ecriture2->type_id) ? $ecriture->ecriture2->type_id : 0, array ('class' => 'input-long', 'onChange' => 'javascript:separateur(this);') ) }}
	</div>

@if(isset($ecriture->ecriture2->type->req_justif) and $ecriture->ecriture2->type->req_justif)
		<div id="divjustificatif2" class="input">
	@else
		<div id="divjustificatif2" class="input hidden">
	@endif
		<!-- Type (justificatif) -->
		{{ Form::label('justif2', 'Justificatif', array ('class' => '')) }}
		<span id="sep2">
			{{isset($ecriture->ecriture2->type->sep_justif) ? $ecriture->ecriture2->type->sep_justif :  ''}}
		</span>
		{{ Form::text('justif2', isset($ecriture->ecriture2->justificatif) ? $ecriture->ecriture2->justificatif : '', array ('class' => 'input-long margright')) }} 
		<!-- Type (justificatif requis) -->
		{{ Form::hidden('req_justif2', isset($ecriture->ecriture2->type->req_justif) ? $ecriture->ecriture2->justificatif : '', array ('class' => 'input-long margright', 'id' => 'req_justif2')) }}   <!-- aPo probleme de selected -->
	</div>
</fieldset>

@section('script')
<script src="/assets/tresorerie/js/ecritures.js">
</script>

<script type="text/javascript">

<?php 
echo "var seps = {};";
echo "var req_justif = {};";

foreach($types as $i) {
	echo "
	seps['$i->id'] = '$i->sep_justif';
	req_justif['$i->id'] = '$i->req_justif';
	";
}
?>

var txt_label = "{{VERROU}}";

</script>

@stop

