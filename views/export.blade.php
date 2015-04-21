@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('titrepage')
<h1>{{ $titre_page }}</h1>
@stop



@section('contenu')
<hr>
{{ $ecritures }}

@stop

@section('topfoot1')
@stop

@section('topfoot3')
@stop

@section('footer')
@parent
				<h3>  Le footer de export</h3>
@stop

