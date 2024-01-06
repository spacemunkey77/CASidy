<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <title>CASidy :: Sensor Events</title>
		<link href="{{ asset('css/sensorreport.css') }}" rel="stylesheet">
	</head>
	<body>
        @can('view_alarm_entries')
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">
                <img src="/images/{{$brandicon}}" width="30" height="30" class="d-inline-block align-top" alt="">
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
			<h1>🚨 Sensor events 🚨</h1>
	        <table class="table table-striped">
				<thead>
	                <tr>
	                    <th>Zone</th>
                        <th>Name</th>
	                    <th>Event</th>
	                </tr>
				</thead>
				<tbody>
	                @foreach($events as $event)
	                <tr>
	                    <td>{{$event->zone}}</td>
	                    <td>{{$event->name}}</td>
	                    <td>{{$event->event}}</td>
	                </tr>
	                @endforeach
				</tbody>
	    	</table>
		</div>
		<!-- Load JavaScript -->
		<script src="{{ asset('js/sensorreport.js') }}"></script>
        @endcan
	</body>
</html>
