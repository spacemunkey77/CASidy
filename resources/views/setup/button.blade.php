<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>CASidy :: Light Controls Button Setup</title>
        <link rel="apple-touch-icon" href="/images/touch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/touch-icon-ipad-retina.png">
		<link href="{{ asset('css/buttons.css') }}" rel="stylesheet">
	</head>
	<body>
        @can('condo_settings')
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">
                <img src="/images/houseicon.png" width="30" height="30" class="d-inline-block align-top" alt="">
                CASidy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> 
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/status">Status</a>
                    </li>
                    @can('condo_settings')
                    <li class="nav-item">
                        <a class="nav-link" href="/sensors/setup">Security</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="/setup">Settings</a>
                    </li>
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>                    
                </ul>
            </div>
        </nav>
        <main>
            <form action="/setup/buttons" method="POST">
                @csrf
                @foreach($switches as $key => $switch)
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="switch{{ $key }}">{{ $key }}</label>
                        <select class="custom-select" name="switches[{{ $key }}][]" id="switch{{ $key }}" multiple>
                            @foreach($lights as $light)
                                @if (in_array($light->id, $switch))
                                <option value="{{ $light['id'] }}" selected="selected">{{ $light['name'] }}</option>
                                @else
                                <option value="{{ $light['id'] }}">{{ $light['name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                @endforeach
                <p>&nbsp;</p>
                <div class="row">
                    <button type="submit" class="btn btn-primary" id="setbuttons">Save Button Settings</button>
                </div>
            </form>
        </main>
		<!-- Load JavaScript -->
		<script src="{{ asset('js/buttons.js') }}"></script>
        @endcan
	</body>
</html>
