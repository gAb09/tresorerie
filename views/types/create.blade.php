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

{{ Form::open(['method' => 'post', 'action' => 'TypeController@store']) }}

@include('tresorerie/views/types/form')

@stop


@section('zapette')
{{ link_to_action('TypeController@index', 'Retour à la liste', null, array('class' => 'btn btn-info btn-zapette iconesmall list')); }}

{{ Form::submit('Créer ce type', array('class' => 'btn btn-success btn-zapette')) }}
{{ Form::close() }}

@stop

@section('footer')
@parent
<h3>  Le footer de types</h3>

@stop


