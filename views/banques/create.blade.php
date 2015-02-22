@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('titrepage')
<h1>{{$titre_page}}</h1>
@stop



@section('contenu')
<hr>

{{ Form::model($banque, ['method' => 'post', 'action' => 'BanqueController@store']) }}

@include('tresorerie/views/banques/form')

@stop

@section('actions')
{{ link_to_action('BanqueController@index', 'Retour à la liste', null, array('class' => 'btn btn-info btn-actions iconesmall list')); }}

{{ Form::submit('Créer cette banque', array('class' => 'btn btn-success btn-actions')) }}
{{ Form::close() }}
@stop

@section('affichage')
@stop

@section('footer')
@parent
<h3>  Le footer de banques</h3>
@stop