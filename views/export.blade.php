@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('titrepage')
<h1 class="titrepage">{{ $titre_page }}</h1>
@stop



@section('contenu')
<hr>
{{ $ecritures }}

@stop

@section('actions')
@stop

@section('affichage')
@stop

@section('footer')
@parent
				<h3>  Le footer de export</h3>
@stop

