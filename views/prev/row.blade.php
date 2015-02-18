		<tr id ="{{$ecriture->id}}" class="{{$ecriture->statut->classe}}" 
			ondblclick = document.location.href="{{ URL::action('EcritureController@edit', [$ecriture->id]) }}">

			<!-- Statuts -->
		<td
			id="statut_td_{{ $ecriture->id }}"
			class="statut {{$ecriture->statut->classe}}">
		</td>

		<!-- Dates -->
		<td id="valeur{{ $ecriture->id }}" class="info date">
			{{ DatesFr::longue($ecriture->date_valeur) }}
			<span>
				Date d’émission : {{ DatesFr::longue($ecriture->date_emission) }}
			</span>
		</td>

		<td class="type">
			@if($ecriture->type->id == 10)<span class="depense">{{ $ecriture->type->nom}}</span>@else{{ $ecriture->type->nom}}@endif
			@if($ecriture->justificatif)
			{{ $ecriture->type->sep_justif }}
			@endif
			{{ $ecriture->justificatif }}
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

		<td class="montant {{$ecriture->signe->nom_sys}}">
			{{ NombresFr::francais_insec($ecriture->montant) }}
		</td>

		@foreach($banques as $banque)
		<?php $id = 'solde_'.$banque->id; ?>
		<?php $show = 'show_'.$banque->id; ?>

		<td class="{{($ecriture->{$id} >=0) ? 'montant recette' : 'montant depense' }}">
			@if($ecriture->{$show})
			{{ NombresFr::francais_insec($ecriture->{$id}) }}
			@endif
		</td>

		@endforeach

		<td class=" montant {{($ecriture->solde_total >=0) ? 'recette' : 'depense' }}">
			{{NombresFr::francais_insec($ecriture->solde_total)}}
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
