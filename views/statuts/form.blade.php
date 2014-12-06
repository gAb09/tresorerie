{{ Form::label('nom', 'Nom', array ('class' => '')) }}
{{ Form::text('nom', null, array ('class' => '')) }}

{{ Form::label('classe', 'Classe pour les css', array ('class' => '')) }}
{{ Form::text('classe', null, array ('class' => '')) }}

{{ Form::label('description', 'Description (facultative)', array ('class' => '')) }}
{{ Form::textarea('description', null, array ('class' => '')) }}