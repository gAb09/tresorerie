@section('body')
onLoad="toggleJustif();"
@stop

<!-- liste d'inputs commune au vues CREATE et EDIT -->
<div>
	<!-- Nom -->
	{{ Form::label('nom', 'Nom', array ('class' => '')) }}
	{{ Form::text('nom', $type->nom, array ('class' => '')) }}

	<!-- Rang -->
	{{ Form::label('rang', 'Rang', array ('class' => 'nobr')) }}
	{{ Form::text('rang', $type->rang, array ('class' => '')) }}
</div>

<div>
	<!-- Description -->
	{{ Form::label('description', 'Description', array ('class' => '')) }}
	{{ Form::textarea('description', $type->description, array ('class' => '')) }}
	Pour obtenir un retour ligne saisir les caractères suivants : {{'&lt;br /&gt;'}}
</div>
<hr />


<h5>Champ “Justificatif”</h5>
<div>
	<!-- "Justificatif" requis -->
		{{ Form::checkbox('statut_justif', 1, $type->statut_justif, array ('class' => 'nobr', 'id' => 'justif_check', 'onClick' => 'javascript:toggleJustif()')) }}
		{{ Form::label('justif_check', 'non requis', array ('class' => 'nobr', 'id' => 'justif_label')) }}
</div>

<div id="statut_justif_div">
	<!-- Separateur -->
	{{ Form::label('sep_justif', 'Séparateur', array ('class' => 'nobr')) }}
	{{ Form::text('sep_justif', trim($type->sep_justif), array ('class' => '')) }}
	Choisir le(s) caractère(s) ou le texte de séparation. Cela séparera “type” et “justificatif” dans les différentes listes et vues
</div>

@section('script')
<script src="/assets/tresorerie/js/types.js">
</script>
@stop