@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('topcontent1')
<h1 class="titrepage">{{ $titre_page }}</h1>
@stop


@section('topcontent2')
@stop


@section('contenu')
<hr>
{{ Form::open(['method' => 'post', 'action' => 'CompteController@store']) }}

@include('tresorerie/views/comptes/form')

@stop

@section('actions')
{{ link_to_action('CompteController@index', 'Retour à la liste', null, array('class' => 'btn btn-info btn-actions iconemedium list', 'style' => 'font-size:1.1em')); }}

{{ Form::submit('Créer ce compte', array('class' => 'btn btn-actions btn-success')) }}
{{ Form::close() }}
@stop

@section('footer')
@parent
<h3>  Le footer de comptes</h3>
@stop