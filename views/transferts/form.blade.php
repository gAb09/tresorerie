@section('body')
onLoad="bascule_signe();banque();"
@stop

<?php
$class_verrou = (Session::get('class_verrou')) ? Session::get('class_verrou') : "invisible";
?>


<!-- Banque - Dates - Montant & Signe - Écriture simple/double - Verrou simple/double -->
<fieldset>
	<div class="input">
		<!-- Banque -->
		{{ Form::label('banque_id', 'Banque', array ('id' => 'banque', 'class' => '')) }}
		{{ Form::select('banque_id', $list['banque'], $transfert->banque_id) }}
	</div>

	<!-- Date émission -->
	<div class="input">
		{{ Form::label('date_emission', 'Date émission', array ('class' => '')) }}
		{{ Form::text('date_emission', DatesFr::formEdit($transfert->date_emission), array ('class' => 'calendrier')) }}

		<br /><div class="btn btn-date" OnClick="javascript:aujourdhuiEmission();">Aujourd'hui</div>
	</div>

	<!-- Date valeur -->
	<div class="input nobr">
		{{ Form::label('date_valeur', 'Date de valeur', array ('class' => '')) }}
		{{ Form::text('date_valeur', DatesFr::formEdit($transfert->date_valeur), array ('class' => 'calendrier')) }}

		<br /><div class="btn btn-date" OnClick="javascript:aujourdhuiValeur();">Aujourd'hui</div>
	</div>

	<div class="input">
		<!-- Montant -->
		{{ Form::label('montant', 'Montant', array ('class' => '')) }}
		{{ Form::text('montant', NombresFr::francais($transfert->montant), array ('class' => '')) }}

		<!-- Signe -->
		@foreach($list_radios as $signes => $signe)
		<br />
		{{ Form::radio('signe_id', $signe['value'], ($signe['id'] == $transfert->signe_id) ? "checked" : "", array ('class' => '', 'style' => 'vertical-align:inherit;', 'id' => $signe["id_css"], 'onClick' => 'javascript:bascule_signe();'))}}
		{{ Form::label($signe["id_css"], $signe['etiquette'], array ('class' => 'nobr','style' => '', 'id' => '')) }}
		@endforeach
	</div>
</fieldset>

<!-- Libellés -->
<fieldset>
	<div class="input">
		<!-- Libellé -->
		{{ Form::label('Libelle', 'Libellé', array ('class' => '')) }}
		{{ Form::text('libelle', $transfert->libelle, array ('class' => 'input-long')) }}
	</div>

	<div class="input">
		<!-- Libellé détail -->
		{{ Form::label('libelle_detail', 'Libellé détail', array ('class' => '')) }}
		{{ Form::text('libelle_detail', $transfert->libelle_detail, array ('class' => 'input-long margright')) }}
	</div>
</fieldset>

<!-- Type - justificatif-->
<fieldset>
	<div class="input">
		<!-- Type -->
		{{ Form::label('type_id1', 'Type', array ('name' => 'label')) }}
		{{Form::select('type_id1', $list['type'], $transfert->type_id, array ('class' => 'input-long', 'onChange' => 'javascript:toggleJustif(this);') ) }}
	</div>

		<!-- Type (justificatif) -->
	@if(isset($transfert->type->statut_justif) and $transfert->type->statut_justif == 1)
		<div id="divjustificatif1" class="input">
		{{ Form::label('justificatif1', 'Justificatif requis', array ('class' => '')) }}
	@else
		<div id="divjustificatif1" class="input locked">
		{{ Form::label('justificatif1', 'Justificatif non requis', array ('class' => '')) }}
	@endif
		<span id="sep1">
			{{ isset($transfert->type->sep_justif) ? $transfert->type->sep_justif : '' }}
		</span>
		{{ Form::text('justificatif1', isset($transfert->justificatif) ? $transfert->justificatif : '',  array ('class' => 'input-long margright')) }}

		<!-- Type (justificatif requis) Utilisé pour la validation -->
		{{ Form::hidden('statut_justif1',  isset($transfert->type->statut_justif) ? $transfert->type->statut_justif : '', array ('class' => 'input-long margright', 'id' => 'statut_justif1')) }}
	</div>
</fieldset>



<!-- Compte -->
<fieldset>
	<div class="input">
		{{ Form::label('compte_id', 'Compte', array ('class' => '', 'id' => 'compte_id')) }}
		{{Form::select('compte_id', $list['compte'], $transfert->compte_id, array ('class' => 'input-long nobr', 'id' => 'compte_id_actif')) }}

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


<!--  NOTES -->
<fieldset>
		{{ Form::label('note', 'Notes', array ('class' => '', 'id' => 'note_label'))}}

		<br />{{Form::textarea('note', $transfert->note, array ('class' => '', 'id' => 'note')) }}
</fieldset>


@section('script')
<script src="/assets/tresorerie/js/ecritures.js">
</script>

<script type="text/javascript">

<?php 
echo "var separateurs = {};";
echo "var statut_justif = {};";

foreach($types as $i) {
	echo "
	separateurs['$i->id'] = '$i->sep_justif';
	statut_justif['$i->id'] = '$i->statut_justif';
	";
}
?>

var txt_label = "{{VERROU}}";

</script>

<script>
CKEDITOR.replace( 'note', {
	language: 'en',
	uiColor: '#EDDCC1',
});
</script>

@stop

