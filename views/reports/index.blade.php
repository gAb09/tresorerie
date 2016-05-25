@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('titrepage')
<h1>{{$titre_page}}</h1>
@stop


@section('contenu')

<table style="font-size:12px;border:0px">
	<thead>
		@foreach($head as $colonne => $entete)
		<th class="" id="{{$colonne}}" onClick="javascript:tri('{{Request::url()}}', {{$colonne}});">
			{{$entete}}
		</th>

		@endforeach
	</thead>

	<tbody>
		@foreach($ecritures as $ecriture)
		<tr id ="{{$ecriture->id}}" class="{{$ecriture->classe}}"
			ondblclick = "basculeReportable(this, '{{ URL::action('ReportController@setReportable', [$ecriture->id]) }}');">
			<td>{{ $ecriture->id }}</td>
			<td>{{ $ecriture->banque->nom }}
			</td>
			<td class ="info">{{ DatesFr::longue($ecriture->date_valeur) }}
				<span>
					Date d’émission : {{ DatesFr::longue($ecriture->date_emission) }}
				</span>
			</td>
			<td>{{ $ecriture->libelle }}
				@if($ecriture->libelle_detail)
				— {{ $ecriture->libelle_detail }}
				@endif
			</td>
			<td>{{ $ecriture->type->nom}}
				@if($ecriture->justificatif){{$ecriture->type->sep_justif}}{{$ecriture->justificatif}}@endif
			</td>
			<td class="{{ $ecriture->signe->nom_sys }}">{{ NombresFr::francais_insec($ecriture->montant) }}</td>
			<td>{{ DatesFr::longue($ecriture->created_at) }}</td>
			<td>{{ DatesFr::longue($ecriture->updated_at) }}</td>

			@endforeach
		</tr>
	</tbody>
</table>

@stop


@section('tresorerie/footer')
@parent
@stop

@section('script')
<script src="/assets/tresorerie/js/reports.js">
</script>
@stop
