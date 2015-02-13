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
	<p style="margin-bottom:2px"><span class="pco">Les comptes du Plan Comptable Général (PCG) apparaissent en rouge.
		Ils ne peuvent être supprimés ni modifiés. Seulement recevoir une description complémentaire et être activés/désactivés.</span>
		<br /> Les comptes "maison" (lmh) apparaisent en vert et sont totalement éditables.
		<div class="compte actif">Qu’il soit <span class="pco">PCG</span> ou lmh, un compte activé apparaît sur fond vert.
		<br />Un compte activé sera disponible dans les listes pour être appliqué à une écriture.</div>

		@foreach($comptes as $compte)
		<hr />
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
		<a href ="{{ URL::route('tresorerie.comptes.create') }}" class="btn btn-success btn-actions iconemedium add"
		style="font-size:1.1em">Ajouter un nouveau compte</a><br />
		@stop

		@section('affichage')
@foreach($classes as $classe)
<div class="classeRacine">Classe {{ $classe->numero }} :
	<br />{{ link_to_action('CompteController@index', $classe->libelle, $classe->numero) }}</div>
	@endforeach
		@stop

		@section('footer')
		@parent
		<h3>  Le footer de comptes</h3>

		@stop
