		<tr 
		id="row_{{ $ecriture->id }}" 
		class="{{$ecriture->statut->classe.' '.$ecriture->index_ligne}}" 
		ondblclick = document.location.href="{{ URL::action('EcritureController@edit', [$ecriture->id]) }}">

		<!-- Statut -->
		<td 
		id="statut_td_{{ $ecriture->id }}"
		class="statut {{$ecriture->statut->classe}}">
		
			@if (strpos($statuts_autorised, (string)$ecriture->statut->rang) !== false)
			{{ Form::open(array('name' => 'incrementeStatut', 'route' => ['incrementeStatut', $ecriture->id, $statuts_autorised], 'method' => 'post', 'class' => 'pointage')) }}

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
		<td id="valeur{{ $ecriture->id }}" class="info date">
			{{ DatesFr::longue($ecriture->date_valeur) }}
			<span>
				Date d’émission : {{ DatesFr::longue($ecriture->date_emission) }}
			</span>
		</td>

		<td class="type">
			@if($ecriture->type->id == 10)<span class="indefini">{{ $ecriture->type->nom}}</span>@else{{ $ecriture->type->nom}}@endif
			@if($ecriture->type->statut_justif === 1)
			{{ $ecriture->type->sep_justif }}
			{{ $ecriture->justificatif }}
			@endif
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

<!-- Montants -->
		<td class="montant {{$ecriture->signe->nom_sys}}">
			{{ NombresFr::francais_insec($ecriture->montant) }}
		</td>

		@foreach($banques as $banque)
		<?php $banque_cumul =  $banque->id; ?>
		<?php $show = 'show_'.$banque->id; ?>

		<td class="{{($ecriture->{$banque_cumul} >=0) ? 'montant recette' : 'montant depense' }}">
			@if($ecriture->$show)
			{{ NombresFr::francais_insec($ecriture->{$banque_cumul}) }}
			@endif
		</td>

		@endforeach

		<td class=" montant {{($ecriture->global >=0) ? 'recette' : 'depense' }}">
			{{NombresFr::francais_insec($ecriture->global)}}
		</td>

		<td class="icone">
			<a class="iconemedium edit" href ="{{ URL::action('EcritureController@edit', [$ecriture->id]) }}"></a>
		</td>

		<td class="icone">
			<a class="iconemedium dupli" href ="{{ URL::action('EcritureController@duplicate', [$ecriture->id]) }}"></a>
		</td>

		<td class="icone">
			@if ($ecriture->ecriture2)
			<a class="iconemedium double" href =""></a>
			@endif
		</td>

	</tr>
