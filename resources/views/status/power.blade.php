<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <title>CASidy :: Power Status</title>
		<link href="{{ asset('css/power.css') }}" rel="stylesheet">
	</head>
	<body>
        @can('view_power_status')
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">
                <img src="/images/houseicon.png" width="30" height="30" class="d-inline-block align-top" alt="">
                CASidy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
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
		<div class="container">
			<table class="table table-striped">
				<thead>
					<tr>
						<th colspan="2">APC Back-UPS ES 450 Information</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Battery Status:</th>
						<td>{{$batteryinfo->batterystatus}}</td>
					</tr>					
					<tr>
						<th>Line Voltage:</th>
						<td>{{$batteryinfo->linevoltage}} Volts</td>
					</tr>
					<tr>
						<th>Load Percentage:</th>
						<td>{{$batteryinfo->loadpercent}} %</td>
					</tr>
					<tr>
						<th>Battery Charge:</th>
						<td>{{$batteryinfo->batterycharge}} %</td>
					</tr>
					<tr>
						<th>Run Time Left:</th>
						<td>{{$batteryinfo->timeleft}} Minutes</td>
					</tr>
				</tbody>
			</table>
	        <table class="table table-striped">
				<thead>
	                <tr>
	                    <th>Time</th>
                        <th>⚡️Event</th>
	                </tr>
				</thead>
				<tbody>
	                @foreach($powerstatus as $status)
	                <tr>
	                    <td>{{$status->occurred}}</td>
                        <td>@if ($status->event == 0)
                            <img src="/images/powerout.png" title="Power Out">
                            @elseif ($status->event == 1)
                            <img src="/images/poweron.png" title="Power On">
                            @else
                            <img src="/images/mainson.png" title="Mains On">
                            @endif
	                    </td>
	                </tr>
	                @endforeach
				</tbody>
	    	</table>
		</div>
		<!-- Load JavaScript -->
		<script src="{{ asset('js/power.js') }}"></script>
        @endcan
	</body>
</html>
