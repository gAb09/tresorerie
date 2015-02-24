<tr 
id="row_{{ $ecriture->id }}" 
class="{{$ecriture->statut->classe}}" 
ondblclick = document.location.href="{{ URL::action('EcritureController@edit', [$ecriture->id]) }}">

	<!-- Statut -->
	<td 
	id="statut_td_{{ $ecriture->id }}"
	class="statut {{$ecriture->statut->classe}}">
	
		@if (strpos($statuts_accessibles, (string)$ecriture->statut->rang) !== false)
		{{ Form::open(array('name' => 'pointage', 'action' => ['PointageController@incrementeStatut', $ecriture->id, $statuts_accessibles], 'method' => 'post', 'class' => 'pointage')) }}

		{{ Form::hidden('input_id', $ecriture->statut->id, array('id' => "input_$ecriture->id", 'class' => '')) }}

		{{ Form::button('', array(
		'class' => 'btn btn-link iconemedium toggle', 
		'id' => "btn_$ecriture->id", 
		'style' => '', 
		'OnClick' => 'bascule_statut(this);submit();' 
		)) }}

		{{ form::close() }}
		@endif
	</td>

	<!-- Dates -->
	<td id="valeur{{ $ecriture->id }}" class="date info">
		{{ DatesFr::longue($ecriture->date_valeur) }}
		<span>
			Date d’émission : {{ DatesFr::longue($ecriture->date_emission) }}
		</span>

	</td>


<!-- Libellé -->
	@if($ecriture->note)
	<td class="libelle {{$ecriture->presence_note}}">
		@else
		<td class="libelle">
			@endif
			{{ $ecriture->libelle }}
			@if($ecriture->libelle_detail)
			— 
			{{ $ecriture->libelle_detail }}
			@endif
			@if($ecriture->note)
			<span class="left">
				{{ $ecriture->note }}
			</span>
			@endif
		</td>


	<!-- Montant -->
	<td class="montant {{$ecriture->signe->nom_sys}}">

		@if($ecriture->signe_id == 1)
		{{ NombresFr::francais_insec($ecriture->montant) }}
		@endif
	</td>

	<td class="montant {{$ecriture->signe->nom_sys}}">

		@if($ecriture->signe_id == 2)
		{{ NombresFr::francais_insec($ecriture->montant) }}
		@endif
	</td>


	<!-- Solde -->
	<td class="montant cumul">
		@if($ecriture->cumul < 0)
		<span class="depense">{{NombresFr::francais_insec($ecriture->cumul)}}</span>
		@else
		<span class="recette">{{NombresFr::francais_insec($ecriture->cumul)}}</span>
		@endif
	</td>

	<!-- Type -->
	<td class="type">
		@if($ecriture->type->id == 10)<span class="indefini">{{ $ecriture->type->nom}}</span>@else{{ $ecriture->type->nom}}@endif
		@if($ecriture->type->statut_justif === 1)
		{{ $ecriture->type->sep_justif }}
		{{ $ecriture->justificatif }}
		@endif
	</td>


	<!-- Banque -->
	<td class="banque">
		{{ $ecriture->banque->nom }}
		@if($ecriture->is_double)
		@if($ecriture->signe->signe == -1)
		<br />&rarr; 
		@else
		<br />&larr; 
		@endif
		<small>{{ $ecriture->ecriture2->banque->nom }}</small>
		@endif
	</td>

	<!-- Compte -->
	<td class="{{ $ecriture->class_compte }}">
		{{ $ecriture->compte->libelle }}
	</td>


	<!-- Edit -->
		<td class="icone">
			<a class="iconemedium edit" href ="{{ URL::action('EcritureController@edit', [$ecriture->id]) }}"></a>
		</td>

</tr>
