<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <title>CASidy :: Night Settings</title>
		<link href="{{ asset('css/night.css') }}" rel="stylesheet">
	</head>
	<body>
        @can('can_setup_night')
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

		<div class="container">
            <form action="/night" method="POST">
                @csrf
                <h1>Minutes before Sunset</h1>

                <div class="form-group col-md-3">
                    <label for="sunsetdelta">Turn lights on MINUTES before sunset.</label>
                    <input type="text" class="form-control" id="sunsetdelta" name="sunsetdelta"
                     value="{{ $sunsetdelta }}"/>
                </div>

                <h1>Lights</h1>

                @foreach($lights as $light)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="lights[]" value="{{ $light['lightid'] }}"
                    @if ($light['night'])
                    checked="checked"
                    @endif
                     id="light{{ $light['lightid'] }}"/>
                    <label class="form-check-label" for="light{{ $light['lightid'] }}">
                        {{ $light['name'] }}
                    </label>
                </div>
                @endforeach

                <h1>Outlets</h1>

                @foreach($outlets as $outlet)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="outlets[]" value="{{ $outlet['id'] }}"
                    @if ($outlet['night'])
                    checked="checked"
                    @endif
                     id="outlet{{ $outlet['id'] }}"/>
                    <label class="form-check-label" for="outlet{{ $outlet['id'] }}">
                     &nbsp;{{ $outlet['outlet_desc'] }}
                    </label>
                </div>
                @endforeach
                <p>&nbsp;</p>
                <div class="row">
                    <button type="submit" class="btn btn-primary" id="setnight">Save Night Settings</button>
                </div>
            </form>
        </div>
		<!-- Load JavaScript -->
		<script src="{{ asset('js/night.js') }}"></script>
        @endcan
	</body>
</html>
