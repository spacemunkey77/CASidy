<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>CASidy :: Sensor Settings</title>
        <link rel="apple-touch-icon" href="/images/touch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/touch-icon-ipad-retina.png">
		<link href="{{ asset('css/sensorsetup.css') }}" rel="stylesheet">
	</head>
	<body>
        @can('can_setup_alarm')
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
                    <li class="nav-item active">
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

        <main>
            <div class="touchButtons">
                <div class="clear">
                    <p class="buttondesc">Is the system in Away mode?</p>
                    <div class="wrapper push-right">
                        <input type="checkbox" name="away" id="away" onclick="javascript:doAway()">
                        <label for="away"></label>
                    </div>
                </div>                
                <div class="clear">
                    <p class="buttondesc">Is the system in <em>Home Mode</em>?</p>
                    <div class="wrapper push-right">
                        <input type="checkbox" name="homeMode" id="homeMode" onclick="javascript:doHomeMode()">
                        <label for="homeMode"></label>
                    </div>
                </div>
                <div class="clear">
                    <p class="buttondesc">Is the system in <em>Testing Mode</em>?</p>
                    <div class="wrapper push-right">
                        <input type="checkbox" name="away" id="testing" onclick="javascript:doTesting()">
                        <label for="testing"></label>
                    </div>
                </div>
                <div class="clear">
                    <p class="buttondesc">Is the alarm set to turn on <em>Home Mode</em> at {{$hometime}}?</p>
                    <div class="wrapper push-right">
                        <input type="checkbox" name="away" id="nightsafety" onclick="javascript:doNightSafety()">
                        <label for="nightsafety"></label>
                    </div>
                </div>
            </div>
            <div style="clear: both;"></div>
            @if( ! $setup )
            <div style="margin-top: 1em"></div>
                <button type="submit" class="btn btn-primary" id="provision" onclick="javascript:doProvision()">Set up Sensors</button>
            </div>
            @endif
        </main>
        <!-- Load JavaScript -->
        <script src="{{ asset('js/sensorsetup.js') }}"></script>
        @endcan
    </body>
</html>
