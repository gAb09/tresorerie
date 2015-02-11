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
<hr>

{{ Form::model($ecriture, array('name' => 'form', 'url' => 'tresorerie/ecritures', 'method' => 'post', 'action' => 'EcritureController@store')) }}

@include('tresorerie/views/ecritures/form')


@stop

@section('actions')
{{ link_to(Session::get('page_depart'), 'Retour liste', array('class' => 'btn btn-info iconesmall list',)); }}

{{ Form::submit('Créer cette écriture', array('class' => 'btn btn-success iconmedium add')) }}
{{ Form::close() }}
@stop

@section('tresorerie/footer')
@parent
<h3>  Le footer de création d'écritures</h3>
@stop