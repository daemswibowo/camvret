
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Install</title>
</head>
<body>

	{!! Form::open(['url' => route('install.store')]) !!}
	{!! Form::label('app_key', 'App Key', []) !!}
	{!! Form::text('app_key', null, ['placeholder' => 'Your app key']) !!}
	{!! Form::submit('Install', []) !!}
	{!! Form::close() !!}

	@if ($errors->any())
	<p style="color: red">{{ $errors->first() }}</p>
	@endif
</body>
</html>