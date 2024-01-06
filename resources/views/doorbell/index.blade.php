<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <title>CASidy :: Doorbell Events</title>
		<link href="{{ asset('css/doorbell.css') }}" rel="stylesheet">
	</head>
	<body>
      @can('view_door_entries')
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">
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
			<h1>Doorbell Events</h1>
	        <table class="table table-striped">
				<thead>
	                <tr>
                        <th>Action</th>
	                    <th>Time</th>
	                    <th>Doorbell</th>
                        <th>What</th>
	                </tr>
				</thead>
				<tbody>
	                @foreach($entries as $entry)
	                <tr>
                        <td>@if ($entry->dooraction == 1)
                            <a target="_blank" href="https://my.arlo.com"><img src="/images/dbmotion.png" title="Doorbell Motion Detected"></a>
                            @else
                            <a target="_blank" href="https://my.arlo.com"><img src="/images/dbrung.png" title="Doorbell Rung"></a>
                            @endif
	                    <td>{{$entry->created_at}}</td>
	                    <td>{{$entry->doorbell}}</td>
                        <td>{{$entry->object}}</td>
	                </tr>
	                @endforeach
				</tbody>
	    	</table>
		</div>
		<!-- Load JavaScript -->
		<script src="{{ asset('js/doorbell.js') }}"></script>
        @endcan
	</body>
</html>
