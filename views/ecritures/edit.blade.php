@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('titrepage')
<h1>{{$titre_page}} </h1>
<p>
	Créée le {{ DatesFr::longue($ecriture->created_at) }}
	@if(isset($ecriture->createur->login))
	par {{ $ecriture->createur->login }}<br />
	@endif
 – 
 Modifiée le {{ DatesFr::longue($ecriture->updated_at) }} 
	@if(isset($ecriture->modificateur->login))
	par {{ $ecriture->modificateur->login }}
	@endif
</p>
@stop

@section('contenu')

<hr />
{{ Form::model($ecriture, ['name' => 'form', 'method' => 'put', 'route' => ['tresorerie.ecritures.update', $ecriture->id]]) }}

@include('tresorerie/views/ecritures/form')

@stop


@section('topfoot1')
<span>Actions</span>
	{{ link_to(Session::get('page_depart')."#".Session::get('tresorerie.mois_travail'), 'Retour liste', array('class' => 'btn btn-info btn-actions iconesmall list')); }}

	{{ Form::submit('Valider modification', array('class' => 'btn btn-edit btn-actions')) }}
	{{ Form::close() }}

	{{ Form::open(array('url' => 'tresorerie/ecritures/'.$ecriture->id, 'method' => 'delete')) }}
	{{ Form::submit('Supprimer cette écriture', ['class' => 'btn btn-danger btn-actions', 'onClick' => 'javascript:return(confirmation());']) }}
	{{ Form::close() }}
@stop

@section('script')

<script src="/assets/tresorerie/js/justificatif.js">
</script>

@stop