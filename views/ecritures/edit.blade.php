@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('topcontent1')
<h1 class="titrepage">{{$titre_page}}</h1>
@stop


@section('topcontent2')
@stop


@section('contenu')

<hr />
{{ Form::model($ecriture, ['name' => 'form', 'method' => 'put', 'route' => ['tresorerie.ecritures.update', $ecriture->id]]) }}

@include('tresorerie/views/ecritures/form')

<p>Créée le {{ DatesFr::longue($ecriture->created_at) }}<br />
	Modifiée le {{ DatesFr::longue($ecriture->updated_at) }}</p>
	@stop


	@section('actions')
	{{ link_to(Session::get('page_depart')."#".Session::get('Courant.mois'), 'Retour à la liste', array('class' => 'btn btn-info btn-actions iconesmall list')); }}

	{{ Form::submit('Modifier cette écriture', array('class' => 'btn btn-edit btn-actions')) }}
	{{ Form::close() }}

	{{ Form::open(array('url' => 'tresorerie/ecritures/'.$ecriture->id, 'method' => 'delete')) }}
	{{ Form::submit('Supprimer cette écriture', ['class' => 'btn btn-danger btn-actions', 'onClick' => 'javascript:return(confirmation());']) }}
	{{ Form::close() }}
	@stop