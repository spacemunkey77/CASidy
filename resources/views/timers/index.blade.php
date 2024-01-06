<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>CASidy :: Timers Setup</title>
        <link rel="apple-touch-icon" href="/images/touch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/touch-icon-ipad-retina.png">
		<link href="{{ asset('css/timer.css') }}" rel="stylesheet">
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
                        <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                    </li>
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

        <form action="/setup/timers" method="post">

            @csrf

            <div class="row mb-3 px-3 pt-3">
                 <label for="sunsetfrom" class="col-sm-2 col-form-label">Sunset From:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="sunsetfrom" value="{{$timer['sunsetfrom']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="sunsetto" class="col-sm-2 col-form-label">Sunset To:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="sunsetto" value="{{$timer['sunsetto']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="lightsofffrom" class="col-sm-2 col-form-label">Lights Off From:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="lightsofffrom" value="{{$timer['lightsofffrom']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="lightsoffto" class="col-sm-2 col-form-label">Lights Off To:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="lightsoffto" value="{{$timer['lightsoffto']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="cloudylightsfrom" class="col-sm-2 col-form-label">Cloudy Lights From:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="cloudylightsfrom" value="{{$timer['cloudylightsfrom']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="cloudylightsto" class="col-sm-2 col-form-label">Cloudy Lights To:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="cloudylightsto" value="{{$timer['cloudylightsto']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="lightsofftime" class="col-sm-2 col-form-label">Time to set Lights Off Time:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="lightsofftime" value="{{$timer['lightsofftime']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="nightsafetyevening" class="col-sm-2 col-form-label">Turn on Home Security System Mode:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="nightsafetyevening" value="{{$timer['nightsafetyevening']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="nightsafetymorning" class="col-sm-2 col-form-label">Turn off Home Security System Mode:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="nightsafetymorning" value="{{$timer['nightsafetymorning']}}">
                 </div>
            </div>

            <div class="row mb-3 px-3 pt-3">
                 <label for="sunrisetime" class="col-sm-2 col-form-label">Time to run Sunrise Light Automation:</label>
                 <div class="col-sm-3 ">
                    <input type="time" class="form-control" name="sunrisetime" value="{{$timer['sunrisetime']}}">
                 </div>
            </div>

            <button type="submit" class="ml-3 mb-3 btn btn-primary">Set Timers</button>

        </form>

		<!-- Load JavaScript -->
        <script src="{{ asset('js/timer.js') }}"></script>
        @endcan
	</body>
</html>
