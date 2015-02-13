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

{{ Form::open(['method' => 'PUT', 'action' => ['TypeController@update', $type->id]]) }}

@include('tresorerie/views/types/form')

@stop

@section('actions')
{{ link_to_action('TypeController@index', 'Retour à la liste', null, array('class' => 'btn btn-info btn-actions iconesmall list')); }}

{{ Form::submit('Modifier ce type', array('class' => 'btn btn-edit btn-actions')) }}
{{ Form::close() }}

{{ Form::open(['method' => 'delete', 'action' => ['TypeController@destroy', $type->id]]) }}
{{ Form::submit('Supprimer ce type', array('class' => 'btn btn-danger btn-actions', 'onClick' => 'javascript:return(confirmation());')) }}
{{ Form::close() }}

@stop

@section('footer')
@parent
<h3>  Le footer de types</h3>
@stop