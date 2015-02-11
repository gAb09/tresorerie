@extends('tresorerie/views/layout')

@section('titre')
@parent


@stop


@section('topcontent1')

<h1 class="titrepage">{{ $titre_page }}</h1>
Nota : les comptes écrits en rouge sont des comptes du Plan Comptable Général (PCG).
<br /> Ils ne peuvent être supprimés ni modifiés. Seulement commentés et activés/désactivés.
<br /> Les comptes écrits en vert sont les comptes "maison" et sont totalement modifiables.
<br /> Les comptes activés (PCG ou maison) sont montrés sur fond vert.
<br />Un compte activé sera disponible dans les listes pour être appliqué à une écriture.
@stop


@section('topcontent2')
<br />
<br />
@foreach($classes as $classe)
<div class="classeRacine">Classe {{ $classe->numero }} :
	<br />{{ link_to_action('CompteController@index', $classe->libelle, $classe->numero) }}</div>
	@endforeach

	@stop


	@section('contenu')

	@foreach($comptes as $compte)
	<hr />
	<div class="compte">
		<div class="compte {{ $compte->classe_actif }}">

			<h4 class=" {{ $compte->class_pco }}">{{ $compte->numero }} – {{ $compte->libelle }}</h4>

			<div>
				@if ($compte->description_officiel) 
				<h5 class="pco">Description officielle (Wikipédia) :</h5>
				<p class="pco">{{ $compte->description_officiel }}</p>
				@endif
			</div>

			<div>
				@if ($compte->description_comp)
				<h5>Informations complémentaires :</h5>
				<p>{{ $compte->description_comp }}</p>
				@endif
			</div>

			<div>
				@if ($compte->description_lmh)
				<h5>Compte spécifique La Mauvaise Herbe :</h5>
				<p>{{ $compte->description_lmh }}</p>
				@endif
			</div>
		</div>

			{{link_to_route('tresorerie.comptes.edit', 'Modifier ce compte', $compte->id, array('class' => "badge badge-edit iconesmall edit"))}}

		@endforeach
		@stop

		@section('actions')
		<a href ="{{ URL::route('tresorerie.comptes.create') }}" class="btn btn-success iconemedium add"
style="font-size:1.1em">Ajouter un nouveau compte</a><br />

Nota : si une des pages de classes de comptes ne s’affiche pas complètement essayez de la recharger. Si le problème persiste contacter le ouaibmaster.
<br />C’est la raison pour laquelle il n’est pas prévu de pouvoir lister la totalité des comptes, car celà créerait une page trop longue.
		@stop

		@section('footer')
		@parent
		<h3>  Le footer de comptes</h3>

		@stop
