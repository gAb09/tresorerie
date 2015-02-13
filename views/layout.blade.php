<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>
		@section('titre')
		{{ isset($titre_page) ? $titre_page : Menu::where('nom_sys', Request::segment(1))->get()[0]->etiquette }}
		@show

	</title>
	@section('assets')
	<link rel="shortcut icon" href="/assets/img/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="/assets/tresorerie/css/base.css" rel="stylesheet" type="text/css">
	<link href="/assets/tresorerie/css/layout.css" rel="stylesheet" type="text/css">
	<link href="/assets/tresorerie/css/tableaux.css" rel="stylesheet" type="text/css">
	<link href="/assets/tresorerie/css/footer.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/style.css" rel="stylesheet" type="text/css">
	<script src="/assets/tresorerie/js/tresorerie.js"></script>
	@show
</head>





<body @section('body')>
	@show

	<div class="container-fluid">

		<!-- - - - - - - - - - - - - - - - Messages - - - - - - - - - - - - - - -->
		@include('shared/views/messages')



		<header class="row-fluid">


			<!-- - - - - - - - - - - - - - - - MENU SECTIONS - - - - - - - - - - - - - - -->

			<nav class="navbar sections span12">
				@include('menus/views/menuSections')
			</nav>


		</header>

		<!-- - - - - - - - - - - - - - SOUS MENU MODES - - - - - - - - - - - - - - -->
@if(Auth::user()->role_id != 3)
		<nav class="navleft">
			<nav class="navmodes">
				@include('tresorerie/views/modes')
			</nav>

			<ul class="nav navconfig">	
				<li  class="dropdown">
					@include('tresorerie/views/configuration')
				</li>
			</ul>
		</nav>
@endif

		<!-- - - - - - - - - - - - - - - - TOP CONTENT (2 zones) - - - - - - - - - - - - - - -->


		<div class="row-fluid topcontent">

			<!-- - - - - - - - - - - - - - TITREPAGE - - - - - - - - - - - - - - -->
			<div class="span9 titrepage">
				@yield('topcontent1')
			</div>


			<!-- - - - - - - - - - - - - - - - USER / DECONNEXION - - - - - - - - - - - - - - -->
			<div class="span3 user_widget">
				@include('shared/views/user_widget')
			</div> 
		</div>


		<!-- - - - - - - - - - - - - - - - CONTENU - - - - - - - - - - - - - - -->


		<div class="row-fluid content">
			<div>
				@yield('contenu')
			</div>
		</div>


		<!-- - - - - - - - - - - - - - - - FOOTER - - - - - - - - - - - - - - -->

		<footer>

			<div class="span12 row-fluid topfooter">



				<!-- - - - - - - - - - - - - - - - ACTIONS - - - - - - - - - - - - - - -->
				<div class="span6 actions">
					@yield('actions')
				</div>

				<!-- - - - - - - - - - - - - - - - AFFICHAGE - - - - - - - - - - - - - - -->
				<div class="span6 affichage">
					@yield('affichage')
				</div>
			</div>
			<div>
				@section('tresorerie/footer')
				© gAb – Tresorerie version 1.1
				@show
			</div>
		</footer>


		@section('script')

		@show
	</body>
	</html>