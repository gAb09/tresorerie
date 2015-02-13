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

{{ Form::model($banque, ['method' => 'post', 'action' => 'BanqueController@store']) }}

@include('tresorerie/views/banques/form')

@stop

@section('actions')
{{ link_to_action('BanqueController@index', 'Retour à la liste', null, array('class' => 'btn btn-info btn-actions iconesmall list')); }}

{{ Form::submit('Créer cette banque', array('class' => 'btn btn-success btn-actions')) }}
{{ Form::close() }}
@stop

@section('footer')
@parent
<h3>  Le footer de banques</h3>
@stop