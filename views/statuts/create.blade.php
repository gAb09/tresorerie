@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('titrepage')
		<h1 class="titrepage">{{$titre_page}}</h1>
@stop


@section('contenu')

{{ Form::model($statut, ['method' => 'post', 'route' => 'tresorerie.statuts.store']) }}

@include('tresorerie/views/statuts/form')

<br />
{{ Form::submit('Créer', array('class' => 'btn')) }}
{{ Form::close() }}

@stop

@section('footer')
@parent
@stop