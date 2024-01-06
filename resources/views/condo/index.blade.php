<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>CASidy :: Condo Controls</title>
        <link rel="apple-touch-icon" href="/images/touch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/touch-icon-ipad-retina.png">
		<link href="{{ asset('css/condo.css') }}" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">
                <img src="/images/{{$brandicon}}" width="30" height="30" class="d-inline-block align-top" alt="">
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
                    <li class="nav-item">
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

        <div id="control-buttons">
            <div class="row">
                @foreach ($switches as $switch)
    			<div class="lightSquare" id="{{$switch['switch']}}Square" data-room="{{$switch['switch']}}">
    				<img src="/images/lightbulb.png"><br>{{$switch['switchdesc']}}
    			</div>
            @if (($loop->iteration % 3) == 0)
            </div>
            <div class="row">
            @endif
                @endforeach
            </div>
            <div class="row">
                @foreach ($outlets as $outlet)
    			<div class="outletSquare" id="{{$outlet['name']}}Square" data-outlet="{{$outlet['name']}}">
    				<img src="/images/outlet.png" height="40px"><br>{{$outlet['outlet_desc']}}
    			</div>
            @if (($loop->iteration % 3) == 0)
            </div>
            <div class="row">
            @endif
                @endforeach
            </div>
        </div>

		<!-- Load JavaScript -->
		<script src="{{ asset('js/condo.js') }}"></script>
	</body>
</html>
