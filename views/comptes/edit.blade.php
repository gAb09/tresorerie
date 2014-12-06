@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('topcontent1')
<h1 class="titrepage  ">Édition du compte n° {{$compte->numero}} : <span class="{{ $compte->class_pco }}">{{$compte->libelle}}</span></h1>
@stop


@section('topcontent2')
@stop


@section('contenu')

<hr />

{{ Form::open(['method' => 'PUT', 'action' => ['CompteController@update', $compte->id]]) }}

@include('tresorerie/views/comptes/form')

@stop


@section('zapette')
	{{ link_to_action('CompteController@index', 'Retour à la liste', Session::get('Courant.classe'), array('class' => 'btn btn-info btn-zapette iconemedium list')); }}

	{{ Form::submit('Modifier ce compte', array('class' => 'btn btn-edit btn-zapette')) }}
	{{ Form::close() }}


	@if(!$compte->pco)
	{{ Form::open( ['method' => 'delete', 'action' => ['CompteController@destroy', $compte->id]] ) }}
	{{ Form::submit('Supprimer ce compte', ['class' => 'btn btn-danger', 'onClick' => 'javascript:return(confirmation());']) }}
	{{ Form::close() }}
	@endif
@stop

@section('footer')
@parent
<h3>  Le footer de comptes</h3>

@stop