@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('topcontent1')
<h1 class="titrepage">{{$titre_page}} <small>(Id = {{$banque->id}})</small></h1>
@stop


@section('topcontent2')
@stop


@section('contenu')

<hr />

{{ Form::model($banque, ['method' => 'PUT', 'action' => ['BanqueController@update', $banque->id]]) }}

@include('tresorerie/views/banques/form')

@stop



@section('actions')
{{ link_to_action('BanqueController@index', 'Retour à la liste', null, array('class' => 'btn btn-info btn-actions iconesmall list')); }}

{{ Form::submit('Modifier cette banque', array('class' => 'btn btn-edit btn-actions')) }}
{{ Form::close() }}

{{ Form::open(['method' => 'delete', 'action' => ['BanqueController@destroy', $banque->id]]) }}
{{ Form::submit('Supprimer cette banque', array('class' => 'btn btn-danger', 'onClick' => 'javascript:return(confirmation());')) }}
{{ Form::close() }}
@stop


@section('footer')
@parent
<h3>  Le footer de banques</h3>

@stop