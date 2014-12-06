@section('body')
onLoad="togle_actif();getFreres();"
@stop

<!-- liste d'inputs commune au vues CREATE et EDIT -->

{{ Form::hidden('pco', $compte->pco) }}
{{ Form::hidden('thisid', $compte->id) }}

@if($compte->pco)
{{ Form::hidden('numero', $compte->numero) }}
{{ Form::hidden('libelle', $compte->libelle) }}
{{ Form::hidden('pere', $compte->parent_id) }}
{{ Form::hidden('position', null) }}

@else
<div>
<div style="display:inline-block">
	<!-- Numéro -->
	{{ Form::label('numero', 'Numéro', array ('class' => '')) }}
	{{ Form::text('numero', $compte->numero, array ('class' => '', 'style' => 'width:100px;margin-right:10px')) }}
</div>

<div style="display:inline-block">
	<!-- Libellé -->
	{{ Form::label('libelle', 'Libellé', array ('class' => '')) }}
	{{ Form::text('libelle', $compte->libelle, array ('class' => '', 'style' => 'width:800px')) }}
</div>
	<hr class="filetfin">
</div>


<div style="display:inline-block">
	<!-- Pere -->
	{{ Form::label('pere', 'Compte parent', array ('class' => '')) }}
	{{ Form::select('pere', $parents, $compte->parent_id, array ('class' => 'nobr', 'onChange' => 'javascript:getFreres();')) }}
</div>
<div id="div_position" class="{{$position_class}}" style="display:inline-block;margin-left:20px">
	<!-- Position -->
	{{ Form::label('position', 'Position', array ('class' => '', 'style' => 'width:80px')) }}
	{{ Form::select('position', $parents, array ('class' => '')) }}
	<div class="aide">
		<span id ="span_pere"></span>Le compte parent sélectionné comporte déjà des sous-comptes. Par défaut, le compte en cours d'édition sera placé en dernier.
		<br />Si vous voulez décider de sa position, utilisez la liste ci-contre (il sera alors placé AVANT l'item sélectionné).<br /></div>
	</div>
	<hr class="filetfin">
	@endif

	<div>
		<!-- Compte actif -->
		{{ Form::checkbox('actif', 1, $compte->actif, array ('class' => 'nobr', 'id' => 'actif_check', 'onClick' => 'javascript:togle_actif()')) }}
		{{ Form::label('actif_check', '', array ('class' => 'nobr', 'id' => 'actif_label')) }}
		<hr class="filetfin"/>
	</div>

	<div>
		<!-- Descrition officelle -->
		@if($compte->pco and $compte->description_officiel)
		<h5 class="pco">Description officielle : </h5>
		<p class="pco">{{$compte->description_officiel}}</p>
		@endif
	</div>

	<div style="display:inline-block">
		<!-- Descrition complémentaire -->
		{{ Form::label('description_comp', 'Description complémentaire', array ('class' => '')) }}
		{{ Form::textarea('description_comp', $compte->description_comp, array ('class' => '', 'style' => 'width:450px')) }}
	</div>

	<div style="display:inline-block">
		@if(!$compte->pco)
		<!-- Descrition lmh (La Mauvaise Herbe) -->
		{{ Form::label('description_lmh', 'Description maison', array ('class' => '')) }}
		{{ Form::textarea('description_lmh', $compte->description_lmh, array ('class' => '', 'style' => 'width:450px')) }}
		@endif
	</div>

	@section('script')
		<script src="/assets/js/comptes.js">
	</script>
	@stop