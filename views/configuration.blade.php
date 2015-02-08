@if(Auth::user()->role_id != 3)


<span>Configuration</span> <span class="iconemedium mode_configuration"></span>
<ul class="dropdown-menu">

@foreach ($configs as $config)

		@if ($config->publication == 1)

		<li class="dropdown">
			<a href="{{ URL::to($config->route) }}">
			 {{ $config->etiquette }} 
			</a>
			@if(!$config->children->isEmpty())
			<ul class="dropdown-menu">
				@foreach ($config->children as $children)
					@if($children->publication == 1)
					<li>
						<a href={{ URL::to($children->route) }} >
							{{ $children->etiquette }}
						</a>
					</li>
					@endif
				@endforeach
			</ul>
			@endif
		</li>
		@endif
@endforeach
</ul>
@endif