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
	<script src="/assets/tresorerie/js/justificatif.js"></script>
	<script src="/ckeditor/ckeditor.js"></script>
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
			<div class="span8 titrepage">
				@yield('titrepage')
			</div>

			<!-- - - - - - - - - - - - - - - - USER / DECONNEXION - - - - - - - - - - - - - - -->
			<div class="span3 user_widget">
				@include('shared/views/user_widget')
			</div> 
		</div>


		<!-- - - - - - - - - - - - - - - - CONTENU - - - - - - - - - - - - - - -->
		<!-- <div style="margin-top:60px">
			<?php var_dump(Session::all());?>
		</div> -->


		<div class="row-fluid content">
				@yield('contenu')
		</div>
		<div class="chassebaspage">
		</div>
	</div>

	<!-- - - - - - - - - - - - - - - - FOOTER - - - - - - - - - - - - - - -->


	<div class="span12 row-fluid topfooter">




		<!-- - - - - - - - - - - - - - - - TopFoot 1 - - - - - - - - - - - - - - -->
		<div class="topfoot topfoot1 {{ isset($tf1) ? $tf1 : "" }}">
			@yield('topfoot1')
		</div>


		<!-- - - - - - - - - - - - - - - - TopFoot 2 - - - - - - - - - - - - - - -->
		<div class="topfoot topfoot2">
			@yield('topfoot2')
		</div>


		<!-- - - - - - - - - - - - - - - - TopFoot 3 - - - - - - - - - - - - - - -->
		<div class="topfoot topfoot3">
			@yield('topfoot3')
		</div>

	</div>

	<footer>
		<div class="version">
			@section('tresorerie/footer')
			© gAb – Tresorerie version 1.3.2
			@show
		</div>
	</footer>


	@section('script')

	@show
</body>
</html>