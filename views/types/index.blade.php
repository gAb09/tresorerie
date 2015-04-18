@extends('tresorerie/views/layout')

@section('titre')
@parent
@stop


@section('titrepage')
<h1>{{ $titre_page }}
</h1>
@stop


@section('contenu')

@foreach($types as $type)

<hr />
<h3>{{ $type->nom }} <small>(rang n° {{ $type->rang }})</small></h3>

<p>• Description :<br />{{ $type->description }}</p>

@if($type->statut_justif)
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


@section('topfoot1')
<span>Actions</span>
<a href ="{{ URL::route('tresorerie.types.create') }}" class="btn btn-success btn-actions iconemedium add">Créer un nouveau type</a>
@stop


@section('footer')
@parent
@stop
