@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('titrepage')
<h1>{{$titre_page}}</h1>
<p>
	Créée le {{ DatesFr::longue($transfert->created_at) }}
	@if(isset($transfert->createur->login))
	par {{ $transfert->createur->login }}<br />
	@endif
 – 
 Modifiée le {{ DatesFr::longue($transfert->updated_at) }} 
	@if(isset($transfert->modificateur->login))
	par {{ $transfert->modificateur->login }}
	@endif
</p>
@stop

@section('contenu')

<hr />
{{ Form::model($transfert, ['name' => 'form', 'method' => 'put', 'route' => ['tresorerie.transferts.update', $transfert->id]]) }}

@include('tresorerie/views/transferts/form')

@stop


@section('topfoot1')
<span>Actions</span>
	{{ link_to(Session::get('page_depart')."#".Session::get('tresorerie.mois_travail'), 'Retour à la liste', array('class' => 'btn btn-info btn-actions iconesmall list')); }}

	{{ Form::submit('Modifier cette écriture', array('class' => 'btn btn-edit btn-actions')) }}
	{{ Form::close() }}

	{{ Form::open(array('url' => 'tresorerie/transferts/'.$transfert->id, 'method' => 'delete')) }}
	{{ Form::submit('Supprimer cette écriture', ['class' => 'btn btn-danger btn-actions', 'onClick' => 'javascript:return(confirmation());']) }}
	{{ Form::close() }}
@stop

@section('script')

<script src="/assets/tresorerie/js/justificatif.js">
</script>

@stop