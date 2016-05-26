@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('titrepage')
		<h1>{{$titre_page}}</h1>
@stop


@section('contenu')
<hr>

{{ Form::model($transfert, array('name' => 'form', 'url' => 'tresorerie/transferts', 'method' => 'post', 'action' => 'TransfertController@store')) }}

@include('tresorerie/views/transferts/form')


@stop

@section('topfoot1')
<span>Actions</span>
{{ link_to(Session::get('page_depart'), 'Retour liste', array('class' => 'btn btn-info btn-actions iconesmall list',)); }}

{{ Form::submit('Créer cette écriture', array('class' => 'btn btn-success btn-actions iconmedium add')) }}
{{ Form::close() }}
@stop

@section('tresorerie/footer')
@parent
@stop