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
		<th class="icone">
			Edit
		</th>
		<th class="icone">
			Dupli
		</th>
	</thead>

	<tbody>
		@foreach($reports as $report)
		<tr id ="{{$report->id}}" class=""
			ondblclick = document.location.href="{{ URL::action('ReportController@edit', [$report->id]) }}">
			<td>{{ $report->id }}</td>
			<td class ="info">{{ DatesFr::longue($report->date_valeur) }}
				<span>
					Date d’émission : {{ DatesFr::longue($report->date_emission) }}
				</span>
			</td>
			<td>{{ $report->type->nom}}
				@if($report->justificatif){{$report->type->sep_justif}}{{$report->justificatif}}@endif
			</td>
			<td>{{ $report->banque->nom }}
			</td>
			<td>{{ $report->libelle }}
				@if($report->libelle_detail)
				— {{ $report->libelle_detail }}
				@endif
			</td>
			<td class="{{ $report->signe->nom_sys }}">{{ NombresFr::francais_insec($report->montant) }}</td>
			<td>{{ $report->compte->numero }}<br />({{ $report->compte->libelle }})</td>
			<td>{{ DatesFr::longue($report->created_at) }}</td>
			<td>{{ DatesFr::longue($report->updated_at) }}</td>
			<td>
				<a class="iconemedium edit" href ="{{ URL::action('ReportController@edit', [$report->id]) }}"></a>
			</td>
			<td>
				<a class="iconemedium dupli" href ="{{ URL::action('ReportController@duplicate', [$report->id]) }}"></a>
			</td>

			@endforeach
		</tr>
	</tbody>
</table>

@stop

@section('topfoot1')
<span>Actions</span>
{{link_to_action('ReportController@create', 'Ajouter un report', null, ["class" => "btn btn-success btn-actions iconemedium add"])}}
@stop

@section('tresorerie/footer')
@parent
@stop

@section('script')
<script src="/assets/tresorerie/js/reports.js">
</script>
@stop
