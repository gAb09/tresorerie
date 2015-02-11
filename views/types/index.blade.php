@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('topcontent1')
<h1 class="titrepage">{{ $titre_page }}
</h1>
@stop


@section('topcontent2')
@stop


@section('contenu')

@foreach($types as $type)

<hr />
<h3>{{ $type->nom }} <small>(rang n° {{ $type->rang }})</small></h3>

<p>• Description :<br />{{ $type->description }}</p>

@if($type->req_justif)
<p>• La référence à un <strong>justificatif sera obligatoire</strong> lors de la saisie d'une écriture.
	<br />Le séparateur est : “{{ $type->sep_justif }}”
</p>
@endif
</p>

<p class="label label-edit iconesmall edit">
	{{link_to_action('TypeController@edit', 'Modifier ce type', $type->id)}}
</p>

<br />
@endforeach

@stop


@section('actions')
<a href ="{{ URL::route('tresorerie.types.create') }}" class="btn btn-success iconemedium add">Créer un nouveau type</a>
@stop


@section('footer')
@parent
<h3>  Le footer de types</h3>
@stop
