@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('titrepage')
		<h1 class="titrepage">{{$titre_page}}</h1>
<p class="badge badge-locale iconesmall add"><a href="{{ URL::action('StatutController@create') }}">Ajouter un nouveau statut</a></p>
@stop



@section('contenu')


@foreach($statuts as $statut)
<hr />

<h2 class="item">{{ $statut->nom }}</h2>
<h5>ID : {{ $statut->id }}</h5>
<h5>Classe : {{ $statut->classe }}</h5>
<h5>Description :</h5><p>{{ $statut->description }}</p>
<p class="badge badge-locale iconesmall edit"><a href="{{ URL::action('StatutController@edit', [$statut->id]) }}">Modifier ce statut</a></p>
<br />
@endforeach

@stop

@section('tresorerie/footer')
@parent
@stop

