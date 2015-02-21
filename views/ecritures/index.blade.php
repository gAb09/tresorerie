@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('titrepage')
<h1 class="titrepage">{{$titre_page}}</h1>
@stop


@section('contenu')

<table style="font-size:12px;border:0px">
	<thead>
		@foreach($head as $key => $value)
		<?php
		if ($key == $critere_tri) {
			if ($sens_tri == 'asc') {
				$th_class = 'iconesmall asc tri_selon';
			}else{
				$th_class = 'iconesmall desc tri_selon';
			}
		}else{
			$th_class = '';
		}
		?>
		<th class="{{$th_class}}" id="{{$key}}" onClick="javascript:tri('{{Request::url()}}', {{$key}});">{{$value}}</th>

		@endforeach
		<th class="icone">
			Edit
		</th>
		<th class="icone">
			Dupli
		</th>
		<th class="icone">
			Liée
		</th>
	</thead>

	<tbody>
		@foreach($ecritures as $ecriture)
		<tr id ="{{$ecriture->id}}" class=""
			ondblclick = document.location.href="{{ URL::action('EcritureController@edit', [$ecriture->id]) }}">
			<td>{{ $ecriture->id }}</td>
			<td class ="info">{{ DatesFr::longue($ecriture->date_valeur) }}
				<span>
					Date d’émission : {{ DatesFr::longue($ecriture->date_emission) }}
				</span>
			</td>
			<td>@if($ecriture->type->id == 10)<span class="indefini">{{ $ecriture->type->nom}}</span>@else{{ $ecriture->type->nom}}@endif
				@if($ecriture->justificatif){{$ecriture->type->sep_justif}}{{$ecriture->justificatif}}@endif
			</td>
			<td><b>{{ $ecriture->banque->nom }}</b>
				@if($ecriture->is_double)
				@if($ecriture->signe->signe == -1)
				<br />&rarr; 
				@else
				<br />&larr; 
				@endif
				<small>{{ $ecriture->ecriture2->banque->nom }}</small>
				@endif
			</td>
			<td>{{ $ecriture->libelle }}
				@if($ecriture->libelle_detail)
				— {{ $ecriture->libelle_detail }}
				@endif
			</td>
			<td class="{{ $ecriture->signe->nom_sys }}">{{ NombresFr::francais_insec($ecriture->montant) }}</td>
			<td>{{ $ecriture->compte->numero }}<br />({{ $ecriture->compte->libelle }})</td>
			<td>{{ DatesFr::longue($ecriture->created_at) }}</td>
			<td>{{ DatesFr::longue($ecriture->updated_at) }}</td>
			<td>
				<a class="iconemedium edit" href ="{{ URL::action('EcritureController@edit', [$ecriture->id]) }}"></a>
			</td>
			<td>
				<a class="iconemedium dupli" href ="{{ URL::action('EcritureController@duplicate', [$ecriture->id]) }}"></a>
			</td>
			<td>
				@if ($ecriture->ecriture2)
				<a class="iconemedium double" href ="{{ URL::to('tresorerie/banque/'.$ecriture->ecriture2->banque_id.'#'.$ecriture->ecriture2->id) }}"></a>
				@endif
			</td>

			@endforeach
		</tr>
	</tbody>
</table>

Écritures {{ $ecritures->getFrom() }} à {{ $ecritures->getTo() }} sur un total de {{ $ecritures->getTotal() }}, réparties sur {{ $ecritures->getLastPage() }} pages.
{{ $ecritures->appends(array('critere_tri' => $critere_tri, 'sens_tri' => $sens_tri))->links() }}
Le réglage par défaut est de {{NBRE_PAR_PAGE}} écritures par page.
<br />Vous pouvez temporairement changer cette valeur ici : 

<input id="nbre_par_page" class="court" type="text" value="{{$ecritures->getPerPage()}}" name="nbre_par_page" onChange="javascript:changeParPage('{{Request::url()}}', '{{$critere_tri}}', '{{$sens_tri}}');">


{{ Form::hidden('prev_critere_tri', $critere_tri, array ('class' => 'long', 'id' => 'prev_critere_tri')) }}

{{ Form::hidden('sens_tri', $sens_tri, array ('class' => 'long', 'id' => 'sens_tri')) }}

@stop

@section('actions')
{{link_to_action('EcritureController@create', 'Ajouter une écriture', null, ["class" => "btn btn-success btn-actions iconemedium add"])}}
@stop

@section('affichage')
{{link_to_route('tresorerie.ecritures.index', 'Toutes les écritures', null, ["class" => "badge badge-locale badge-big"])}}
@foreach(Banque::all() as $bank)
{{link_to_route('bank', $bank->nom, $bank->id, ["class" => "badge badge-locale badge-big"])}}
@endforeach
@stop

@section('tresorerie/footer')
@parent
@stop

@section('script')
<script src="/assets/tresorerie/js/ecritures.js">
</script>
@stop
