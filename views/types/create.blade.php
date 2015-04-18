@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('titrepage')
		<h1>{{$titre_page}}</h1>
@stop


@section('contenu')
<hr>

{{ Form::open(['method' => 'post', 'action' => 'TypeController@store']) }}

@include('tresorerie/views/types/form')

@stop


@section('topfoot1')
<span>Actions</span>
{{ link_to_action('TypeController@index', 'Retour à la liste', null, array('class' => 'btn btn-info btn-actions iconesmall list')); }}

{{ Form::submit('Créer ce type', array('class' => 'btn btn-success btn-actions')) }}
{{ Form::close() }}

@stop

@section('footer')
@parent
@stop


