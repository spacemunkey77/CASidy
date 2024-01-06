<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>CASidy :: Light Controls Setup</title>
        <link rel="apple-touch-icon" href="/images/touch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/touch-icon-ipad-retina.png">
		<link href="{{ asset('css/setup.css') }}" rel="stylesheet">
	</head>
	<body>
        @can('condo_settings')
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
            <div class="touchButtons">
                <div class="clear">
                    <p class="buttondesc">Use sensors for Home Automation.</p>
                    <div class="wrapper push-right">
                        <input type="checkbox" name="homeAutomation" id="homeAutomation" onclick="javascript:doHomeAutomation()">
                        <label for="homeAutomation"></label>
                    </div>
                </div>
                 <div class="clear">
                    <p class="buttondesc">Use lights for artificial sunrise?</p>
                    <div class="wrapper push-right">
                        <input type="checkbox" name="sunriseLights" id="sunriseLights" onclick="javascript:doSunriseLights()">
                        <label for="sunriseLights"></label>
                    </div>
                </div>
                <div class="clear">
                    <p class="buttondesc">Rainy/Cloudy Lights?</p>
                    <div class="wrapper push-right">
                        <input type="checkbox" name="rainyDay" id="rainyDay" onclick="javascript:doRainyDay()">
                        <label for="rainyDay"></label>
                    </div>
                </div>
                <div class="clear">
                    <p class="buttondesc">Away from Home Lights off at night?</p>
                    <div class="wrapper push-right">
                        <input type="checkbox" name="homeAway" id="homeAway" onclick="javascript:doHomeAway()">
                        <label for="homeAway"></label>
                    </div>
                </div>
             </div>

            <div style="clear: both;"></div>
            <div style="margin-top: 1em"></div>
                @can('can_setup_night')
                <p><a href="/night">🌛💡Setup Nighttime Lights.</a></p>
                <p><a href="/cloudy">🌧🌩🌥 Setup Cloudy Lights.</a></p>
                <p><a href="/setup/buttons">💡1⃣ Setup Lights Buttons</a></p>
                <p><a href="/setup/timers">⏰ Setup Timers</a></p>
                @endcan
                @can('can_setup_alarm')
                @if( ! $setup )
                <p><a href="/sensors/setup">🚨 Activate Sensors</a></p>
                @endif
                @endcan
            </div>
        </main>
		<!-- Load JavaScript -->
		<script src="{{ asset('js/setup.js') }}"></script>
        @endcan
	</body>
</html>
