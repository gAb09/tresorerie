@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('titrepage')
<h1>{{$titre_page}}</h1>
<p>
	Créée le {{ DatesFr::longue($report->created_at) }}
	@if(isset($report->createur->login))
	par {{ $report->createur->login }}<br />
	@endif
 – 
 Modifiée le {{ DatesFr::longue($report->updated_at) }} 
	@if(isset($report->modificateur->login))
	par {{ $report->modificateur->login }}
	@endif
</p>
@stop

@section('contenu')

<hr />
{{ Form::model($report, ['name' => 'form', 'method' => 'put', 'route' => ['tresorerie.reports.update', $report->id]]) }}

@include('tresorerie/views/reports/form')

@stop


	@section('actions')
	{{ link_to(Session::get('page_depart')."#".Session::get('tresorerie.mois_travail'), 'Retour à la liste', array('class' => 'btn btn-info btn-actions iconesmall list')); }}

	{{ Form::submit('Modifier cette écriture', array('class' => 'btn btn-edit btn-actions')) }}
	{{ Form::close() }}

	{{ Form::open(array('url' => 'tresorerie/reports/'.$report->id, 'method' => 'delete')) }}
	{{ Form::submit('Supprimer cette écriture', ['class' => 'btn btn-danger btn-actions', 'onClick' => 'javascript:return(confirmation());']) }}
	{{ Form::close() }}
@stop

@section('script')

<script src="/assets/tresorerie/js/justificatif.js">
</script>

@stop