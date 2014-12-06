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
<?php
$statuts = Statut::all();
?>
<hr />

@foreach($statuts as $statut)

<h2 class="item {{ $statut->classe }}">{{ $statut->id }} - {{ $statut->nom }}</h2>
<p>{{ $statut->description }}</p>
<hr class="filetfin"/>

@endforeach

@stop

@section('tresorerie/footer')
@parent
<h3>  Le footer de statuts</h3>
@stop

