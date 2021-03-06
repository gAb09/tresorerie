@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('titrepage')
		<h1>{{$titre_page}}</h1>
@stop


@section('contenu')

<hr />

@foreach($banques as $banque)

<h2 class="item">{{ $banque->nom }}</h2>
<h5>Description :</h5><p>{{ $banque->description }}</p>
<p class="label label-edit iconesmall edit">
	{{link_to_action('BanqueController@edit', 'Modifier cette banque', $banque->id)}}
</p>
<hr />
@endforeach

@stop

@section('topfoot1')
<span>Actions</span>
		<a href ="{{ URL::action('BanqueController@create') }}" class="btn btn-success btn-actions iconemedium add">Créer une banque</a>
@stop

@section('tresorerie/footer')
@parent
@stop