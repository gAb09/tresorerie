<ul class="">
	@if(Auth::user()->role_id != 3)

	@foreach ($modes as $mode)

	@if ($mode->publication == 1)

	@if ($mode->route == Request::path())
	<li class ="active">
		@else
		<li class="">
			@endif

			<a href={{ URL::to($mode->route) }} > {{ $mode->etiquette }} 
				 <span class="iconemedium mode_{{ $mode->nom_sys }}" href={{ URL::to($mode->route) }}></span>
			</a>


		</li>
		@endif
		@endforeach
		@endif
	</ul>