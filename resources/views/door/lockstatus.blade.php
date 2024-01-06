<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <title>CASidy :: Front Door Status</title>
		<link href="{{ asset('css/door.css') }}" rel="stylesheet">
	</head>
	<body>
        @can('can_operate_lock')
        <p style="margin: 1em"><strong>{{ $status }}</strong></p>
        @endcan
	</body>
</html>
