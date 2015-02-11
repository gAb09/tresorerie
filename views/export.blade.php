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
{{ $ecritures }}

@stop

@section('actions')
@stop

@section('footer')
@parent
				<h3>  Le footer de export</h3>
@stop

